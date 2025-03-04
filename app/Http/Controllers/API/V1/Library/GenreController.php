<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Genre\StoreGenreRequest;
use App\Http\Requests\API\V1\Genre\UpdateGenreRequest;
use App\Http\Resources\API\V1\GenreResource;
use App\Models\Genre;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as CodeResponse;

class GenreController extends Controller
{

    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            GenreResource::collection(Genre::paginate()),
            'Genres retrieved successfully.'
        );
    }


    public function store(StoreGenreRequest $request): JsonResponse
    {
        $genre = Genre::create($request->validated());

        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre created successfully.',
            CodeResponse::HTTP_CREATED,
        );

    }


    public function show(Genre $genre): JsonResponse
    {
        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre retrieved successfully.'
        );
    }


    public function update(UpdateGenreRequest $request, Genre $genre): JsonResponse
    {
        $genre->update($request->validated());

        return ApiResponseService::success(
            new GenreResource($genre),
            'Genre updated successfully.'
        );
    }

    public function destroy(Genre $genre): JsonResponse
    {
        $genre->delete();

        return ApiResponseService::success(
            null,
            'Genre deleted successfully.'
        );
    }
}
