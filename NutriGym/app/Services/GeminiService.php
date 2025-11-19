<?php

namespace App\Services;

class GeminiService
{
    public function generateContent($prompt)
    {
        $apiKey = env('GOOGLE_API_KEY');
        
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-lite:generateContent?key=' . $apiKey;
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return "Error: HTTP {$httpCode}";
        }
        
        $responseData = json_decode($response, true);
        
        // Extraer texto de manera simple - probar diferentes estructuras
        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            return $responseData['candidates'][0]['content']['parts'][0]['text'];
        }
        
        if (isset($responseData['candidates'][0]['content']['parts'][0])) {
            return $responseData['candidates'][0]['content']['parts'][0];
        }
        
        if (isset($responseData['candidates'][0]['content']['text'])) {
            return $responseData['candidates'][0]['content']['text'];
        }
        
        // Si no funciona, devolver un menú predeterminado
    }
}