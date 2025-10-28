<?php

namespace App\Mcp\Servers;

use Laravel\Mcp\Server\McpServer;

class WeatherServer extends McpServer
{
    public function tools(): array
    {
        return [
            \App\Mcp\Tools\GetWeather::class,
        ];
    }

    public function resources(): array
    {
        return [
            \App\Mcp\Resources\WeatherReport::class,
        ];
    }

    public function prompts(): array
    {
        return [
            \App\Mcp\Prompts\ForecastPrompt::class,
        ];
    }
}


