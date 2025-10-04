<?php
namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\BlameableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Cart
{
    use TimestampableTrait;

    use BlameableTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?User $user = null;


    #[ORM\Column(type: 'string', length: 64, unique: true)]
    private string $token;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = 'open'; // open, checked_out, converted

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $items;



    public function __construct(string $token) {

        $this->items = new ArrayCollection();
        $this->token = $token;
        $this->lines = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();

    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $s): self { $this->status = $s; return $this; }

    /** @return Collection<int, CartItem> */
    public function getItems(): Collection { return $this->items; }

    public function addItem(CartItem $item): self {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setCart($this);
        }
        return $this;
    }

    public function removeItem(CartItem $item): self {
        if ($this->items->removeElement($item)) {
            if ($item->getCart() === $this) {
                $item->setCart(null);
            }
        }
        return $this;
    }

    public function getTotal(): float {
        return array_sum(array_map(fn($item) => $item->getSubtotal(), $this->items->toArray()));
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

}
