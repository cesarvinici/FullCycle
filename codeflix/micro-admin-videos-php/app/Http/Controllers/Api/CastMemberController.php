<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CastMemberResource;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Core\UseCase\CastMember\DTO\Create\CreateCastMemberInputDto;
use Core\UseCase\CastMember\DTO\ListCastMembers\ListCastMembersInputDto;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Core\UseCase\Category\DTO\Insert\InsertCategoryInputDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CastMemberController extends Controller
{

    public function index(Request $request, ListCastMembersUseCase $useCase): AnonymousResourceCollection
    {
        $response = $useCase->execute(
            inputDto: new ListCastMembersInputDto(
                filter: $request->get('filter', null),
                order: $request->get('order', "DESC"),
                page: (int) $request->get('page', 1),
                perPage: (int) $request->get('perPage', 15),
            )
        );

        return CastMemberResource::collection(collect($response->items))
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

    public function store(Request $request, CreateCastMemberUseCase $useCase): JsonResponse
    {
        $response = $useCase->execute(new CreateCastMemberInputDto(
            name: $request->input('name'),
            type: $request->input('type'),
        ));

        return response()->json(
            new CastMemberResource($response),
            JsonResponse::HTTP_CREATED
        );
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
