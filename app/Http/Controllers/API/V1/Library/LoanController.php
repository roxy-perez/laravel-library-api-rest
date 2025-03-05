<?php

namespace App\Http\Controllers\API\V1\Library;

use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Loan\StoreLoanRequest;
use App\Http\Resources\API\V1\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Services\API\V1\ApiResponseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as CodeResponse;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return ApiResponseService::success(
            LoanResource::collection(Loan::with(['book', 'user'])->paginate()),
            'Loans retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request): JsonResponse
    {

        if(!Book::find($request->book_id)->stock) {
            return ApiResponseService::error(
                'Book out of stock.',
                CodeResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $loan = Loan::create(['book_id' => $request->book_id,
            'loaned_at' => now(),
            'due_date' => now()->addDays(7),
            'returned' => false,
            'returned_at' => null
        ]);

        $loan->book()->update(['stock' => $loan->book->stock - 1]);

        return ApiResponseService::success(
            new LoanResource($loan->load('book', 'book.author', 'book.genre')),
            'Loan created successfully.',
            CodeResponse::HTTP_CREATED
        );


    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan): JsonResponse
    {
        return ApiResponseService::success(
            new LoanResource($loan->load('book')),
            'Loan created successfully.',
            CodeResponse::HTTP_CREATED,
        );
    }

    // Return loan and update book stock.
    public function returnLoan(Loan $loan): JsonResponse
    {
        if($loan->returned) {
            return ApiResponseService::error(
                'Loan already returned.',
                CodeResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$loan->isOwner()) {
            return ApiResponseService::error(
                'Access denied.',
                CodeResponse::HTTP_FORBIDDEN
            );
        }

        $loan->update([
            'returned' => true,
            'returned_at' => now(),
        ]);

        $loan->book()->update([
            'stock' => $loan->book->stock + 1,
        ]);

        return ApiResponseService::success(
            $loan->load('book'),
            'Loan returned successfully.'
        );
    }

}
