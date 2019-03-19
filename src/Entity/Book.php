<?php

namespace App\Entity;

//use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $barcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $genre;

    /**
     * @ORM\Column(type="float")
     */
    private $startingBid;

    /**
     * @ORM\Column(type="float")
     */
    private $bid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bidAccepted;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentQuestion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerQs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getStartingBid(): ?float
    {
        return $this->startingBid;
    }

    public function setStartingBid(float $startingBid): self
    {
        $this->startingBid = $startingBid;

        return $this;
    }

    public function getBid(): ?float
    {
        return $this->bid;
    }

    public function setBid(float $bid): self
    {
        $this->bid = $bid;

        return $this;
    }

    public function getBidAccepted(): ?bool
    {
        return $this->bidAccepted;
    }

    public function setBidAccepted(bool $bidAccepted): self
    {
        $this->bidAccepted = $bidAccepted;

        return $this;
    }

    public function getCommentQuestion(): ?string
    {
        return $this->commentQuestion;
    }

    public function setCommentQuestion(string $commentQuestion): self
    {
        $this->commentQuestion = $commentQuestion;

        return $this;
    }

    public function getAnswerQs(): ?string
    {
        return $this->answerQs;
    }

    public function setAnswerQs(?string $answerQs): self
    {
        $this->answerQs = $answerQs;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
