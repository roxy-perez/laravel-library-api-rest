<?php

namespace App\Attributes;

use Attribute;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;
use Symfony\Component\HttpFoundation\Response as CodeResponse;

#[Attribute (Attribute::TARGET_METHOD)]
class ValidationErrorResponseAttribute extends Response
{
    /**
     * Create a new class instance.
     */
    public function __construct(array $errors)
    {
        parent::__construct(
            response: CodeResponse::HTTP_UNPROCESSABLE_ENTITY,
            description: 'Validation error',
            content: new JsonContent(
                schema: 'error',
                example: [
                    'status' => 'error',
                    'message' => 'The given data was invalid.',
                    'errors' => $errors,
                ]
            )
        );
    }
}
