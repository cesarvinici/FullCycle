<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\DTO\Insert\InsertCategoryInputDto;
use Core\UseCase\Category\DTO\ListCategories\ListCategoriesInputDto;
use Core\UseCase\Category\DTO\ListCategory\ListCategoryInputDto;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\Category\ListCategoryUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListCategoriesInputDto(
                filter: $request->get('filter', null),
                order: $request->get('order', "DESC"),
                page: (int) $request->get('page', 1),
                perPage: (int) $request->get('perPage', 15),
            )
        );


        return CategoryResource::collection(collect($response->items))
            ->additional([
                "meta" => [
                    'total' => $response->total,
                    'currentPage' => $response->currentPage,
                    'firstPage' =>  $response->firstPage,
                    'lastPage' => $response->lastPage,
                    'perPage' => $response->perPage,
                    'to' => $response->to,
                    'from' => $response->from,
                ]
            ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $response = $useCase->execute(new InsertCategoryInputDto(
            name: $request->input('name'),
            description: $request->input('description', ""),
            isActive: (bool) $request->input('isActive', true),
        ));

        return response()
            ->json(
                new CategoryResource(collect($response)),
                JsonResponse::HTTP_CREATED
            );
    }

    public function show(ListCategoryUseCase $useCase, string $id)
    {
        $response = $useCase->execute(
            input: new ListCategoryInputDto(
                id: $id
            )
        );

        return response()
            ->json(
                new CategoryResource(collect($response)),
                JsonResponse::HTTP_OK
            );
    }
}
