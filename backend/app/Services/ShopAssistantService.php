<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShopAssistantService
{
    public function reply(string $message): string
    {
        $apiKey = (string) config('services.gemini.key');

        if ($apiKey === '') {
            return $this->offlineReply($message);
        }

        try {
            $response = Http::connectTimeout(5)
                ->timeout(30)
                ->acceptJson()
                ->withHeaders(['x-goog-api-key' => $apiKey])
                ->post('https://generativelanguage.googleapis.com/v1beta/models/'.config('services.gemini.model').':generateContent', [
                    'systemInstruction' => [
                        'parts' => [['text' => $this->systemPrompt()]],
                    ],
                    'contents' => [[
                        'role' => 'user',
                        'parts' => [['text' => $message]],
                    ]],
                    'generationConfig' => ['maxOutputTokens' => 350],
                ]);
        } catch (ConnectionException) {
            return $this->offlineReply($message);
        }

        $text = trim((string) $response->json('candidates.0.content.parts.0.text', ''));

        if ($response->successful() && $text !== '') {
            return $text;
        }

        Log::warning('Gemini chat request failed.', [
            'status' => $response->status(),
            'error' => $response->json('error.message'),
        ]);

        return $this->offlineReply($message);
    }

    private function offlineReply(string $message, bool $ollamaUnavailable = false): string
    {
        $text = mb_strtolower($message);
        $prefix = $ollamaUnavailable
            ? 'Luc AI chưa khởi động trên máy, nên đang ở chế độ hỗ trợ cơ bản. '
            : 'Luc AI đang ở chế độ hỗ trợ cơ bản. ';

        if (str_contains($text, 'voucher') || str_contains($text, 'mã giảm') || str_contains($text, 'khuyến mãi')) {
            return $prefix.'Bạn vào mục Voucher để nhận mã. Voucher vận chuyển có thể nhận trực tiếp; một số voucher giảm đơn hàng cần nhập mã khi thanh toán.';
        }

        if (str_contains($text, 'đơn') || str_contains($text, 'giao hàng') || str_contains($text, 'vận chuyển')) {
            return $prefix.'Bạn có thể xem trạng thái trong mục Đơn hàng. Khi cần hỗ trợ, hãy chuẩn bị mã đơn để cửa hàng kiểm tra nhanh hơn.';
        }

        if (str_contains($text, 'laptop') || str_contains($text, 'điện thoại') || str_contains($text, 'tai nghe') || str_contains($text, 'phụ kiện')) {
            return $prefix.'Bạn hãy vào Sản phẩm, lọc đúng danh mục và so sánh cấu hình, giá cùng chính sách bảo hành trước khi đặt. Bạn đang muốn tìm sản phẩm trong khoảng giá bao nhiêu?';
        }

        if (str_contains($text, 'thanh toán') || str_contains($text, 'trả tiền')) {
            return $prefix.'Bạn kiểm tra lại thông tin nhận hàng và voucher ở trang thanh toán trước khi xác nhận đơn.';
        }

        return $prefix.'Mình vẫn có thể hướng dẫn cơ bản về sản phẩm, voucher, đơn hàng và thanh toán. Bạn muốn hỗ trợ mục nào?';
    }

    private function systemPrompt(): string
    {
        $catalog = Product::query()
            ->with(['brand:id,name', 'category:id,name'])
            ->where('status', 'active')
            ->orderBy('name')
            ->limit(80)
            ->get(['id', 'category_id', 'brand_id', 'name', 'price', 'sale_price', 'quantity', 'description'])
            ->map(function (Product $product): string {
                $price = $product->sale_price ?? $product->price;
                $description = Str::squish(strip_tags((string) $product->description));

                return sprintf(
                    '- %s | %s | %s | giá %sđ | %s | %s',
                    $product->name,
                    $product->brand?->name ?? 'Không rõ thương hiệu',
                    $product->category?->name ?? 'Không rõ danh mục',
                    number_format((float) $price, 0, ',', '.'),
                    $product->quantity > 0 ? 'còn hàng' : 'tạm hết hàng',
                    Str::limit($description, 180)
                );
            })
            ->implode("\n");

        return "Bạn là Luc AI, trợ lý mua sắm thân thiện của Luc Tech Shop. Luôn trả lời bằng tiếng Việt, ngắn gọn và hữu ích. Chỉ tư vấn sản phẩm, đơn hàng, thanh toán, voucher và chính sách mua sắm.\n\nDANH MỤC SẢN PHẨM ĐANG BÁN (đây là nguồn chính xác):\n{$catalog}\n\nQuy tắc bắt buộc: Khi khách hỏi một sản phẩm có trong danh mục, phải xác nhận sản phẩm có bán và chỉ dùng tên, giá, tình trạng trong danh mục. Tuyệt đối không nói sản phẩm đó chưa được công bố hoặc tự đoán thông tin. Nếu không có trong danh mục, nói rõ cửa hàng hiện chưa thấy sản phẩm đó trong danh sách. Không bịa tồn kho, giá, khuyến mãi hoặc thông tin đơn hàng.";
    }
}
