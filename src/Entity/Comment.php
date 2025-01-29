<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id] // this is the way doctrine defines the primary key 
    #[ORM\GeneratedValue] // Indicates that the value for this key will be generated automatically (e.g., auto-increment for integers)
    #[ORM\Column] //Defines column properties in DB (ex: type and length).
    private ?int $id = null; 


    #[ORM\Column(length: 350)]
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
