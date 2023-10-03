<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Core\UseCase\Category\DTO\ListCategories\ListCategoriesInputDto;
use Core\UseCase\Category\ListCategoriesUseCase;
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
}
