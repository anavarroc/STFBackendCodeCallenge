<?php

namespace StayForLong\Domain\Booking;

use DateTime;

class Booking
{
    private $requestId;
    private $checkInDate;
    private $numberOfNights;
    private $sellingRate;
    private $salesMargin;

    public function __construct(
        $requestId,
        DateTime $checkInDate,
        $numberOfNights,
        $sellingRate,
        $salesMargin
    ) {
        $this->requestId = $requestId;
        $this->checkInDate = $checkInDate;
        $this->numberOfNights = $numberOfNights;
        $this->sellingRate = $sellingRate;
        $this->salesMargin = $salesMargin;
    }

    public static function fromArray($bookingArray)
    {
        return new self(
            $bookingArray['request_id'],
            new DateTime($bookingArray['check_in']),
            $bookingArray['nights'],
            $bookingArray['selling_rate'],
            $bookingArray['margin']
        );
    }

    public function getTotalProfit(): float
    {
        return round($this->sellingRate * $this->salesMargin / 100, 2);
    }

    public function getProfitPerNight(): float
    {
        return round($this->getTotalProfit() / $this->numberOfNights, 2);
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

    public function getCheckInDate(): DateTime
    {
        return $this->checkInDate;
    }

    public function getCheckOutDate(): DateTime
    {
        $checkOutDate = clone $this->checkInDate;
        $checkOutDate->modify('+' . $this->numberOfNights . 'days');

        return $checkOutDate;
    }

    public function getNumberOfNights(): int
    {
        return $this->numberOfNights;
    }

    public function getSellingRate(): float
    {
        return $this->sellingRate;
    }

    public function getSalesMargin(): float
    {
        return $this->salesMargin;
    }

    public function overlapWith(Booking $booking): bool
    {
        if (
        (
            $this->checkInDate <= $booking->getCheckOutDate()
            && $this->checkInDate >= $booking->getCheckInDate())
        || (
            $this->getCheckOutDate() <= $booking->getCheckOutDate()
            && $this->getCheckOutDate() >= $booking->getCheckInDate())
        ) {
            return true;
        }

        return false;
    }
}
