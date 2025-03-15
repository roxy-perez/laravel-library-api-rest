<?php

namespace App\Attributes;

use Attribute;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;
use Symfony\Component\HttpFoundation\Response as CodeResponse;

#[Attribute(Attribute::TARGET_METHOD)]
class NotFoundResponseAttribute extends Response
{
    /**
     * Create a new class instance.
     */
    public function __construct(string $description)
    {
        parent::__construct(
            response: CodeResponse::HTTP_NOT_FOUND,
            description: $description,
            content: new JsonContent(
                schema: 'json',
                example: [
                    'status' => 'error',
                    'message' => $description
                ]
            )
        );
    }
}
