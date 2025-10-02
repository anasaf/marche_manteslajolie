<?php
namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\BlameableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class CartItem
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 1;

    #[ORM\Column(type: 'string', length: 255)]
    private string $sku;

    #[ORM\Column(type: 'string', length: 255)]
    private string $titleSnapshot;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $unitPrice;

    #[ORM\Column(type: 'integer')]
    private int $unitPriceCents;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $vendorId = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $metadata = [];


    public function getId(): ?int { return $this->id; }
    public function getCart(): ?Cart { return $this->cart; }
    public function setCart(?Cart $c): self { $this->cart = $c; return $this; }

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $p): self { $this->product = $p; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $q): self { $this->quantity = $q; return $this; }

    public function getUnitPrice(): string { return $this->unitPrice; }
    public function setUnitPrice(string $p): self { $this->unitPrice = $p; return $this; }

    public function getSubtotal(): float {
        return (float)$this->unitPrice * $this->quantity;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getTitleSnapshot(): string
    {
        return $this->titleSnapshot;
    }

    /**
     * @param string $titleSnapshot
     */
    public function setTitleSnapshot(string $titleSnapshot): void
    {
        $this->titleSnapshot = $titleSnapshot;
    }

    /**
     * @return int
     */
    public function getUnitPriceCents(): int
    {
        return $this->unitPriceCents;
    }

    /**
     * @param int $unitPriceCents
     */
    public function setUnitPriceCents(int $unitPriceCents): void
    {
        $this->unitPriceCents = $unitPriceCents;
    }

    /**
     * @return int|null
     */
    public function getVendorId(): ?int
    {
        return $this->vendorId;
    }

    /**
     * @param int|null $vendorId
     */
    public function setVendorId(?int $vendorId): void
    {
        $this->vendorId = $vendorId;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

}
