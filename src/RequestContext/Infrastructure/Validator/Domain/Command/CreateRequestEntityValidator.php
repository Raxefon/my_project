<?php

namespace App\RequestContext\Infrastructure\Validator\Domain\Command;

class CreateRequestEntityValidator
{
    public static function validate(array $data): array
    {
        if (!isset($data['name']) || !is_string($data['name']) || empty(trim($data['name']))) {
            throw new \InvalidArgumentException();
        }

        return $data;
    }
}