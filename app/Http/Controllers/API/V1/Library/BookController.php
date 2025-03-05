<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Book\StoreBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookRequest;
use App\Http\Requests\API\V1\Book\UpdateBookStockRequest;
use App\Http\Resources\API\V1\BookResource;
use App\Models\Book;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as CodeResponse;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            BookResource::collection(Book::with('author', 'genre')->paginate()),
            'Books retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());

        return ApiResponseService::success(
            new BookResource($book),
            'Book created successfully.',
            CodeResponse::HTTP_CREATED
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): JsonResponse
    {
        return ApiResponseService::success(
            new BookResource($book->load('author', 'genre')),
            'Book retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->validated());

        return ApiResponseService::success(
        new BookResource($book),
        'Book updated successfully.'
        );
    }

    public function updateStock(UpdateBookStockRequest $request, Book $book): JsonResponse
    {
        $book->update([
            'stock' => data_get($request->validated(), 'stock', 0),
        ]);

        return ApiResponseService::success(
            new BookResource($book),
            'Book stock updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return ApiResponseService::success(
            null,
            'Book deleted successfully.'
        );
    }
}
