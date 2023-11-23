<?php

/**
 * This file serves as a visual example of the code working.
 * See README.md for instructions on how to run it.
 */

require_once './vendor/autoload.php';

use Howsy\CodeChallenge\Basket;
use Howsy\CodeChallenge\Entities\Product;
use Howsy\CodeChallenge\Offers\PercentageDiscount;
use Howsy\CodeChallenge\ValueObjects\Money;
use Howsy\CodeChallenge\ValueObjects\ProductCode;

// Initialise the basket with offers
$basket = new Basket([
    new PercentageDiscount('20'),
]);

// Add products to the basket
$basket->add(new Product(ProductCode::from('P001'), 'Photography', new Money(200_00)));
$basket->add(new Product(ProductCode::from('P002'), 'Floorplan', new Money(100_00)));
$basket->add(new Product(ProductCode::from('P003'), 'Gas Certificate', new Money(83_50)));
$basket->add(new Product(ProductCode::from('P004'), 'EICR Certificate', new Money(51_00)));

$lineLength = 65;
$formattedTotal = number_format($basket->total()->value() / 100, 2);

// Display any items
if (! empty($basket->getItems())) {
    foreach ($basket->getItems() as $item) {
        $formattedPrice = number_format($item->price()->value() / 100, 2);

        echo $item->name() . ' ' . str_repeat('.', $lineLength - strlen($item->name()) - strlen($formattedPrice)) . ' ' . $formattedPrice . "\n";
    }
} else {
    echo "Basket is empty\n";
}

// Display any offers
if (! empty($basket->getOffers())) {
    echo str_repeat('=', $lineLength + 2) . "\n";

    foreach ($basket->getOffers() as $offer) {
        echo 'Offer: ' . $offer->description() . "\n";
    }
}

// Display basket total with offers applied
echo str_repeat('=', $lineLength + 2) . "\n";
echo 'Total ' . str_repeat('.', $lineLength - 5 - strlen($formattedTotal)) . ' ' . $formattedTotal . "\n";
