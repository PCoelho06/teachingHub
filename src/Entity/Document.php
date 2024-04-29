<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[UniqueEntity('slug')]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre du document ne peut être vide.')]
    #[Assert\Regex("/^[\p{L} ':\-0-9]+$/u", 'Le nom du document contient des caractères non autorisés.')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description du document ne peut être vide.')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $file = null;

    #[ORM\Column(nullable: true)]
    private ?float $ratingAverage = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    private ?Type $type = null;

    #[ORM\ManyToMany(targetEntity: Level::class, inversedBy: 'documents')]
    #[MaxDepth(1)]
    private Collection $levels;

    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'documents')]
    #[MaxDepth(1)]
    private Collection $subjects;

    #[ORM\ManyToMany(targetEntity: Theme::class, inversedBy: 'documents')]
    #[MaxDepth(1)]
    private Collection $themes;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    private ?User $author = null;

    #[ORM\Column]
    private ?int $downloadsNumber = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'downloadedDocuments')]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $downloaders;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'favoriteDocuments')]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $favoriteUsers;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'document', orphanRemoval: true)]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $comments;

    public function __construct()
    {
        $this->levels = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->downloaders = new ArrayCollection();
        $this->favoriteUsers = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getRatingAverage(): ?float
    {
        return $this->ratingAverage;
    }

    public function setRatingAverage(?float $ratingAverage): static
    {
        $this->ratingAverage = $ratingAverage;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): static
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

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
            $level->addDocument($this);
        }

        return $this;
    }

    public function removeLevel(Level $level): static
    {
        if ($this->levels->removeElement($level)) {
            $level->removeDocument($this);
        }

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
            $subject->addDocument($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        if ($this->subjects->removeElement($subject)) {
            $subject->removeDocument($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Theme>
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): static
    {
        if (!$this->themes->contains($theme)) {
            $this->themes->add($theme);
            $theme->addDocument($this);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): static
    {
        if ($this->themes->removeElement($theme)) {
            $theme->removeDocument($this);
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getDownloadsNumber(): ?int
    {
        return $this->downloadsNumber;
    }

    public function setDownloadsNumber(int $downloadsNumber): static
    {
        $this->downloadsNumber = $downloadsNumber;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getDownloaders(): Collection
    {
        return $this->downloaders;
    }

    public function addDownloader(User $downloader): static
    {
        if (!$this->downloaders->contains($downloader)) {
            $this->downloaders->add($downloader);
            $downloader->addDownloadedDocument($this);
        }

        return $this;
    }

    public function removeDownloader(User $downloader): static
    {
        if ($this->downloaders->removeElement($downloader)) {
            $downloader->removeDownloadedDocument($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavoriteUsers(): Collection
    {
        return $this->favoriteUsers;
    }

    public function addFavoriteUser(User $favoriteUser): static
    {
        if (!$this->favoriteUsers->contains($favoriteUser)) {
            $this->favoriteUsers->add($favoriteUser);
            $favoriteUser->addFavoriteDocument($this);
        }

        return $this;
    }

    public function removeFavoriteUser(User $favoriteUser): static
    {
        if ($this->favoriteUsers->removeElement($favoriteUser)) {
            $favoriteUser->removeFavoriteDocument($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setDocument($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getDocument() === $this) {
                $comment->setDocument(null);
            }
        }

        return $this;
    }
}
