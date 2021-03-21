<?php

declare(strict_types=1);

namespace App\Domain;

use App\Exception\CustomerNotAllowedToPurchase;
use App\Exception\NoProductAddedToPurchase;
use App\Exception\ProductAlreadyPurchased;
use Money\Money;

class Purchase
{
    private string $id;

    private array $products = [];

    private Customer $customer;

    private Money $totalCost;

    private string $status;

    public function __construct(string $id, Customer $customer)
    {
        if (!$customer->isLegalAge()) {
            throw new CustomerNotAllowedToPurchase('Client is not of legal age');
        }
        $this->id = $id;
        $this->customer = $customer;
        $this->totalCost = Money::PLN(0);
        $this->status = PurchaseStatus::NEW;
    }

    /**
     * @throws CustomerNotAllowedToPurchase
     * @throws ProductAlreadyPurchased
     */
    public function addProduct(Product $product): void
    {
        if ($product->isPurchased()) {
            throw new ProductAlreadyPurchased('This product is already purchased');
        }

        $product->purchase();

        $this->totalCost = $this->totalCost->add($product->getPrice());
        $this->products[] = $product;
    }

    public function confirm(): void
    {
        if (\count($this->products) < 1) {
            throw new NoProductAddedToPurchase('Purchase should have at least one product added');
        }

        $this->status = PurchaseStatus::CONFIRMED;
    }

    public function isConfirmed(): bool
    {
        return $this->status === PurchaseStatus::CONFIRMED;
    }
}
