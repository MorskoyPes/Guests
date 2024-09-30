<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    public static function addDebugHeaders(Response $response, float $startTime): Response
    {
        $executionTime = microtime(true) - $startTime;
        $response->headers->set('X-Debug-Time', (string)$executionTime);
        $response->headers->set('X-Debug-Memory', (string)memory_get_usage());

        return $response;
    }
}
