<?php

namespace App\Attributes;
use Attribute;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;
use Symfony\Component\HttpFoundation\Response as CodeResponse;

#[Attribute(Attribute::TARGET_METHOD)]
class UnauthorizedResponseAttribute extends Response
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct(
            response: CodeResponse::HTTP_UNAUTHORIZED,
            description: 'Unauthorized',
            content: new JsonContent(
                schema: 'error',
                example: [
                    'status' => 'error',
                    'message' => 'Unauthenticated',
                ]
            )
        );
    }
}
