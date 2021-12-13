<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class Service
{
    protected function parseJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }
        $request->request->replace($data);

        return $request;
    }
}