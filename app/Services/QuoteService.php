<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class QuoteService
{
    public string $endpoint;

    public function __construct()
    {
        $this->endpoint = 'https://zenquotes.io/api/';
    }

    private function transformQuote(array $quote): array
    {
        // q = quote text
        // a = author name
        // i =? author image (key required)
        // c =? character count
        // h = pre-formatted HTML quote
        return [
            'quote' => $quote['q'] ?? '',
            'author' => $quote['a'] ?? '',
            'author_image' => $quote['i'] ?? null,
            'char_count' => $quote['c'] ?? null,
            'html' => $quote['h'] ?? '',
        ];
    }

    public function getRandomQuote(): ?array
    {
        try {
            $response = Http::get($this->endpoint.'random')
                ->throw()
                ->json();

            return $this->transformQuote($response[0]);
        } catch (\Exception $e) {
            report($e);

            return null;
        }
    }
}
