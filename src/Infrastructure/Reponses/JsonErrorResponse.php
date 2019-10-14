<?php

namespace StayForLong\Infrastructure\Reponses;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonErrorResponse extends JsonResponse
{
    public function __construct(\Exception $e)
    {
        parent::__construct(
            array('message' => $e->getMessage()),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
