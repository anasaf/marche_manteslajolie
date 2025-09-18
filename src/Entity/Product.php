<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\BlameableTrait;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Product
{
    use TimestampableTrait;
    use BlameableTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:'integer')]
    private ?int $id = null;

    #[ORM\Column(type:'string', length:200)]
    private string $name;

    #[ORM\Column(type:'text', nullable:true)]
    private ?string $description = null;

    #[ORM\Column(type:'decimal', precision:10, scale:2)]
    private string $price;

    #[ORM\Column(type:'integer')]
    private int $stock = 0;

    #[ORM\Column(type:'string', length:255, nullable:true)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Commercant::class, inversedBy:'products')]
    #[ORM\JoinColumn(nullable:false)]
    private ?Commercant $commercant = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable:true)]
    private ?Category $category = null;

    // getters/setters...
}
