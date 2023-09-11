<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Entity\Category;
use App\Models\Category as Model;
use Core\Domain\Repository\PaginationInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{

    private Model $model;

    public function __construct (Model $category)
    {
        $this->model = $category;
    }

    public function insert(Category $entity): Category
    {
        $category = $this->model->create(
            [
                "id" => $entity->id(),
                "name" => $entity->name,
                "description" => $entity->description,
                "is_active" => $entity->isActive
            ]
        );

        return $this->toCategory($category);
    }

    public function findAll(string $filter = "", string $order = "DESC"): array
    {
        return [];
    }

    public function findById(string $id): Category
    {
        return new Category();
    }

    public function paginate(string $filter = "", string $order = "DESC", int $page = 1, int $perPage = 15): PaginationInterface
    {
        return new PaginationPresenter(
            items: [],
            total: 0,
            currentPage: $page,
            firstPage: 1,
            lastPage: 1,
            perPage: $perPage,
            to: 0,
            from: 0
        );
    }

     public function update(Category $category): Category
    {
        return new Category();
    }

    public function delete(string $id): bool
    {
        return false;
    }

    private function toCategory(object $object): Category
    {
        return new Category(
            id: $object->id,
            name: $object->name,
            description: $object->description,
            isActive: $object->is_active,
            createdAt: $object->created_at,
            updatedAt: $object->updated_at
        );
    }
}
