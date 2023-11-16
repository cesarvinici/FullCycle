<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as GenreModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Genre as GenreEntity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Illuminate\Support\Arr;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    private GenreModel $model;

    public function __construct(GenreModel $genre)
    {
        $this->model = $genre;
    }

    public function insert(GenreEntity $entity): GenreEntity
    {
        $model = $this->model->create([
            "id" => $entity->id(),
            "name" => $entity->name,
            "is_active" => $entity->isActive,
            "created_at" => $entity->createdAt(),
        ]);

        if(count($entity->categoriesId)) {
            $model->categories()->sync($entity->categoriesId);
        }

        return $this->toGenre($model);
    }

    public function findAll(string $filter = "", string $order = "DESC"): array
    {
        $query = $this->model->query();

        if($filter) {
            $query->where("name", "LIKE", "%{$filter}%");
        }

        $query->orderBy("created_at", $order);

        return $query->get()->map(function ($model) {
            return $this->toGenre($model);
        })->toArray();
    }

    public function findById(string $id): GenreEntity
    {
        $model = $this->model->find($id);

        if(! $model) {
            throw new NotFoundException("Genre not found");
        }

        return $this->toGenre($model);
    }

    public function paginate(
        ?string $filter = "",
        string $order = "DESC",
        int $page = 1,
        int $perPage = 15
    ): PaginationInterface
    {
        $genrePaginated = $this->model
            ->when($filter, fn ($query, $filter) =>
                $query->where('name', 'like', "%{$filter}%")
            )
            ->orderBy('created_at', $order)
            ->paginate($perPage, ['*'], 'page', $page);

        return new PaginationPresenter($genrePaginated);
    }

    public function update(GenreEntity $genre): GenreEntity
    {
        $model = $this->model->find($genre->id());

        if(! $model) {
            throw new NotFoundException("Genre not found");
        }

        $model->update([
            "name" => $genre->name,
            "is_active" => $genre->isActive,
        ]);

        if(count($genre->categoriesId)) {
            $model->categories()->sync($genre->categoriesId);
        }

        return $this->toGenre($model);
    }

    public function delete(string $id): bool
    {
        $model = $this->model->find($id);

        if(! $model) {
            throw new NotFoundException("Genre not found");
        }

        return $model->delete();
    }

    private function toGenre(GenreModel $model): GenreEntity
    {
        return new GenreEntity(
            id: $model->id,
            name: $model->name,
            isActive: $model->is_active,
            categoriesId: $model->categories()->pluck("id")->toArray(),
            createdAt: $model->created_at,
        );
    }
}
