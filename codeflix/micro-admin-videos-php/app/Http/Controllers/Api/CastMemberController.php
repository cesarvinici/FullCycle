<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCastMemberRequest;
use App\Http\Requests\UpdateCastMemberRequest;
use App\Http\Resources\CastMemberResource;
use App\Models\CastMember;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Core\UseCase\CastMember\DeleteCastMemberUseCase;
use Core\UseCase\CastMember\DTO\Create\CreateCastMemberInputDto;
use Core\UseCase\CastMember\DTO\Delete\DeleteCastMemberInputDto;
use Core\UseCase\CastMember\DTO\ListCastMember\ListCastMemberInputDto;
use Core\UseCase\CastMember\DTO\ListCastMembers\ListCastMembersInputDto;
use Core\UseCase\CastMember\DTO\Update\UpdateCastMemberInputDto;
use Core\UseCase\CastMember\ListCastMembersUseCase;
use Core\UseCase\CastMember\ListCastMemberUseCase;
use Core\UseCase\CastMember\UpdateCastMemberUseCase;
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

    public function store(StoreCastMemberRequest $request, CreateCastMemberUseCase $useCase): JsonResponse
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

    public function show(CastMember $castMember, ListCastMemberUseCase $useCase)
    {
        $response = $useCase->execute(new ListCastMemberInputDto(
            id: $castMember->id
        ));

        return response()->json(
            new CastMemberResource($response)
        );
    }

    public function update(UpdateCastMemberRequest $request, CastMember $castMember, UpdateCastMemberUseCase $useCase)
    {
        $response = $useCase->execute(new UpdateCastMemberInputDto(
            id: $castMember->id,
            name: $request->input('name'),
            type: $request->input('type'),
        ));

        return response()->json(
            new CastMemberResource($response)
        );
    }

    public function destroy(CastMember $castMember, DeleteCastMemberUseCase $useCase)
    {
        $useCase->execute(new DeleteCastMemberInputDto(
            id: $castMember->id
        ));

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
