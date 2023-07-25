<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

class ApiRequestException extends \Exception
{
    public function __construct(string $message = '', int $code = 400, \Throwable $previous = null)
    {
        parent::__construct('Api Request Exception. '.$message, $code, $previous);
    }
}
