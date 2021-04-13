<?php

namespace App\Application\Entity;

use App\Application\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var string|null
     * @ORM\Column
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */
    private string $content;

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $user;

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
    * @return User
    */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
    * @param User $user
    */
    public function setUser(User $user): void
    {
        $this->user= $user;
    }

    /**
    * @return Collection
    */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Get the value of image
     *
     * @return  string|null
     */ 
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @param  string|null  $image
     */ 
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
}
