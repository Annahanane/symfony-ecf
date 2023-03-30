<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $loanDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $returnDate = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\OneToMany(mappedBy: 'loans', targetEntity: Borrower::class)]
    private Collection $borrowers;

    public function __construct()
    {
        $this->borrowers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoanDate(): ?\DateTimeInterface
    {
        return $this->loanDate;
    }

    public function setLoanDate(\DateTimeInterface $loanDate): self
    {
        $this->loanDate = $loanDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(?\DateTimeInterface $returnDate): self
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    /**
     * @return Collection<int, Borrower>
     */
    public function getBorrowers(): Collection
    {
        return $this->borrowers;
    }

    public function addBorrower(Borrower $borrower): self
    {
        if (!$this->borrowers->contains($borrower)) {
            $this->borrowers->add($borrower);
            $borrower->setLoans($this);
        }

        return $this;
    }

    public function removeBorrower(Borrower $borrower): self
    {
        if ($this->borrowers->removeElement($borrower)) {
            // set the owning side to null (unless already changed)
            if ($borrower->getLoans() === $this) {
                $borrower->setLoans(null);
            }
        }

        return $this;
    }
}
