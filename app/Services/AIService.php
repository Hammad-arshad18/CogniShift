<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        $this->model = config('services.openai.model', 'gpt-4o');
    }

    /**
     * Generate a professional performance review from bullet points.
     */
    public function generatePerformanceReview(string $bulletPoints, string $employeeName): ?string
    {
        $systemPrompt = "You are an expert HR Manager. Your job is to take raw bullet points about an employee's performance and rewrite them into a highly professional, empathetic, and constructive performance review document. Maintain a balanced tone.";
        $userPrompt = "Employee Name: {$employeeName}\n\nPerformance Notes:\n{$bulletPoints}\n\nPlease generate the official review document.";

        return $this->callOpenAI($systemPrompt, $userPrompt);
    }

    /**
     * Analyze attendance and overtime records to predict burnout.
     */
    public function predictBurnout(array $employeeData): ?string
    {
        $systemPrompt = "You are an AI Occupational Psychologist. Analyze the provided employee attendance and overtime data. Identify any anomalies or high probabilities of burnout based on consecutive late clock-outs, excessive overtime, or lack of leave. Respond with a concise warning and reasoning if burnout risk is high, otherwise respond 'LOW_RISK'.";
        $userPrompt = "Employee Data JSON:\n" . json_encode($employeeData);

        return $this->callOpenAI($systemPrompt, $userPrompt);
    }

    /**
     * Natural Language SQL Reporting
     */
    public function generateSQLFromNaturalLanguage(string $query, string $schema): ?string
    {
        $systemPrompt = "You are a Postgres SQL expert. Given the database schema, translate the user's natural language question into a valid, read-only SQL query. Return ONLY the raw SQL query, without any markdown formatting or explanations.";
        $userPrompt = "Schema:\n{$schema}\n\nQuestion: {$query}";

        return $this->callOpenAI($systemPrompt, $userPrompt);
    }

    protected function callOpenAI(string $systemPrompt, string $userPrompt): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenAI API Key is missing.');
            return "AI Generation is currently unavailable (Missing API Key).";
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::error('OpenAI API Error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('OpenAI Exception: ' . $e->getMessage());
            return null;
        }
    }
}
