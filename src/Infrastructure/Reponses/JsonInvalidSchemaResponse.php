<?php

namespace StayForLong\Infrastructure\Reponses;

use StayForLong\Infrastructure\Exceptions\InvalidSchemaException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonInvalidSchemaResponse extends JsonResponse
{
    public function __construct(InvalidSchemaException $e)
    {
        parent::__construct(
            array('message' => $e->getErrorsString()),
            Response::HTTP_BAD_REQUEST,
        );
    }
}
