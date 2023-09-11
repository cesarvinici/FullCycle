<?php

namespace Tests\Unit\UseCase\Category;

use App\Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Entity\Category;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\Category\DTO\Delete\DeleteCategoryInputDto;
use Core\UseCase\Category\DTO\Delete\DeleteCategoryOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;


class DeleteCategoryUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {

        $categoryName = "Category Name";
        $id = Uuid::uuid4()->toString();
        $description = "Category Description";
        $isValid = true;

        $mockCategory = Mockery::mock(Category::class, [$id, $categoryName, $description, $isValid]);

        $inputDto = Mockery::mock(
            DeleteCategoryInputDto::class,
            [
                $id
            ]
        );

        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
            ->once()
            ->with($inputDto->id)
            ->andReturn(true);

        $useCase = new DeleteCategoryUseCase($mockRepository);
        $response = $useCase->execute($inputDto);

        $this->assertTrue($response->success);
        $this->assertInstanceOf(DeleteCategoryOutputDto::class, $response);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
