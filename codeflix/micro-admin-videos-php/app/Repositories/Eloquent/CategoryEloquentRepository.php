<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Entity\Category;
use App\Models\Category as ModelCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{

    private ModelCategory $model;

    public function __construct (ModelCategory $category)
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

    public function findAll(string $filter = "", string $order = "desc"): array
    {
        return $this->model
            ->when($filter, function ($query, $filter) {
                return $query->where('name', 'like', "%{$filter}%");
            })
            ->orderBy('id', $order)
            ->get()
            ->toArray();
    }

    public function findById(string $id): Category
    {
        $category = $this->model->find($id);

        if (! $category) {
            throw new NotFoundException("Category not found");
        }


        return $this->toCategory($category);
    }

    public function getCategoriesIds(array $ids): array
    {
       return $this->model
           ->whereIn('id', $ids)
           ->get()
           ->pluck('id')
           ->toArray();
    }

    public function paginate(?string $filter = "", string $order = "DESC", int $page = 1, int $perPage = 15): PaginationInterface
    {
        $categoriesPaginated = $this->model
            ->when($filter, function ($query, $filter) {
                return $query->where('name', 'like', "%{$filter}%");
            })
            ->orderBy('id', $order)
            ->paginate($perPage, ['*'], 'page', $page);

        return new PaginationPresenter($categoriesPaginated);
    }

     public function update(Category $category): Category
    {
        $modelCategory = $this->model->find($category->id());

        if (! $modelCategory) {
            throw new NotFoundException("Category not found");
        }

        $modelCategory->update(
            [
                "name" => $category->name,
                "description" => $category->description,
                "is_active" => $category->isActive
            ]
        );

        return $this->toCategory($modelCategory->refresh());
    }

    public function delete(string $id): bool
    {
        $category = $this->model->find($id);

        if (! $category) {
            throw new NotFoundException("Category not found");
        }

        return $category->delete();
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
