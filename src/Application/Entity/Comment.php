<?php

namespace App\Application\Entity;

use App\Application\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @Assert\NotBlank(groups={"anonymous"})
     * @Assert\Length(min=2,groups={"anonymous"})
     */
    private string $author;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=5)
     */
    private string $content;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $postedAt;

    /**
    * @var Post
    * @ORM\ManyToOne(targetEntity="Post",inversedBy="comments")
    */
    private $post;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    */
    private ?User $user = null;

    public function __construct()
    {
        $this->postedAt = new DateTimeImmutable();
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
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string|null $author
     */ 
    public function setAuthor(?string $author):void
    {
        $this->author = $author;
    }

    /**
     * @return DateTimeImmutable
     */ 
    public function getPostedAt(): DateTimeImmutable
    {
        return $this->postedAt;
    }

    /**
     * @param DateTimeImmutable $postedAt
     */ 
    public function setPostedAt(DateTimeImmutable $postedAt): void
    {
        $this->postedAt = $postedAt;
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
     * @return Post
     */
    public function getPost(): Post{
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

   

    /**
     * @return User|null
     */ 
    public function getUser() : ?User
    {
        return $this->user;
    }

    /**
     *@param User|null $user
     */ 
    public function setUser($user):void
    {
        $this->user = $user;
    }
}