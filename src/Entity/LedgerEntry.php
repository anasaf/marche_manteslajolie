<?php

namespace App\Entity;

use App\Entity\Payment;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ledger_entry')]
class LedgerEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Payment::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Payment $payment = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type;

    #[ORM\Column(type: 'integer')]
    private int $amountCents;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $feeCents = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $vendorId = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $externalId = null;

    public function getId(): ?int { return $this->id; }
    public function setPayment(?Payment $p): self { $this->payment = $p; return $this; }
    public function getPayment(): ?Payment { return $this->payment; }

    public function getType(): string { return $this->type; }
    public function setType(string $t): self { $this->type = $t; return $this; }

    public function getAmountCents(): int { return $this->amountCents; }
    public function setAmountCents(int $c): self { $this->amountCents = $c; return $this; }

    public function getFeeCents(): ?int { return $this->feeCents; }
    public function setFeeCents(?int $f): self { $this->feeCents = $f; return $this; }

    public function getVendorId(): ?int { return $this->vendorId; }
    public function setVendorId(?int $v): self { $this->vendorId = $v; return $this; }

    public function getExternalId(): ?string { return $this->externalId; }
    public function setExternalId(?string $e): self { $this->externalId = $e; return $this; }
}
