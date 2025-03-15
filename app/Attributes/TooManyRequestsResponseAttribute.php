<?php

namespace App\Attributes;

use Attribute;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;
use Symfony\Component\HttpFoundation\Response as CodeResponse;

#[Attribute(Attribute::TARGET_METHOD)]
class TooManyRequestsResponseAttribute extends Response
{
    public function __construct()
    {
        parent::__construct(
            response: CodeResponse::HTTP_TOO_MANY_REQUESTS,
            description: 'Too many requests',
            content: new JsonContent(
                schema: 'error',
                example: [
                    'status' => 'error',
                    'message' => 'Too many attempts, please slow down the request rate.',
                ]
            )
        );
    }
}
