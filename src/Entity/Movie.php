<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(
     *      min = 0,
     *      max = 10,
     *      notInRangeMessage = "La note doit être entre {{ min }} et {{ max }}.",
     * )
     */
    private $score = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $votersNumbers = 0;

    /**
     * @ORM\Column(type="string")
     */
    private $addBy ;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $imdbRating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $imdbVotes;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getVotersNumbers(): ?int
    {
        return $this->votersNumbers;
    }

    public function setVotersNumbers(int $votersNumbers): self
    {
        $this->votersNumbers = $votersNumbers;

        return $this;
    }

    public function getAddBy(): ?string
    {
        return $this->addBy;
    }

    public function setAddBy(string $addBy): self
    {
        $this->addBy = $addBy;

        return $this;
    }

    public function getImdbRating(): ?float
    {
        return $this->imdbRating;
    }

    public function setImdbRating(?float $imdbRating): self
    {
        $this->imdbRating = $imdbRating;

        return $this;
    }

    public function getImdbVotes(): ?int
    {
        return $this->imdbVotes;
    }

    public function setImdbVotes(int $imdbVotes): self
    {
        $this->imdbVotes = $imdbVotes;

        return $this;
    }
}
