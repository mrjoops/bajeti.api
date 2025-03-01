<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OperationRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'operations')]
class Operation implements IdInterface, Stringable
{
    use IdTrait;

    #[ORM\ManyToOne]
    public ?Category $category = null;

    #[ORM\ManyToOne]
    public ?Party $party = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: "operations_tags")]
    public Collection $tags;

    public function __construct(
        #[ORM\ManyToOne(inversedBy: 'operations')]
        #[ORM\JoinColumn(nullable: false)]
        public Account $account,
        #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
        public readonly string $amount,
        #[ORM\Column(name: '`date`', type: Types::DATE_MUTABLE)]
        public readonly DateTimeInterface $date,
        #[ORM\Column(length: 255)]
        public readonly string $name,
    ) {
        $this->tags = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }
}
