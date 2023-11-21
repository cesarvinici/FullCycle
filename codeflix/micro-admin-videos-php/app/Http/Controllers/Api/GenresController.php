<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\UseCase\Genre\DTO\Create\CreateGenreInputDto;
use Core\UseCase\Genre\DTO\DeleteGenreInputDto;
use Core\UseCase\Genre\DTO\ListGenre\ListGenreInputDto;
use Core\UseCase\Genre\DTO\ListGenres\ListGenresInputDto;
use Core\UseCase\Genre\DTO\Update\UpdateGenreInputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\UseCase\Genre\ListGenreUseCase;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenresController extends Controller
{

    public function index(Request $request, ListGenresUseCase $useCase)
    {
        $response = $useCase->execute(
            new ListGenresInputDto(
                filter: $request->get('filter', null),
                order: $request->get('order', "DESC"),
                page: (int) $request->get('page', 1),
                perPage: (int) $request->get('perPage', 15),
            )
        );

        return GenreResource::collection(collect($response->items))
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

    public function store(StoreGenreRequest $request, CreateGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            new CreateGenreInputDto(
                name: $request->input('name'),
                is_active: (bool) $request->input('isActive', true),
                categoriesId: $request->input('categories_id')
        ));

        return response()
            ->json(
                new GenreResource($response),
                JsonResponse::HTTP_CREATED);
    }

    public function show(string $id, ListGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            new ListGenreInputDto(
                id: $id
            )
        );

        return response()
            ->json(
                new GenreResource($response),
                JsonResponse::HTTP_OK);
    }

    public function update(UpdateGenreRequest $request, $id, UpdateGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            new UpdateGenreInputDto(
                id: $id,
                name: $request->input('name'),
                is_active: (bool) $request->input('isActive', true),
                categoriesId: $request->input('categories_id')
            )
        );

        return response()
            ->json(
                new GenreResource($response),
                JsonResponse::HTTP_OK
            );
    }

    public function destroy(string $id, DeleteGenreUseCase $useCase)
    {
        $useCase->execute(
            new DeleteGenreInputDto(
                id: $id
            )
        );

        return response()
            ->json(
                null,
                JsonResponse::HTTP_NO_CONTENT
            );
    }
}
