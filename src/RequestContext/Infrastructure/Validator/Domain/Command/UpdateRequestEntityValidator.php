<?php

namespace App\RequestContext\Infrastructure\Validator\Domain\Command;

class UpdateRequestEntityValidator
{
    public static function validate(array $data): array
    {
        if (!isset($data['name']) || !is_string($data['name']) || empty(trim($data['name']))) {
            throw new \InvalidArgumentException("The 'name' field is required and must be a non-empty string.");
        }

        return $data;
    }
}