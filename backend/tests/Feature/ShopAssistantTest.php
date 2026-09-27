<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShopAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_assistant_uses_gemini_api(): void
    {
        config([
            'services.gemini.key' => 'test-api-key',
            'services.gemini.model' => 'gemini-3.8-flash',
        ]);

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent' => Http::response([
                'candidates' => [[
                    'content' => ['parts' => [['text' => 'Chào bạn, tôi có thể hỗ trợ tìm sản phẩm.']]],
                ]],
            ]),
        ]);

        $this->postJson(route('assistant.chat'), ['message' => 'Tư vấn laptop giúp tôi'])
            ->assertOk()
            ->assertJsonPath('reply', 'Chào bạn, tôi có thể hỗ trợ tìm sản phẩm.');

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent'
                && $request->hasHeader('x-goog-api-key', 'test-api-key')
                && $request['contents'][0]['parts'][0]['text'] === 'Tư vấn laptop giúp tôi';
        });
    }
}
