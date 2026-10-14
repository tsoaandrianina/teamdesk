<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['project:read']],
    denormalizationContext: ['groups' => ['project:write']],
)]

class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['project:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['project:read', 'project:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['project:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'ownedProjects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['project:read', 'project:write'])]
    #[Assert\NotNull]
    private ?User $owner = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_member')]
    #[Groups(['project:read', 'project:write'])]
    private Collection $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        $this->members->removeElement($member);

        return $this;
    }
}
