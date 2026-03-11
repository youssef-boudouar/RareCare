<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . env('GEMINI_API_KEY'),
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $request->message]
                        ]
                    ]
                ]
            ]
        );

        $result = $response->json();
        $answer = $result['candidates'][0]['content']['parts'][0]['text'];

        return response()->json([
            'answer' => $answer,
        ]);
    }
}
