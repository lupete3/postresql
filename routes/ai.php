<?php

use Laravel\Mcp\Server\Facades\Mcp;
use App\Mcp\Servers\WeatherServer;

Mcp::web('demo', \App\Mcp\Servers\PublicServer::class); // Available at /mcp/demo
Mcp::local('demo', \App\Mcp\Servers\LocalServer::class); // Start with ./artisan mcp:start demo


Mcp::web('/mcp/weather', WeatherServer::class);

Mcp::local('weather', WeatherServer::class);
