<?php

namespace Tests\api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\Iterators\JsonDataProviderIterator;

class MaximizeBookingEndpointTest extends TestCase
{
    const API_ENDPOINT = 'web/booking/maximize';
    const CONTENT_TYPE = 'application/json';

    /**
     * @dataProvider correctData
     */
    public function testResponseOkAndMatchData($data)
    {
        $client = $this->getClient();
        $response = $client->post(
            self::API_ENDPOINT,
            array(
                RequestOptions::JSON => $data['bookings'],
            )
        );
        $this->assertResponse(
            $data,
            $response,
            Response::HTTP_OK
        );
    }

    /**
     * @dataProvider requiredFieldsData
     */
    public function testBadRequestByFieldsRequired($data)
    {
        try {
            $client = $this->getClient();
            $response = $client->post(
                self::API_ENDPOINT,
                array(
                    RequestOptions::JSON => $data['bookings'],
                )
            );
        } catch (RequestException $e) {
            $this->assertResponse(
                $data,
                $e->getResponse(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @dataProvider badConstructionData
     */
    public function testBadRequestByWrongBodyConstruction($data)
    {
        try {
            $client = $this->getClient();
            $response = $client->post(
                self::API_ENDPOINT,
                array(
                    RequestOptions::JSON => $data['bookings'],
                )
            );
        } catch (RequestException $e) {
            $this->assertResponse(
                $data,
                $e->getResponse(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    private function assertResponse($data, ResponseInterface $response, $statusCode): void
    {
        $this->assertEquals(
            $data['expected_response'],
            json_decode($response->getBody()->getContents(), true));
        $this->assertEquals(
            $statusCode,
            $response->getStatusCode());
        $this->assertEquals(
            self::CONTENT_TYPE,
            $response->getHeader('content-type')[0]);
    }

    private function getClient(): Client
    {
        $client = new Client(array(
            'defaults' => array(
                'headers' => array(
                    'content-type' => self::CONTENT_TYPE,
                    'Accept' => self::CONTENT_TYPE,
                ),
                'body' => json_encode(array()),
            ),
        ));

        return $client;
    }

    public function correctData()
    {
        return new JsonDataProviderIterator(__FILE__, 'CorrectData');
    }

    public function requiredFieldsData()
    {
        return new JsonDataProviderIterator(__FILE__, 'RequiredFields');
    }

    public function badConstructionData()
    {
        return new JsonDataProviderIterator(__FILE__, 'BadConstruction');
    }
}
