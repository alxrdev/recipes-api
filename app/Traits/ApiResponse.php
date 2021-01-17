<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success(string $message, mixed $data, int $status = 200)
    {
        return response()
            ->json([
                'message' => $message,
                'data' => $data
            ],
            $status
        );
    }

    protected function failure(string $message, mixed $errors, int $status = 400)
    {
        return response()
            ->json([
                'message' => $message,
                'errors' => $errors
            ],
            $status
        );
    }
}