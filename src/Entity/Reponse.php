<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reponse = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Question $idQuestion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getIdQuestion(): ?Question 
    {
        return $this->idQuestion;
    }

    public function __toString()
    {
        return $this->idQuestion;
    }

    public function setIdQuestion(?Question $idQuestion): static
    {
        $this->idQuestion = $idQuestion;

        return $this;
    }
}
