<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\CastMember as CastMemberEntity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class CastMemberEloquentRepository implements CastMemberRepositoryInterface
{
    private CastMemberModel $model;

    public function __construct(CastMemberModel $model)
    {
        $this->model = $model;
    }


    public function insert(CastMemberEntity $entity): CastMemberEntity
    {
        $model = $this->model->create([
            "id" => $entity->id(),
            "name" => $entity->name,
            "type" => $entity->type->value,
            "created_at" => $entity->createdAt(),
        ]);

        return $this->parseToEntity($model);
    }

    public function findAll(string $filter = "", string $order = "DESC"): array
    {
        $query = $this->model->query();

        if ($filter) {
            $query->where("name", "LIKE", "%{$filter}%");
        }

        $query->orderBy("created_at", $order);

        return $query->get()->map(function ($model) {
            return $this->parseToEntity($model);
        })->toArray();
    }

    public function findById(string $id): CastMemberEntity
    {
        $model = $this->model->find($id);

        if (! $model) {
            throw new NotFoundException("Cast Member not found");
        }

        return $this->parseToEntity($model);
    }

    public function paginate(?string $filter = "", string $order = "DESC", int $page = 1, int $perPage = 15): PaginationInterface
    {
        $modelPaginated =  $this->model
            ->when($filter, fn ($query, $filter) =>
            $query->where('name', 'like', "%{$filter}%")
            )
            ->orderBy('created_at', $order)
            ->paginate($perPage, ['*'], 'page', $page);

        return new PaginationPresenter($modelPaginated);
    }

    public function update(CastMemberEntity $castMember): CastMemberEntity
    {
        $model = $this->model->find($castMember->id());

        if (! $model) {
            throw new NotFoundException("Cast Member not found");
        }

        $model->update([
            "name" => $castMember->name,
            "type" => $castMember->type->value,
        ]);

        return $this->parseToEntity($model);
    }

    public function delete(string $id): bool
    {
        $model = $this->model->find($id);

        if (! $model) {
            throw new NotFoundException("Cast Member not found");
        }

        return $model->delete();
    }

    private function parseToEntity(CastMemberModel $model): CastMemberEntity
    {
        return new CastMemberEntity(
            id: $model->id,
            name: $model->name,
            type: CastMemberType::from($model->type),
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
        );
    }


}
