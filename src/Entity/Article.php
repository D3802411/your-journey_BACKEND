<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attraction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activity = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 2000)]
    private ?string $textArea = null;

    #[ORM\Column(nullable: true)]
    private ?bool $photo = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article')]
    private Collection $comment;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getAttraction(): ?string
    {
        return $this->attraction;
    }

    public function setAttraction(?string $attraction): static
    {
        $this->attraction = $attraction;

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(?string $activity): static
    {
        $this->activity = $activity;

        return $this;
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


    public function getTextArea(): ?string
    {
        return $this->textArea;
    }

    public function setTextArea(?string $textArea): static
    {
        $this->textArea = $textArea;

        return $this;
    }

    public function isPhoto(): ?bool
    {
        return $this->photo;
    }

    public function setPhoto(?bool $photo): static
    {
        $this->photo = $photo;

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
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
