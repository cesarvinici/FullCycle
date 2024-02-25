<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Genre;
use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Tests\TestCase;
use DateTime;

class VideoUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid4();
        $createdAt = new Datetime(date('Y-m-d H:i:s'));

        $entity = new Video(
            id: new Uuid($uuid),
            title: 'Video Title',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            published: true,
            rating: Rating::ER,
        );

        $this->assertEquals($uuid, $entity->id());
        $this->assertEquals('Video Title', $entity->title);
        $this->assertEquals('Video Description', $entity->description);
        $this->assertEquals(2021, $entity->yearLaunched);
        $this->assertEquals(90, $entity->duration);
        $this->assertTrue($entity->opened);
        $this->assertTrue($entity->published);
        $this->assertEquals(Rating::ER, $entity->rating);
    }

    public function testAttributesCreated()
    {

        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER
        );

        $this->assertNotEmpty($entity->id());
        $this->assertEquals('Genre Name', $entity->title);
        $this->assertEquals('Video Description', $entity->description);
        $this->assertEquals(2021, $entity->yearLaunched);
        $this->assertEquals(90, $entity->duration);
        $this->assertTrue($entity->opened);
        $this->assertFalse($entity->published);
        $this->assertEquals(Rating::ER, $entity->rating);
        $this->assertNotEmpty($entity->createdAt());
        $this->assertNotEmpty($entity->updatedAt());
    }

    public function testAddCategoryId()
    {

        $categoryId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER
        );

        $entity->addCategory($categoryId);

        $this->assertContains($categoryId, $entity->categoriesId);
        $this->assertCount(1, $entity->categoriesId);
    }

    public function testRemoveCategory()
    {
        $categoryId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER
        );

        $entity->addCategory($categoryId);

        $entity->removeCategory($categoryId);

        $this->assertEmpty($entity->categoriesId);
    }

    public function testAddGenre()
    {

        $genreId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER
        );

        $entity->addGenre($genreId);

        $this->assertContains($genreId, $entity->genresId);
        $this->assertCount(1, $entity->genresId);
    }

    public function testRemoveGenre()
    {
        $genreId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER
        );

        $entity->addGenre($genreId);

        $entity->removeGenre($genreId);

        $this->assertEmpty($entity->genresId);
    }

    public function testAddCastMember()
    {

        $castMemberId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER
        );

        $entity->addCastMember($castMemberId);

        $this->assertContains($castMemberId, $entity->castMembersId);
        $this->assertCount(1, $entity->castMembersId);
    }

    public function testRemoveCastMember()
    {
        $castMemberId = (string) RamseyUuid::uuid4();

        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER
        );

        $entity->addCastMember($castMemberId);
        $entity->removeCastMember($castMemberId);

        $this->assertEmpty($entity->castMembersId);
    }

    public function testBannerFile()
    {
        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER,
            bannerFile: new Image('path')
        );

        $this->assertNotNull($entity->bannerFile());
        $this->assertInstanceOf(Image::class, $entity->bannerFile());
        $this->assertEquals('path', $entity->bannerFile()->path());
    }

    public function testThumbFile()
    {
        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER,
            thumbFile: new Image('path')
        );

        $this->assertNotNull($entity->thumbFile());
        $this->assertInstanceOf(Image::class, $entity->thumbFile());
        $this->assertEquals('path', $entity->thumbFile()->path());
    }

    public function testImageToThumbHalf()
    {
        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER,
            thumbHalf: new Image('path-half')
        );

        $this->assertNotNull($entity->thumbHalf());
        $this->assertInstanceOf(Image::class, $entity->thumbHalf());
        $this->assertEquals('path-half', $entity->thumbHalf()->path());
    }

    public function testTrailerFile()
    {

        $trailer = new Media(
            path: 'file-path',
            status: MediaStatus::PENDING,
            encodedPath: 'encoded-path'
        );

        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER,
            trailerFile: $trailer
        );

        $this->assertNotNull($entity->trailerFile());
        $this->assertInstanceOf(Media::class, $entity->trailerFile());
        $this->assertEquals('file-path', $entity->trailerFile()->path);
        $this->assertEquals(MediaStatus::PENDING->value, $entity->trailerFile()->status->value);
        $this->assertEquals('encoded-path', $entity->trailerFile()->encodedPath);
    }

    public function testVideoFile()
    {

        $videoFile = new Media(
            path: 'file-path',
            status: MediaStatus::PROCESSING,
            encodedPath: 'encoded-path'
        );

        $entity = new Video(
            title: 'Genre Name',
            description: 'Video Description',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER,
            videoFile: $videoFile
        );

        $this->assertNotNull($entity->videoFile());
        $this->assertInstanceOf(Media::class, $entity->videoFile());
        $this->assertEquals('file-path', $entity->videoFile()->path);
        $this->assertEquals(MediaStatus::PROCESSING->value, $entity->videoFile()->status->value);
        $this->assertEquals('encoded-path', $entity->videoFile()->encodedPath);
    }

    public function testValidation()
    {

        $this->expectException(EntityValidationException::class);

        new Video(
            title: 'Ge',
            description: 'Vi',
            yearLaunched: 2021,
            duration: 90,
            opened: true,
            rating: Rating::ER
        );
    }
}
