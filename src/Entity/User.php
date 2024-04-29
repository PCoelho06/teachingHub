<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte enregistré avec cette adresse email.')]
#[UniqueEntity(fields: ['username'], message: 'Ce nom d\'utilisateur est déjà utilisé.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner une adresse email.')]
    #[Assert\Email(message: 'Veuillez renseigner une adresse email valide.')]
    #[Assert\Regex('/^[a-zA-Z0-9._-]+@ac-[a-zA-Z0-9._-]{2,}\.fr$/', 'Vous devez utiliser votre adresse email académique pour pouvoir vous inscrire.')]
    #[Ignore]
    private ?string $email = null;

    #[ORM\Column]
    #[Ignore]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez renseigner un mot de passe.')]
    #[Assert\Regex("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#!$%&+-=])(?=\\S+$).{8,}$/", 'Votre mot de passe doit contenir au moins 8 caractères parmi lesquels au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial parmis @ # ! $ % & + - =')]
    #[Assert\NotCompromisedPassword(null, 'Ce mot de passe a été révelé lors d\'une fuite de données et ne devrait plus être utilisé. Merci de choisir un autre mot de passe.')]
    #[Ignore]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre prénom.')]
    #[Assert\Regex("/^[\p{L} ']+$/u")]
    #[Ignore]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre nom.')]
    #[Assert\Regex("/^[\p{L} ']+$/u")]
    #[Ignore]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column]
    #[Ignore]
    private ?\DateTimeImmutable $registered_at = null;

    #[ORM\Column(type: 'boolean')]
    #[Ignore]
    private $isVerified = false;

    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'author')]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $documents;

    #[ORM\ManyToMany(targetEntity: Document::class, inversedBy: 'downloaders')]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $downloadedDocuments;

    #[ORM\ManyToMany(targetEntity: Document::class, mappedBy: 'favoriteUsers')]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $favoriteDocuments;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'author')]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $comments;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(groups: ['biography'], message: 'Veuillez renseigner la biographie.')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(groups: ['support-link'], message: 'Veuillez renseigner un lien Buy me a coffee.')]
    private ?string $supportLink = null;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->downloadedDocuments = new ArrayCollection();
        $this->favoriteDocuments = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registered_at;
    }

    public function setRegisteredAt(\DateTimeImmutable $registered_at): static
    {
        $this->registered_at = $registered_at;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

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
            $document->setAuthor($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getAuthor() === $this) {
                $document->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDownloadedDocuments(): Collection
    {
        return $this->downloadedDocuments;
    }

    public function addDownloadedDocument(Document $downloadedDocument): static
    {
        if (!$this->downloadedDocuments->contains($downloadedDocument)) {
            $this->downloadedDocuments->add($downloadedDocument);
        }

        return $this;
    }

    public function removeDownloadedDocument(Document $downloadedDocument): static
    {
        $this->downloadedDocuments->removeElement($downloadedDocument);

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getFavoriteDocuments(): Collection
    {
        return $this->favoriteDocuments;
    }

    public function addFavoriteDocument(Document $favoriteDocument): static
    {
        if (!$this->favoriteDocuments->contains($favoriteDocument)) {
            $this->favoriteDocuments->add($favoriteDocument);
        }

        return $this;
    }

    public function removeFavoriteDocument(Document $favoriteDocument): static
    {
        $this->favoriteDocuments->removeElement($favoriteDocument);

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getSupportLink(): ?string
    {
        return $this->supportLink;
    }

    public function setSupportLink(?string $supportLink): static
    {
        $this->supportLink = $supportLink;

        return $this;
    }
}
