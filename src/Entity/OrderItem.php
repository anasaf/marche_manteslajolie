<?php
namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\BlameableTrait;
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
    #[ORM\Column(type: 'string', length: 255)]
    private string $sku;
    #[ORM\Column(type: 'string', length: 255)]
    private string $titleSnapshot;
    #[ORM\ManyToOne(targetEntity: Merchant::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Merchant $merchant = null;

    #[ORM\Column(type: 'string', length: 200)]
    private string $productName;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $unitPrice;
    #[ORM\Column(type: 'integer')]
    private int $unitPriceCents;
    #[ORM\Column(type: 'integer')]
    private int $quantity;
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $vendorId = null;

    public function getId(): ?int { return $this->id; }
    public function getOrder(): ?Order { return $this->order; }
    public function setOrder(?Order $o): self { $this->order = $o; return $this; }

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $p): self { $this->product = $p; return $this; }

    public function getMerchant(): ?Merchant { return $this->Merchant; }
    public function setMerchant(?Merchant $c): self { $this->Merchant = $c; return $this; }

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
