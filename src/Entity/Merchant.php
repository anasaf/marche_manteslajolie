<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\BlameableTrait;

#[ORM\Entity]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Merchant
{
    // upload du logo

    #[Vich\UploadableField(mapping: 'merchants', fileNameProperty: 'logoName')]
    private ?File $logoFile = null;
    #[ORM\Column(type:'string', length:255, nullable:true)]
    private ?string $logoName = null;

    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:'integer')]
    private ?int $id = null;

    #[ORM\Column(type:'string', length:150)]
    private ?string $name;

    #[ORM\Column(type:'text', nullable:true)]
    private ?string $description = null;

    #[ORM\Column(type:'string', length:255, nullable:true)]
    private ?string $adresse = null;

    #[ORM\Column(type:'string', length:50, nullable:true)]
    private ?string $telephone = null;

    #[ORM\Column(type:'string', length:100, nullable:true)]
    private ?string $email = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Category $category = null;

    #[ORM\Column(type:'json', nullable:true)]
    private ?array $positionPlan3D = null; // {x, y, z}

    #[ORM\OneToMany(mappedBy:'merchant', targetEntity: Product::class, cascade:['persist','remove'])]
    private Collection $products;

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Address $address;

    public function __construct(){ $this->products = new ArrayCollection(); }

    // === getters/setters ===
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $n): self { $this->name = $n; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): self { $this->description = $d; return $this; }
    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $a): self { $this->adresse = $a; return $this; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(?string $t): self { $this->telephone = $t; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $e): self { $this->email = $e; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $c): self { $this->category = $c; return $this; }

    public function getProducts(): Collection { return $this->products; }
    public function addProducts($products){

        $this->products->add($products);

        return $this;
    }
    // logo upload
    public function setLogoFile(?File $file = null): void
    {
        $this->logoFile = $file;
        if ($file) $this->updatedAt = new \DateTimeImmutable();
    }

    public function getLogoFile(): ?File { return $this->logoFile; }
    public function setLogoName(?string $name): void { $this->logoName = $name; }
    public function getLogoName(): ?string { return $this->logoName; }

    public function getPositionPlan3D(): ?array { return $this->positionPlan3D; }
    public function setPositionPlan3D(?array $pos): self { $this->positionPlan3D = $pos; return $this; }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return Merchant
     */
    public function setAddress(Address $address): Merchant
    {
        $this->address = $address;

        return $this;
    }


}
