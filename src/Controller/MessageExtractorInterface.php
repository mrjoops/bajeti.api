<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

interface MessageExtractorInterface
{
    public function extractMessageFromRequest(Request $request, string $type): object;
}
