<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column
     * @Assert\NotBlank
     * @Assert\Length(min=2)
     */
    private string $title;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $publishedAt;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */
    private string $content;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private $comments;

    public function __construct()
    {
        $this->publishedAt = new DateTimeImmutable();
        $this->comments = new ArrayCollection();
    }

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */ 
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */ 
    public function setTitle(string $title):void
    {
        $this->title = $title;
    }

    /**
     * @return DateTimeImmutable
     */ 
    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * @param DateTimeImmutable $publishAt
     */ 
    public function setPublishedAt(DateTimeImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return string|null
     */ 
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */ 
    public function setContent(string $content):void
    {
        $this->content = $content;
    }

     /**
     * @return Collection
     */
    public function getComments(){
        return $this->comments;
    }
}
