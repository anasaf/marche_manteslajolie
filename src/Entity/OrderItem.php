<?php
namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\BlameableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class OrderItem
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: Commercant::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Commercant $commercant = null;

    #[ORM\Column(type: 'string', length: 200)]
    private string $productName;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $unitPrice;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function getId(): ?int { return $this->id; }
    public function getOrder(): ?Order { return $this->order; }
    public function setOrder(?Order $o): self { $this->order = $o; return $this; }

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $p): self { $this->product = $p; return $this; }

    public function getCommercant(): ?Commercant { return $this->commercant; }
    public function setCommercant(?Commercant $c): self { $this->commercant = $c; return $this; }

    public function getProductName(): string { return $this->productName; }
    public function setProductName(string $n): self { $this->productName = $n; return $this; }

    public function getUnitPrice(): string { return $this->unitPrice; }
    public function setUnitPrice(string $p): self { $this->unitPrice = $p; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $q): self { $this->quantity = $q; return $this; }

    public function getSubtotal(): float {
        return (float)$this->unitPrice * $this->quantity;
    }
}
