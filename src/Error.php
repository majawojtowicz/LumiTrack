<?php

class Error
{
    public static function handleException(Throwable $e): void
    {
        http_response_code(500);
        require __DIR__ . '/../../public/views/500.html';
        exit;
    }

    public static function handleError(
        int $severity,
        string $message,
        string $file,
        int $line
    ): bool {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
}
