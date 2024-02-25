<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MagicMethodsTrait;
use Core\Domain\Enum\Rating;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use Datetime;

class Video
{
    use MagicMethodsTrait;

    protected array $categoriesId = [];
    protected array $genresId = [];
    protected array $castMembersId = [];


    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Image $bannerFile = null,
        protected ?Image $thumbFile = null,
        protected ?Image $thumbHalf = null,
        protected ?Media $trailerFile = null,
        protected ?Media $videoFile = null,
        protected bool $published = false,
        protected Uuid|string $id = "",
        protected Datetime|null $createdAt = null,
        protected Datetime|null $updatedAt = null,
    ) {
        $this->id = $id === "" ? Uuid::random() : $id;
        $this->createdAt = $createdAt ?? new Datetime();
        $this->updatedAt = $updatedAt ?? new Datetime();

        $this->validation();
    }

    public function addCategory(string $categoryId): void
    {
        if (! in_array($categoryId, $this->categoriesId))
        {
            $this->categoriesId[] = $categoryId;
        }
    }

    protected function validation() {
        DomainValidation::strMinLength($this->title, 3, "Title must have at least 3 character");
        DomainValidation::strMinLength($this->description, 2, "Description must have at least 3 character");
        DomainValidation::strMaxLength($this->title, 255, "Title must have at most 255 character");
        DomainValidation::strMaxLength($this->description, 500, "Title must have at most 500 character");
    }

    public function removeCategory(string $categoryId): void
    {
        $this->categoriesId = array_filter($this->categoriesId, fn($id) => $id !== $categoryId);
    }

    public function addGenre(string $genreId): void
    {
        if (! in_array($genreId, $this->genresId))
        {
            $this->genresId[] = $genreId;
        }
    }

    public function removeGenre(string $genreId): void
    {
        $this->genresId = array_filter($this->genresId, fn($id) => $id !== $genreId);
    }

    public function addCastMember(string $castMemberId): void
    {
        if (! in_array($castMemberId, $this->castMembersId))
        {
            $this->castMembersId[] = $castMemberId;
        }
    }

    public function removeCastMember(string $castMemberId): void
    {
        $this->castMembersId = array_filter($this->castMembersId, fn($id) => $id !== $castMemberId);
    }

    public function bannerFile(): ?Image
    {
        return $this->bannerFile;
    }

    public function thumbFile(): ?Image
    {
        return $this->thumbFile;
    }

    public function thumbHalf(): ?Image
    {
        return $this->thumbHalf;
    }

    public function trailerFile(): ?Media
    {
        return $this->trailerFile;
    }

    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }
}
