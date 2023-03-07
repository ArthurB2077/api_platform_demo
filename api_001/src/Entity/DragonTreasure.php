<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\DragonTreasureRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use function Symfony\Component\String\u;


#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]
#[ApiResource(
    description: 'A rare and valuable treasure.',
    operations: [
        new Get(
            normalizationContext: [
                'groups' => ['treasure:read', 'treasure:item:get'],
            ],
        ),
        new GetCollection(),
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    shortName: 'Treasure',
    normalizationContext: [
        'groups' => ['treasure:read'],
    ],
    denormalizationContext: [
        'groups' => ['treasure:write'],
    ],
    paginationItemsPerPage: 10,
    formats: [
        'jsonld',
        'json',
        'html',
        'csv' => 'text/csv',
    ],
)]
#[ApiResource(
    uriTemplate: '/users/{user_id}/treasures.{_format}',
    shortName: 'Treasure',
    operations: [new GetCollection()],
    uriVariables: [
        'user_id' => new Link(
            fromProperty: 'dragonTreasure',
            fromClass: User::class,
        ),
    ],
    normalizationContext: [
        'groups' => ['treasure:read'],
    ],
)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, properties: [
    'owner.username' => 'partial',
])]
class DragonTreasure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[Groups(['treasure:read','treasure:write', 'user:read', 'user:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50, maxMessage: 'Describe your loot in 50 chars or less')]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['treasure:read'])]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * The estimated value of this treasure, in gold coins.
     */
    #[ApiFilter(RangeFilter::class)]
    #[Groups(['treasure:read','treasure:write', 'user:read', 'user:write'])]
    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column]
    private ?int $value = 0;

    #[Groups(['treasure:read', 'treasure:write'])]
    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\LessThanOrEqual(10)]
    #[ORM\Column]
    private ?int $coolFactor = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ApiFilter(BooleanFilter::class)]
    #[ORM\Column]
    private ?bool $isPublished = false;

    #[Assert\Valid]
    #[Groups(['treasure:read', 'treasure:write'])]
    #[ORM\ManyToOne(inversedBy: 'dragonTreasure')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct(string $name = null)
    {
        $this->name = $name;
        $this->createAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    // public function setName(string $name): self
    // {
    //     $this->name = $name;

    //     return $this;
    // }

    public function getDescription(): ?string
    {
        return $this->description;
    }


    #[Groups(['treasure:read'])]
    public function getShortDescription(): ?string
    {
        return u($this->getDescription())->truncate(40, '...');
    }

    // public function setDescription(string $description): self
    // {
    //     $this->description = $description;

    //     return $this;
    // }
    #[SerializedName('description')]
    #[Groups(['treasure:write', 'user:write'])]
    public function setTextDescription(string $description): self
    {
        $this->description = nl2br($description);
        return $this;
    }

    #[Groups(['treasure:read'])]
    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
    
    #[Groups(['treasure:read'])]
    public function getCoolFactor(): ?int
    {
        return $this->coolFactor;
    }

    #[Groups(['treasure:read'])]
    public function setCoolFactor(int $coolFactor): self
    {
        $this->coolFactor = $coolFactor;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    #[Groups(['treasure:read'])]
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->createAt)->diffForHumans();
    }

    // public function setCreateAt(\DateTimeImmutable $createAt): self
    // {
    //     $this->createAt = $createAt;

    //     return $this;
    // }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
