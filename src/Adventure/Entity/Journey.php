<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use App\Adventure\Repository\JourneyRepository;
use App\Security\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;
use Stringable;
use Symfony\Component\Uid\Ulid;

#[Entity(repositoryClass: JourneyRepository::class)]
class Journey implements Stringable
{
    #[Id]
    #[Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[OneToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[ManyToOne(targetEntity: Level::class)]
    private ?Level $currentLevel;

    /**
     * @var Collection<int, Checkpoint>
     */
    #[OneToMany(mappedBy: 'journey', targetEntity: Checkpoint::class)]
    #[OrderBy(['passedAt' => 'DESC'])]
    private Collection $checkpoints;

    #[Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->checkpoints = new ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getCurrentLevel(): ?Level
    {
        return $this->currentLevel;
    }

    public function setCurrentLevel(?Level $currentLevel): void
    {
        $this->currentLevel = $currentLevel;
    }

    /**
     * @return Collection<int, Checkpoint>
     */
    public function getCheckpoints(): Collection
    {
        return $this->checkpoints;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function __toString(): string
    {
        return $this->user->__toString();
    }
}