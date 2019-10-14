<?php

namespace Tests\unit\Domain\Bookings;

use PHPUnit\Framework\TestCase;
use StayForLong\Domain\Booking\Booking;
use StayForLong\Domain\Booking\BookingCollection;
use Tests\Iterators\JsonDataProviderIterator;

class BookingCollectionOverlapByTest extends TestCase
{
    /**
     * @dataProvider overlappedBookingsData
     */
    public function testOverlap($data)
    {
        $bookings = $this->collectionFromData($data);
        $bookingToCheck = Booking::fromArray($data['booking_to_check']);
        $this->assertTrue($bookings->overlapBy($bookingToCheck));
    }

    /**
     * @dataProvider bookingsData
     */
    public function testDontOverlap($data)
    {
        $bookings = $this->collectionFromData($data);
        $bookingToCheck = Booking::fromArray($data['booking_to_check']);
        $this->assertFalse($bookings->overlapBy($bookingToCheck));
    }

    private function collectionFromData($data): BookingCollection
    {
        $bookingCollection = new BookingCollection();
        foreach ($data['bookings'] as $bookingArray) {
            $bookingCollection->add(Booking::fromArray($bookingArray));
        }

        return $bookingCollection;
    }

    public function overlappedBookingsData()
    {
        return new JsonDataProviderIterator(__FILE__, 'OverlappedBookings');
    }

    public function bookingsData()
    {
        return new JsonDataProviderIterator(__FILE__, 'Bookings');
    }
}
