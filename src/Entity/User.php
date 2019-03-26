<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Book", mappedBy="user")
     */
    private $books;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\book", inversedBy="bidders")
     */
    private $bids;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBooks(Book $books): self
    {
        if (!$this->books->contains($books)) {
            $this->books[] = $books;
            $books->setUser($this);
        }

        return $this;
    }

    public function removeBooks(Book $books): self
    {
        if ($this->books->contains($books)) {
            $this->books->removeElement($books);
            // set the owning side to null (unless already changed)
            if ($books->getUser() === $this) {
                $books->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->username.' ';
    }

    /**
     * @return Collection|book[]
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(book $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids[] = $bid;
        }

        return $this;
    }

    public function removeBid(book $bid): self
    {
        if ($this->bids->contains($bid)) {
            $this->bids->removeElement($bid);
        }

        return $this;
    }
}