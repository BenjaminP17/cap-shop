<?php

namespace App\Entity;

use App\Enum\BadgeType;
use App\Enum\Category;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private string $name = '';

    #[ORM\Column(length: 140, unique: true)]
    private string $slug = '';

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $brand = null;

    #[ORM\Column(type: 'decimal', precision: 7, scale: 2)]
    private string $price = '0.00';

    #[ORM\Column(enumType: Category::class)]
    private Category $category = Category::SNAPBACK;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(enumType: BadgeType::class, nullable: true)]
    private ?BadgeType $badge = null;

    #[ORM\Column]
    private int $stock = 0;

    #[ORM\Column]
    private int $soldCount = 0;

    #[ORM\Column]
    private bool $isBestseller = false;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $droppedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): static
    {
        $this->name = $name;
        if ($this->slug === '') {
            $this->slug = (new AsciiSlugger('fr'))->slug(strtolower($name))->toString();
        }
        return $this;
    }

    public function getSlug(): string { return $this->slug; }
    public function setSlug(string $slug): static { $this->slug = $slug; return $this; }

    public function getBrand(): ?string { return $this->brand; }
    public function setBrand(?string $brand): static { $this->brand = $brand; return $this; }

    public function getPrice(): string { return $this->price; }
    public function setPriceFloat(float $price): static { $this->price = number_format($price, 2, '.', ''); return $this; }

    public function getCategory(): Category { return $this->category; }
    public function setCategory(Category $category): static { $this->category = $category; return $this; }

    public function getImageUrl(): ?string { return $this->imageUrl; }
    public function setImageUrl(?string $imageUrl): static { $this->imageUrl = $imageUrl; return $this; }

    public function getBadge(): ?BadgeType { return $this->badge; }
    public function setBadge(?BadgeType $badge): static { $this->badge = $badge; return $this; }

    public function getStock(): int { return $this->stock; }
    public function setStock(int $stock): static { $this->stock = $stock; return $this; }

    public function getSoldCount(): int { return $this->soldCount; }
    public function setSoldCount(int $count): static { $this->soldCount = $count; return $this; }

    public function isBestseller(): bool { return $this->isBestseller; }
    public function setIsBestseller(bool $val): static { $this->isBestseller = $val; return $this; }

    public function isActive(): bool { return $this->isActive; }
    public function setIsActive(bool $val): static { $this->isActive = $val; return $this; }

    public function getDroppedAt(): ?\DateTimeImmutable { return $this->droppedAt; }
    public function setDroppedAt(?\DateTimeImmutable $date): static { $this->droppedAt = $date; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): static { $this->description = $d; return $this; }

    public function getFormattedPrice(): string
    {
        return number_format((float) $this->price, 0, ',', ' ') . '€';
    }
}
