<?php

namespace App\Helpers;

use CodeIgniter\HTTP\ResponseInterface;

class ResponseFormatter
{
    /**
     * Format response success
     */
    public static function success($data = null, string $message = 'success', int $code = 200)
    {
        $response = [
            'status'  => $code,
            'message' => $message,
            'errors'  => null,
            'data'    => $data,
        ];

        return service('response')
            ->setStatusCode($code)
            ->setJSON($response);
    }

    /**
     * Format response error
     */
    public static function error(string $message = 'error', $errors = null, int $code = 400)
    {
        $response = [
            'status'  => $code,
            'message' => $message,
            'errors'  => $errors,
            'data'    => null,
        ];

        return service('response')
            ->setStatusCode($code)
            ->setJSON($response);
    }
}
