<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $patients = Patient::all();

        $prompt = "You are an assistant for RareCare website.
               Here is the patient database: {$patients}
               Answer this question: {$request->message}";

        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . config('services.gemini.api_key'),
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]
        );

        if ($response->failed()) {
            return response()->json(['message' => 'AI service unavailable'], 503);
        }

        $result = $response->json();
        $answer = $result['candidates'][0]['content']['parts'][0]['text'];

        return response()->json([
            'answer' => $answer,
        ]);
    }
}
