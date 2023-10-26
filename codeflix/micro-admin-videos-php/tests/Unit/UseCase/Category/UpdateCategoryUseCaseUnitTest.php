<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\DTO\Update\UpdateCategoryInputDto;
use Core\UseCase\Category\DTO\Update\UpdateCategoryOutputDto;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

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

        $mockCategoryEntity = Mockery::mock(
            Category::class,
            [$id, $editedCategoryName, $editedDescription, $editedIsValid]
        );

        $mockCategoryEntity->shouldReceive('update');
        $mockCategoryEntity->shouldReceive("id")->andReturn($id);
        $mockCategoryEntity->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));
        $mockCategoryEntity->shouldReceive("updatedAt")->andReturn(date("Y-m-d H:i:s"));

        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($id)
            ->andReturn($mockCategoryEntity);


        $mockRepository->shouldReceive('update')->once()->andReturn($mockCategoryEntity);

        $inputDto = Mockery::mock(
            UpdateCategoryInputDto::class,
            [
                $id,
                $editedCategoryName,
                $editedDescription,
                $editedIsValid
            ]
        );


        $useCase = new UpdateCategoryUseCase($mockRepository);
        $response = $useCase->execute($inputDto);

        $this->assertInstanceOf(UpdateCategoryOutputDto::class, $response);
        $this->assertEquals($id, $response->id);
        $this->assertEquals($editedCategoryName, $response->name);
        $this->assertEquals($editedDescription, $response->description);
        $this->assertEquals($editedIsValid, $response->is_active);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }


}
