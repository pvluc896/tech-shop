<?php

namespace App\Http\Controllers;

use App\Services\ShopAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ShopAssistantController extends Controller
{
    public function __invoke(Request $request, ShopAssistantService $assistant): JsonResponse
    {
        $data = $request->validate(['message' => ['required', 'string', 'max:1000']]);

        try {
            return response()->json(['reply' => $assistant->reply($data['message'])]);
        } catch (Throwable $exception) {
            report($exception);
            return response()->json(['message' => 'Trợ lý AI đang tạm thời không khả dụng. Vui lòng thử lại sau.'], 503);
        }
    }
}
