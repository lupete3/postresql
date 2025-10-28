<?php

namespace App\Mcp\Tools;

use App\Models\Weather;
use Generator;
use Illuminate\Support\Facades\Http;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\Title;
use Laravel\Mcp\Server\Tools\ToolInputSchema;
use Laravel\Mcp\Server\Tools\ToolResult;

#[Title('Get Weather')]
class GetWeather extends Tool
{
    /**
     * A description of the tool.
     */
    public function description(): string
    {
        return "Récupère la météo actuelle pour une ville et sauvegarde le rapport.";
    }

    /**
     * The input schema of the tool.
     */
    public function schema(ToolInputSchema $schema): ToolInputSchema
    {
        $schema->string('example')
            ->description('An example input description.')
            ->required();

        return $schema;
    }

    /**
     * Execute the tool call.
     *
     * @return ToolResult|Generator
     */
    public function handle(array $arguments): ToolResult|Generator
    {
        $city = $input['city'] ?? 'Kinshasa';
        $apiKey = env('OPENWEATHER_API_KEY');

        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'fr',
        ]);

        if ($response->failed()) {
            return "Impossible de récupérer la météo pour {$city}.";
        }

        $data = $response->json();

        // Sauvegarde dans la base
        $weather = Weather::create([
            'city' => $data['name'],
            'temperature' => $data['main']['temp'],
            'conditions' => $data['weather'][0]['description'],
            'humidity' => $data['main']['humidity'],
        ]);

        return [
            'ville' => $weather->city,
            'temperature' => $weather->temperature,
            'conditions' => $weather->conditions,
            'humidite' => $weather->humidity,
        ];
    }

    public function inputs(): array
    {
        return [
            'city' => 'Nom de la ville',
        ];
    }
}
