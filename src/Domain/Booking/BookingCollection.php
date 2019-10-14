<?php

namespace StayForLong\Domain\Booking;

use StayForLong\Domain\Common\CollectionInterface;

class BookingCollection implements CollectionInterface
{
    private $items;
    private $current;

    public function __construct()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->items = array();
        $this->rewind();
    }

    public function unshift($value)
    {
        array_unshift($this->items, $value);
        $this->rewind();
    }

    public function add(Booking $value)
    {
        $this->items[] = $value;
        $this->rewind();

        return $this;
    }

    public function current()
    {
        return $this->items[$this->current];
    }

    public function next()
    {
        ++$this->current;
    }

    public function key()
    {
        return $this->current;
    }

    public function valid()
    {
        return array_key_exists($this->current, $this->items);
    }

    public function rewind()
    {
        $this->current = 0;
    }

    public function count()
    {
        return count($this->items);
    }

    public function getBookingIds(): array
    {
        return array_map(
            function ($booking) { return $booking->getRequestId(); },
            $this->items
        );
    }

    public function overlapBy(Booking $newBooking): bool
    {
        foreach ($this->items as $booking) {
            if ($booking->overlapWith($newBooking)) {
                return true;
            }
        }

        return false;
    }
}
