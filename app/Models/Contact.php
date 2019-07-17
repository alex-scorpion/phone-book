<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="contacts")
 */
class Contact
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
    private $name;
    /**
     * @ORM\Column(type="string",  nullable=true)
     */
    private $description;
    /**
     * @var \DateTimeImmutable $createdAt
     * @ORM\Column(type="datetime_immutable", name="created_at", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;
    /**
     * @var \DateTimeImmutable $updatedAt
     * @ORM\Column(type="datetime_immutable", name="updated_at", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $updatedAt;
    /**
     * @var ArrayCollection|Phone[]
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="contact", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $phones;

    public function __construct(string $name, string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->phones = new ArrayCollection();
    }

    public function update(string $name, string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->updatedAt = new \DateTimeImmutable();
        $this->phones = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt ?? new \DateTimeImmutable();
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt ?? new \DateTimeImmutable();
    }

    public function addPhone(Phone $phone): void
    {
        $this->phones->add($phone);
    }

    public function removePhone(int $id): void
    {
        foreach ($this->phones as $phone) {
            if ($phone->getId() === $id) {
                $this->phones->removeElement($phone);
            }
        }
    }

    /**
    * @return Phone[]
    */
    public function getPhones(): array
    {
        return $this->phones->toArray();
    }

    public function toArray(bool $relation = false): array
    {
        $result = [
            'id'          => (int)$this->id ?? 0,
            'name'        => $this->name ?? '',
            'description' => $this->description ?? '',
            'created_at'  => $this->createdAt->format('d.m.Y H:i:s'),
            'updated_at'  => $this->updatedAt->format('d.m.Y H:i:s'),
            'phones'      => []
        ];

        if ($relation) {
            $result['phones'] = [];
            foreach ($this->phones as $phone) {
                /** @var Phone $phone */
                $result['phones'][] = $phone->toArray();
            }
        }

        return $result;
    }
}
