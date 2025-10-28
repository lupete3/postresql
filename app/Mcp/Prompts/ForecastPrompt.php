<?php

namespace App\Mcp\Prompts;

use Laravel\Mcp\Prompts\McpPrompt;

class ForecastPrompt extends McpPrompt
{
    public function description(): string
    {
        return "Génère une prévision météo commentée pour une ville et une date.";
    }

    public function template(): string
    {
        return "Peux-tu me donner une prévision météo détaillée pour {city} le {date} ?";
    }

    public function variables(): array
    {
        return ['city', 'date'];
    }
}
