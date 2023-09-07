<?php

namespace Unit\UseCase\Category;

use App\Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\DTO\ListCategory\ListCategoryInputDto;
use Core\UseCase\Category\DTO\ListCategory\ListCategoryOutputDto;
use Core\UseCase\Category\DTO\Update\UpdateCategoryInputDto;
use Core\UseCase\Category\DTO\Update\UpdateCategoryOutputDto;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\Category\UpdateCategoryUseCase;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Mockery;

class UpdateCategoryUseCaseUnitTest extends TestCase
{

    public function testUpdateCategory()
    {
        $categoryName = "Category Name";
        $id = Uuid::uuid4()->toString();
        $description = "Category Description";
        $isValid = true;

        $editedCategoryName = "Edited Category Name";
        $editedDescription = "Edited Category Description";
        $editedIsValid = false;

        $inputDto = Mockery::mock(
            UpdateCategoryInputDto::class,
            [
                $id,
                $editedCategoryName,
                $editedDescription,
                $editedIsValid
            ]
        );

        $mockCategory = Mockery::mock(Category::class, [$id, $editedCategoryName, $editedDescription, $editedIsValid]);
        $mockCategory->shouldReceive("id")->andReturn($id);

        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('update')
            ->once()
            ->with($inputDto)
            ->andReturn($mockCategory);

        $useCase = new UpdateCategoryUseCase($mockRepository);
        $response = $useCase->execute($inputDto);

        $this->assertInstanceOf(UpdateCategoryOutputDto::class, $response);
        $this->assertEquals($id, $response->id);
        $this->assertEquals($editedCategoryName, $response->name);
        $this->assertEquals($editedDescription, $response->description);
        $this->assertEquals($editedIsValid, $response->is_active);
    }


}