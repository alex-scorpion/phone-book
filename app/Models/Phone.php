<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phones")
 */
class Phone
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $phone;
    /**
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="phones")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $contact;

    public function __construct(Contact $contact, string $phone)
    {
        $this->contact = $contact;
        $this->phone = $phone;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function toArray(): array
    {
        $result = [
            'id'    => (int)$this->id ?? 0,
            'phone' => $this->phone ?? ''
        ];

        return $result;
    }
}
