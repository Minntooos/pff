<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Import Log facade for debugging

class PromptController extends Controller
{
    public function generatePrompt(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            // Sanitize the description by stripping HTML tags
            $sanitizedDescription = strip_tags($request->description);

            // Construct the input prompt with explicit instructions
            $inputPrompt = "You are an AI assistant. Given the following information, generate a concise prompt for an AI image generator to create a suitable image for the resource. **Only output the prompt below and do not include any other text.**\n\n"
                        ."Title: {$request->title}\n\n"
                        ."Description: {$sanitizedDescription}\n\n"
                        .'Image prompt:';

            // Log the sanitized input prompt for debugging
            Log::info('Sanitized Input Prompt:', ['prompt' => $inputPrompt]);

            // Make the API request to Hugging Face
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.env('HUGGING_FACE_TOKEN'), // Use environment variable
                'Content-Type' => 'application/json',
            ])->post('https://api-inference.huggingface.co/models/meta-llama/Meta-Llama-3-8B-Instruct', [
                'inputs' => $inputPrompt,
                'options' => [
                    'use_cache' => false,
                    'wait_for_model' => true,
                ],
            ]);

            if ($response->successful()) {
                $result = $response->json();

                // Log the API response for debugging
                Log::info('Hugging Face API Response:', $result);

                // Assuming the API returns an array with 'generated_text'
                $generatedPrompt = $result[0]['generated_text'] ?? null;

                if ($generatedPrompt) {
                    // Extract only the text after "Image prompt:"
                    $delimiter = 'Image prompt:';
                    $promptPosition = strpos($generatedPrompt, $delimiter);

                    if ($promptPosition !== false) {
                        // Extract the substring after the delimiter
                        $cleanPrompt = substr($generatedPrompt, $promptPosition + strlen($delimiter));
                        $cleanPrompt = trim($cleanPrompt);

                        // Optional: Remove any trailing unwanted characters or sentences
                        // For example, if the AI adds "**Render the image with exceptional detail and realism.**", remove it
                        // You can adjust the pattern based on the actual unwanted text
                        $cleanPrompt = preg_replace('/\*\*.*\*\*/', '', $cleanPrompt);
                        $cleanPrompt = trim($cleanPrompt);
                    } else {
                        // If delimiter not found, fallback to the entire generated prompt
                        $cleanPrompt = trim($generatedPrompt);
                    }

                    // Return the clean prompt in the expected format
                    return response()->json(['prompt' => $cleanPrompt]);
                } else {
                    // Log the unexpected structure
                    Log::warning('Unexpected API response structure:', $result);

                    return response()->json(['error' => 'Unexpected response structure from API.'], 500);
                }
            } else {
                // Capture and log the error message from the API
                $error = $response->json()['error'] ?? 'Unknown error occurred.';
                Log::error('Hugging Face API Error:', ['error' => $error]);

                return response()->json(['error' => 'Failed to generate prompt: '.$error], $response->status());
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception occurred while generating prompt:', ['exception' => $e->getMessage()]);

            // Handle any exceptions that occur during the request
            return response()->json(['error' => 'An error occurred: '.$e->getMessage()], 500);
        }
    }
}
