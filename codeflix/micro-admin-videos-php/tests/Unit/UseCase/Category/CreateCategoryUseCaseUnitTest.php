<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\Category\DTO\Insert\InsertCategoryInputDto;
use Core\UseCase\Category\DTO\Insert\InsertCategoryOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    public function testCreateNewCategory()
    {
        $categoryName = "Category Name";
        $uuid = Uuid::uuid4()->toString();
        $description = "Category Description";
        $isValid = true;

        $mockCategory = Mockery::mock(Category::class, [$uuid, $categoryName, $description, $isValid]);
        $mockCategory->shouldReceive("id")->andReturn($uuid);
        $mockCategory->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));
        $mockCategory->shouldReceive("updatedAt")->andReturn(date("Y-m-d H:i:s"));

        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')->once()->andReturn($mockCategory);

        $inputDto = Mockery::mock(InsertCategoryInputDto::class, [
            $categoryName,
            $description,
            $isValid
        ]);

        $useCase = new CreateCategoryUseCase($mockRepository);
        $response = $useCase->execute($inputDto);

        $mockRepository->shouldHaveReceived('insert')->once();
        $this->assertInstanceOf(InsertCategoryOutputDto::class, $response);
        $this->assertEquals($uuid, $response->id);
        $this->assertEquals($categoryName, $response->name);
        $this->assertEquals($description, $response->description);
        $this->assertEquals($isValid, $response->is_active);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
