<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payment')]
class Payment
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $provider;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $providerPaymentId = null;

    #[ORM\Column(type: 'integer')]
    private int $amountCents;

    #[ORM\Column(type: 'string', length: 8)]
    private string $currency = 'EUR';

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = 'pending';

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $rawResponse = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function setOrder(?Order $o): self { $this->order = $o; return $this; }
    public function getOrder(): ?Order { return $this->order; }

    public function getProvider(): string { return $this->provider; }
    public function setProvider(string $p): self { $this->provider = $p; return $this; }

    public function getProviderPaymentId(): ?string { return $this->providerPaymentId; }
    public function setProviderPaymentId(?string $id): self { $this->providerPaymentId = $id; return $this; }

    public function getAmountCents(): int { return $this->amountCents; }
    public function setAmountCents(int $c): self { $this->amountCents = $c; return $this; }

    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $cur): self { $this->currency = $cur; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $s): self { $this->status = $s; return $this; }

    public function getRawResponse(): ?array { return $this->rawResponse; }
    public function setRawResponse(?array $r): self { $this->rawResponse = $r; return $this; }
}
