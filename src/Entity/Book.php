<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 190)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?int $yearOfEdition = null;

    #[ORM\Column]
    private ?int $numberOfPages = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $isbnCode = null;

    #[ORM\ManyToMany(targetEntity: Author::class, mappedBy: 'books')]
    private Collection $authors;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: loan::class)]
    private Collection $loans;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->loans = new ArrayCollection();
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

    public function getYearOfEdition(): ?int
    {
        return $this->yearOfEdition;
    }

    public function setYearOfEdition(?int $yearOfEdition): self
    {
        $this->yearOfEdition = $yearOfEdition;

        return $this;
    }

    public function getNumberOfPages(): ?int
    {
        return $this->numberOfPages;
    }

    public function setNumberOfPages(int $numberOfPages): self
    {
        $this->numberOfPages = $numberOfPages;

        return $this;
    }

    public function getIsbnCode(): ?string
    {
        return $this->isbnCode;
    }

    public function setIsbnCode(?string $isbnCode): self
    {
        $this->isbnCode = $isbnCode;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, loan>
     */
    public function getLoans(): Collection
    {
        return $this->loans;
    }

    public function addLoan(loan $loan): self
    {
        if (!$this->loans->contains($loan)) {
            $this->loans->add($loan);
            $loan->setBook($this);
        }

        return $this;
    }

    public function removeLoan(loan $loan): self
    {
        if ($this->loans->removeElement($loan)) {
            // set the owning side to null (unless already changed)
            if ($loan->getBook() === $this) {
                $loan->setBook(null);
            }
        }

        return $this;
    }
}
