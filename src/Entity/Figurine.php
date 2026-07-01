<?php

namespace App\Entity;

use App\Repository\FigurineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FigurineRepository::class)]
// Cette entité représente une figurine vendue dans le catalogue.
class Figurine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $prix_base = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    // Une figurine peut proposer plusieurs personnalisations.
    /**
     * @var Collection<int, Personnalisation>
     */
    #[ORM\OneToMany(targetEntity: Personnalisation::class, mappedBy: 'figurine')]
    private Collection $personnalisations;

    public function __construct()
    {
        // Initialise la collection des personnalisations disponibles.
        $this->personnalisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        // Affiche le nom de la figurine dans EasyAdmin.
        return $this->nom ?? 'Figurine';
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrixBase(): ?int
    {
        return $this->prix_base;
    }

    public function setPrixBase(int $prix_base): static
    {
        $this->prix_base = $prix_base;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Collection<int, Personnalisation>
     */
    public function getPersonnalisations(): Collection
    {
        return $this->personnalisations;
    }

    public function addPersonnalisation(Personnalisation $personnalisation): static
    {
        // Ajoute une personnalisation et synchronise la relation Doctrine.
        if (!$this->personnalisations->contains($personnalisation)) {
            $this->personnalisations->add($personnalisation);
            $personnalisation->setFigurine($this);
        }

        return $this;
    }

    public function removePersonnalisation(Personnalisation $personnalisation): static
    {
        // Retire une personnalisation et nettoie le lien inverse.
        if ($this->personnalisations->removeElement($personnalisation)) {
            if ($personnalisation->getFigurine() === $this) {
                $personnalisation->setFigurine(null);
            }
        }

        return $this;
    }
}
