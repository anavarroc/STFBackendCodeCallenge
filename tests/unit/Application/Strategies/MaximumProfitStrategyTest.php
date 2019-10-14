<?php

namespace Tests\unit\Application\Strategies;

use PHPUnit\Framework\TestCase;
use StayForLong\Application\Booking\Strategies\MaximumProfitProfitStrategy;
use StayForLong\Domain\Booking\Booking;
use StayForLong\Domain\Booking\BookingCollection;
use Tests\Iterators\JsonDataProviderIterator;

class MaximumProfitStrategyTest extends TestCase
{
    /**
     * @dataProvider bookingCollectionData
     */
    public function testMinimumProfitPerNight($data)
    {
        $bookings = $this->collectionFromData($data);
        $strategy = new MaximumProfitProfitStrategy();
        $this->assertEquals($data['expected_max'], $strategy->execute($bookings));
    }

    public function bookingCollectionData()
    {
        return new JsonDataProviderIterator(__FILE__, 'BookingCollections');
    }

    private function collectionFromData($data): BookingCollection
    {
        $bookingCollection = new BookingCollection();
        foreach ($data['bookings'] as $bookingArray) {
            $bookingCollection->add(Booking::fromArray($bookingArray));
        }

        return $bookingCollection;
    }
}
