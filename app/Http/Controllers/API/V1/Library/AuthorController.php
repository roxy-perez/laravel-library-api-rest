<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Models\Author;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Schema;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\JsonContent;
use App\Http\Controllers\API\V1\Controller;
use App\Services\API\V1\ApiResponseService;
use App\Attributes\NotFoundResponseAttribute;
use App\Http\Resources\API\V1\AuthorResource;
use Symfony\Component\HttpFoundation\Response;
use App\Attributes\UnauthorizedResponseAttribute;
use App\Attributes\TooManyRequestsResponseAttribute;
use App\Attributes\ValidationErrorResponseAttribute;
use App\Http\Requests\API\V1\Author\StoreAuthorRequest;
use App\Http\Requests\API\V1\Author\UpdateAuthorRequest;

class AuthorController extends Controller
{
    #[Get(
        path: '/api/v1/library/authors',
        summary: 'Get all authors',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Authors list',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Authors list',
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'J. K. Rowling',
                        'created_at' => '2023-10-10T10:00:00Z',
                    ],
                ]
            ]
        )
    )]
    #[UnauthorizedResponseAttribute]
    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            AuthorResource::collection(Author::paginate()),
            'Authors retrieved successfully',
        );
    }

    #[Post(
        path: '/api/v1/library/authors',
        summary: 'Create a new author',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
        parameters: [
            new Parameter(
                name: 'name',
                description: 'Author name',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CREATED,
        description: 'Author created',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Author created',
                'data' => [
                    'id' => 1,
                    'name' => 'J. K. Rowling',
                    'created_at' => '2023-10-10T10:00:00Z',
                ]
            ]
        )
    )]
    #[ValidationErrorResponseAttribute(
        [
            'name' => ['The name field is required.']
        ]
    )]
    #[UnauthorizedResponseAttribute]
    #[TooManyRequestsResponseAttribute]
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $author = Author::create($request->validated());

        return ApiResponseService::success(
            new AuthorResource($author),
            'Author created successfully',
            Response::HTTP_CREATED,
        );
    }

    #[Get(
        path: '/api/v1/library/authors/{author}',
        summary: 'Get an author',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
        parameters: [
            new Parameter(
                name: 'author',
                description: 'Author ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Author details',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Author details',
                'data' => [
                    'id' => 1,
                    'name' => 'J. K. Rowling',
                    'created_at' => '2023-10-10T10:00:00Z',
                ]
            ]
        )
    )]

    #[NotFoundResponseAttribute('Author Not found')]
    #[UnauthorizedResponseAttribute]
    #[TooManyRequestsResponseAttribute]
    public function show(Author $author): JsonResponse
    {
        return ApiResponseService::success(
            new AuthorResource($author),
            'Author retrieved successfully',
        );
    }


    #[Put(
        path: '/api/v1/library/authors/{author}',
        summary: 'Update an author',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
        parameters: [
            new Parameter(
                name: 'author',
                description: 'Author ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
            new Parameter(
                name: 'name',
                description: 'Author name',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Author updated',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Author updated',
                'data' => [
                    'id' => 1,
                    'name' => 'J. K. Rowling Updated!',
                    'created_at' => '2023-10-10T10:00:00Z',
                ]
            ]
        )
    )]
    #[NotFoundResponseAttribute('Author Not found')]
    #[ValidationErrorResponseAttribute(
        [
            'name' => ['The name field is required.'],
        ]
    )]
    #[UnauthorizedResponseAttribute]
    #[TooManyRequestsResponseAttribute]
    public function update(UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        $author->update($request->validated());

        return ApiResponseService::success(
            new AuthorResource($author),
            'Author updated successfully',
        );
    }

    #[Delete(
        path: '/api/v1/library/authors/{author}',
        summary: 'Delete an author',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Authors'],
        parameters: [
            new Parameter(
                name: 'author',
                description: 'Author ID',
                in: 'path',
                required: true,
                schema: new Schema(type: 'integer'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Author deleted',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'Author deleted',
                'data' => null,
            ]
        )
    )]
    #[NotFoundResponseAttribute('Author Not found')]
    #[UnauthorizedResponseAttribute]
    #[TooManyRequestsResponseAttribute]
    public function destroy(Author $author): JsonResponse
    {
        $author->delete();

        return ApiResponseService::success(
            null,
            'Author deleted successfully',
        );
    }
}
