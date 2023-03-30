<?php

namespace App\Entity;

use App\Repository\BorrowerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorrowerRepository::class)]
class Borrower
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 190)]
    private ?string $firstname = null;

    #[ORM\Column(length: 190)]
    private ?string $lastname = null;

    #[ORM\Column(length: 190)]
    private ?string $tel = null;

    #[ORM\ManyToOne(inversedBy: 'borrowers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?loan $loans = null;

    #[ORM\OneToOne(inversedBy: 'borrower', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $users = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getLoans(): ?loan
    {
        return $this->loans;
    }

    public function setLoans(?loan $loans): self
    {
        $this->loans = $loans;

        return $this;
    }

    public function getUsers(): ?user
    {
        return $this->users;
    }

    public function setUsers(user $users): self
    {
        $this->users = $users;

        return $this;
    }

    
}
