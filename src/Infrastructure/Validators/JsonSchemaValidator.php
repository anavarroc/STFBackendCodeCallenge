<?php

namespace StayForLong\Infrastructure\Validators;

use JsonSchema\Validator;
use StayForLong\Infrastructure\Exceptions\InvalidSchemaException;

class JsonSchemaValidator
{
    public static function validate($data, $schema)
    {
        $validator = new Validator();
        $jsonSchemaPath = realpath(__DIR__ . '/../Schemas/' . $schema . '.json');
        $validator->validate(
            $data,
            (object) array('$ref' => 'file://' . $jsonSchemaPath)
        );

        if (!$validator->isValid()) {
            $errors = array_map(
                function ($error) {
                    return $error['property'] . ': ' . $error['message'];
                },
                $validator->getErrors()
            );
            throw new InvalidSchemaException($errors);
        }

        return true;
    }
}
