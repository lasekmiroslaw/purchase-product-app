<?php

declare(strict_types=1);

namespace App\Domain;

class Customer
{
    private const LEGAL_AGE = 18;

    private string $id;

    private int $age;

    public function __construct(string $id, int $age)
    {
        $this->id = $id;
        $this->age = $age;
    }

    public function isLegalAge(): bool
    {
        return $this->age >= self::LEGAL_AGE;
    }
}
