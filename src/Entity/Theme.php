<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use App\Validator as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Regex("/^[\p{L} ':\-0-9]+$/u", 'Le nom du thème contient des caractères non autorisés.')]
    #[CustomAssert\SimilarTheme()]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'themes')]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $subjects;

    #[ORM\ManyToMany(targetEntity: Document::class, mappedBy: 'themes')]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $documents;

    #[ORM\ManyToMany(targetEntity: Level::class, inversedBy: 'themes')]
    private Collection $levels;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->levels = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->id . ' ' . $this->name;
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

    /**
     * @return Collection<int, Subject>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): static
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        $this->subjects->removeElement($subject);

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        $this->documents->removeElement($document);

        return $this;
    }

    /**
     * @return Collection<int, Level>
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }

    public function addLevel(Level $level): static
    {
        if (!$this->levels->contains($level)) {
            $this->levels->add($level);
            $level->addTheme($this);
        }

        return $this;
    }

    public function removeLevel(Level $level): static
    {
        if ($this->levels->removeElement($level)) {
            $level->removeTheme($this);
        }

        return $this;
    }
}
