<?php

namespace Unit\UseCase\Category;

use App\Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\DTO\ListCategory\ListCategoryInputDto;
use Core\UseCase\Category\DTO\ListCategory\ListCategoryOutputDto;
use Core\UseCase\Category\ListCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ListCategoryUseCaseUnitTest extends TestCase
{
    public function testGetById()
    {
        $categoryName = "Category Name";
        $id = Uuid::uuid4()->toString();
        $description = "Category Description";
        $isValid = true;

        $mockCategory = Mockery::mock(Category::class, [$id, $categoryName, $description, $isValid]);
        $mockCategory->shouldReceive("id")->andReturn($id);
        $mockCategory->shouldReceive("createdAt")->andReturn(date("Y-m-d H:i:s"));

        $inputDto = Mockery::mock(ListCategoryInputDto::class, [$id]);

        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($id)
            ->andReturn($mockCategory);

        $useCase = new ListCategoryUseCase($mockRepository);

        $response = $useCase->execute($inputDto);

        $this->assertInstanceOf(ListCategoryOutputDto::class, $response);

        $this->assertEquals($id, $response->id);
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