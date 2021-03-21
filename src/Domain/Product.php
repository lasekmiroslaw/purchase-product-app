<?php

declare(strict_types=1);

namespace App\Domain;

use Money\Money;

class Product
{
    private string $id;

    private Money $price;

    private bool $isPurchased;

    public function __construct(string $id, Money $price)
    {
        $this->id = $id;
        $this->price = $price;
        $this->isPurchased = false;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function purchase(): void
    {
        $this->isPurchased = true;
    }

    public function isPurchased(): bool
    {
        return $this->isPurchased;
    }
}
