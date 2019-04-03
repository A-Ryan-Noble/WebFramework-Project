<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="float",nullable=true)
     */
    private $bid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bidAccepted;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="bids")
     */
    private $bidders;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $questions = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $replies = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bidOnBy;

    public function __construct()
    {
        $this->bidders = new ArrayCollection();
    }

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getBidders(): Collection
    {
        return $this->bidders;
    }

    public function addBidder(User $bidder): self
    {
        if (!$this->bidders->contains($bidder)) {
            $this->bidders[] = $bidder;
            $bidder->addBid($this);
        }

        return $this;
    }

    public function removeBidder(User $bidder): self
    {
        if ($this->bidders->contains($bidder)) {
            $this->bidders->removeElement($bidder);
            $bidder->removeBid($this);
        }

        return $this;
    }

    public function getQuestions(): ?array
    {
        return $this->questions;
    }

    public function setQuestions(?array $questions): self
    {
        $this->questions[] = $questions;
        return $this;
    }

    public function getReplies(): ?array
    {
        return $this->replies;
    }

    public function setReplies(?array $replies): self
    {
        $this->replies[] = $replies;

        return $this;
    }

    public function getBidOnBy(): ?string
    {
        return $this->bidOnBy;
    }

    public function setBidOnBy(?string $bidOnBy): self
    {
        $this->bidOnBy = $bidOnBy;

        return $this;
    }

    public function __toString()
    {
        return $this->title.' ';
    }
}