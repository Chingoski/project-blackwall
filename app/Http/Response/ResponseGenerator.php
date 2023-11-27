<?php

namespace App\Http\Response;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ResponseGenerator
{
    public function success(array $data, string $message = 'Success'): Response
    {
        return $this->generateJsonResponse(ResponseCodes::HTTP_OK, $message, $data);
    }

    public function noContent(): Response
    {
        return $this->generateJsonResponse(ResponseCodes::HTTP_NO_CONTENT);
    }

    public function unauthorized(string $message = 'Unauthorized'): Response
    {
        $message = strlen($message) > 0 ? $message : 'Unauthorized';

        return $this->generateJsonResponse(ResponseCodes::HTTP_UNAUTHORIZED, $message);
    }

    public function unprocessableEntity(?array $data = [], string $message = 'Unprocessable entity'): Response
    {
        return $this->generateJsonResponse(ResponseCodes::HTTP_UNPROCESSABLE_ENTITY, $message, $data);
    }

    public function serverError(): Response
    {
        return $this->generateJsonResponse(ResponseCodes::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong');
    }

    public function forbidden(): Response
    {
        return $this->generateJsonResponse(ResponseCodes::HTTP_FORBIDDEN, 'Forbidden');
    }

    public function notFound(string $message = 'Not Found'): Response
    {
        return $this->generateJsonResponse(ResponseCodes::HTTP_NOT_FOUND, $message);
    }

    protected function generateJsonResponse(int $httpCode, ?string $message = null, ?array $data = null): Response
    {
        $responseBody = match (true) {
            is_null($data), empty($data) => ['meta' => [], 'data' => []],
            default => $data
        };

        if (isset($message)) {
            $responseBody['meta']['message'] = $message;

        }

        $responseBody['meta']['code'] = $httpCode;

        $response = new Response($responseBody, $httpCode);
        $response->withHeaders($response->headers);

        return $response;
    }
}
