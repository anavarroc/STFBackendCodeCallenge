<?php

namespace StayForLong\Infrastructure\Controllers;

use StayForLong\Application\Booking\Strategies\AverageProfitProfitStrategy;
use StayForLong\Application\Booking\Strategies\MaximumProfitProfitStrategy;
use StayForLong\Application\Booking\Strategies\MinimumProfitProfitStrategy;
use StayForLong\Application\Booking\Strategies\MostProfitableCombinationBookingsStrategy;
use StayForLong\Application\Booking\Strategies\TotalProfitProfitStrategy;
use StayForLong\Domain\Booking\Booking;
use StayForLong\Domain\Booking\BookingCollection;
use StayForLong\Infrastructure\Exceptions\InvalidSchemaException;
use StayForLong\Infrastructure\Reponses\JsonErrorResponse;
use StayForLong\Infrastructure\Reponses\JsonInvalidSchemaResponse;
use StayForLong\Infrastructure\Validators\JsonSchemaValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends AbstractController
{
    const BOOKING_REQUEST_LIST_SCHEMA = 'BookingRequestList';

    public function maximize(Request $request)
    {
        try {
            return new JsonResponse(
                $this->getMostProfitableCombination($request),
                Response::HTTP_OK,
                array(
                    'Content-type' => 'application/json',
                )
            );
        } catch (InvalidSchemaException $e) {
            return new JsonInvalidSchemaResponse($e);
        } catch (\Exception $e) {
            return new JsonErrorResponse($e);
        }
    }

    private function getMostProfitableCombination(Request $request)
    {
        $bookings = $this->bookingCollectionFromRequest($request);
        $mostProfitableCombinationStrategy =
            new MostProfitableCombinationBookingsStrategy(new TotalProfitProfitStrategy());
        $newBookingCombination = $mostProfitableCombinationStrategy->execute($bookings);

        return array_merge(
            array(
                'request_ids' => $newBookingCombination->getBookingIds(),
            ),
            $this->getProfits($newBookingCombination)
        );
    }

    public function stats(Request $request)
    {
        try {
            return new JsonResponse(
                $this->getStats($request),
                Response::HTTP_OK,
                array(
                    'Content-type' => 'application/json',
                )
            );
        } catch (InvalidSchemaException $e) {
            return new JsonInvalidSchemaResponse($e);
        } catch (\Exception $e) {
            return new JsonErrorResponse($e);
        }
    }

    private function getStats(Request $request)
    {
        $bookings = $this->bookingCollectionFromRequest($request);

        return $this->getProfits($bookings);
    }

    private function getProfits(BookingCollection $bookings): array
    {
        $profits = array();

        $strategies = array(
            new TotalProfitProfitStrategy(),
            new AverageProfitProfitStrategy(),
            new MaximumProfitProfitStrategy(),
            new MinimumProfitProfitStrategy(),
        );

        foreach ($strategies as $strategy) {
            $profits[$strategy->getName()] = $strategy->execute($bookings);
        }

        return $profits;
    }

    private function bookingCollectionFromRequest(Request $request): BookingCollection
    {
        $content = $request->getContent();
        $this->validateBookingRequestList($content);
        $bookingsArray = json_decode($content, true);
        $bookingCollection = new BookingCollection();
        foreach ($bookingsArray as $booking) {
            $bookingCollection->add(Booking::fromArray($booking));
        }

        return $bookingCollection;
    }

    private function validateBookingRequestList(string $content): void
    {
        JsonSchemaValidator::validate(
            json_decode($content),
            self::BOOKING_REQUEST_LIST_SCHEMA
        );
    }
}
