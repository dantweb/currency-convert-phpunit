<?php declare(strict_types=1);

namespace App\Exception;

use Exception;

class WrongDataException extends Exception
{
    protected const BASIC_ERROR = 'Wrong data format in currency rates source ';

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::BASIC_ERROR . ' ' . $message, $code, $previous);
    }
}