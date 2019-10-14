<?php

namespace StayForLong\Infrastructure\Exceptions;

class InvalidSchemaException extends \Exception
{
    private $errors;

    public function __construct(array $errors)
    {
        $message = 'Invalid json for given schema';
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getErrorsString(): string
    {
        return 'Errors: ' . implode(', ', $this->errors);
    }
}
