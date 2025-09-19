<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\BlameableTrait;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Commercant
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:'integer')]
    private ?int $id = null;

    #[ORM\Column(type:'string', length:150)]
    private string $name;

    #[ORM\Column(type:'text', nullable:true)]
    private ?string $description = null;

    // relation vers category (facultative)
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Category $category = null;

    #[ORM\Column(type:'json', nullable:true)]
    private ?array $positionPlan3D = null; // {x:.., y:.., z:..} JSON

    #[ORM\OneToMany(mappedBy: 'commercant', targetEntity: Product::class, cascade:['persist','remove'])]
    private Collection $products;

    public function __construct(){ $this->products = new ArrayCollection(); }

    // getters / setters ...
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $n): self { $this->name = $n; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $c): self { $this->category = $c; return $this; }
    public function getProducts(): Collection { return $this->products; }
}
