<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id] // this is the way doctrine defines the primary key 
    #[ORM\GeneratedValue] // Indicates that the value for this key will be generated automatically (e.g., auto-increment for integers)
    #[ORM\Column] //Defines column properties in DB (ex: type and length).
    #[Groups(['comments.index'])] //I need this attribute for the serialiser to encode this property in json for the api
    private ?int $id = null; 

    //#[ORM\Column(type: 'datetime')]
    //private ?\DateTimeInterface $publishedAt = null;


    #[ORM\Column(length: 350)]
    #[Groups(['comments.index'])] //I need this attribute for the serialiser to encode this property in json for the api
    private ?string $content = null;


    #[ORM\ManyToOne(inversedBy: 'comment')]   // Foreign keys in Symfony are declared using rlations
    private ?Article $article = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    /*public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    } */
    
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

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
