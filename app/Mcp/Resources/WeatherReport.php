<?php

namespace App\Mcp\Resources;

use App\Models\Weather;
use Laravel\Mcp\Server\Resource;

class WeatherReport extends Resource
{
    protected string $description = 'A description of what this resource contains.';

    /**
     * Return the resource contents.
     */
    public function read(): string
    {
        return json_encode($this->items());
    }

    public function description(): string
    {
        return "Partage les 10 derniers rapports météo sauvegardés.";
    }

    public function items(): array
    {
        return Weather::latest()->take(10)->get()->toArray();
    }
}
