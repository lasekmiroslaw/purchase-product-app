<?php

declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\Customer;
use App\Domain\Product;
use App\Domain\Purchase;
use App\Exception\CustomerNotAllowedToPurchase;
use App\Exception\NoProductAddedToPurchase;
use App\Exception\ProductAlreadyPurchased;
use Money\Money;
use PHPUnit\Framework\TestCase;

class PurchaseTest extends TestCase
{
    public function testCustomerCanPurchaseProduct(): void
    {
        $customer = new Customer(uniqid('c', true), 18);

        $product = new Product(uniqid('p', true), Money::PLN(random_int(1, 500)));

        $purchaseOrder = new Purchase(uniqid('pr', true), $customer);

        $purchaseOrder->addProduct($product);
        $purchaseOrder->confirm();

        $this->assertTrue($purchaseOrder->isConfirmed());
    }

    public function testCustomerCanPurchaseMultipleProducts(): void
    {
        $customer = new Customer(uniqid('c', true), 18);

        $product = new Product(uniqid('p', true), Money::PLN(random_int(1, 500)));
        $product2 = new Product(uniqid('p', true), Money::PLN(random_int(1, 500)));

        $purchaseOrder = new Purchase(uniqid('pr', true), $customer);

        $purchaseOrder->addProduct($product);
        $purchaseOrder->addProduct($product2);
        $purchaseOrder->confirm();

        $this->assertTrue($purchaseOrder->isConfirmed());
    }

    public function testCustomerCannotPurchaseWhenIsNotLegalAge(): void
    {
        $this->expectException(CustomerNotAllowedToPurchase::class);

        $customer = new Customer(uniqid('c', true), 16);

        $product = new Product(uniqid('p', true), Money::PLN(random_int(1, 500)));

        $purchaseOrder = new Purchase(uniqid('pr', true), $customer);

        $purchaseOrder->addProduct($product);
        $purchaseOrder->confirm();
    }

    public function testCustomerCannotPurchaseWhenProductIsAlreadyPurchased(): void
    {
        $this->expectException(ProductAlreadyPurchased::class);

        $customer = new Customer(uniqid('c', true), 18);

        $product = new Product(uniqid('p', true), Money::PLN(random_int(1, 500)));

        $purchaseOrder = new Purchase(uniqid('pr', true), $customer);
        $purchaseOrder2 = new Purchase(uniqid('pr', true), $customer);

        $purchaseOrder->addProduct($product);
        $purchaseOrder->confirm();

        $purchaseOrder2->addProduct($product);
        $purchaseOrder2->confirm();
    }

    public function testCustomerCannotPurchaseWhenNoProductsAdded(): void
    {
        $this->expectException(NoProductAddedToPurchase::class);

        $customer = new Customer(uniqid('c', true), 21);

        $product = new Product(uniqid('p', true), Money::PLN(random_int(1, 500)));
        $product->purchase();

        $purchaseOrder = new Purchase(uniqid('pr', true), $customer);

        $purchaseOrder->confirm();
    }

    public function testShouldCalculateTotalCostForSingleProduct(): void
    {
        $customer = new Customer(uniqid('c', true), 18);

        $product = new Product(uniqid('p', true), Money::PLN(100));

        $purchaseOrder = new Purchase(uniqid('pr', true), $customer);

        $purchaseOrder->addProduct($product);

        $this->assertEquals(100, $purchaseOrder->getTotalCost()->getAmount());
    }

    public function testShouldCalculateTotalCostForMultipleProducts(): void
    {
        $customer = new Customer(uniqid('c', true), 18);

        $product = new Product(uniqid('p', true), Money::PLN(100));
        $product2 = new Product(uniqid('p', true), Money::PLN(66));
        $product3 = new Product(uniqid('p', true), Money::PLN(11));

        $purchaseOrder = new Purchase(uniqid('pr', true), $customer);

        $purchaseOrder->addProduct($product);
        $purchaseOrder->addProduct($product2);
        $purchaseOrder->addProduct($product3);

        $this->assertEquals(177, $purchaseOrder->getTotalCost()->getAmount());
    }
}
