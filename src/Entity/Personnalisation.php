<?php

namespace App\Entity;

use App\Repository\PersonnalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonnalisationRepository::class)]
// Cette entité représente une option choisissable pendant la personnalisation.
class Personnalisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 150)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $prix = null;

    // Chaque option de personnalisation appartient à une figurine.
    #[ORM\ManyToOne(inversedBy: 'personnalisations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Figurine $figurine = null;

    // Une même option peut se retrouver dans plusieurs commandes.
    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'personnalisation')]
    private Collection $commandes;

    public function __construct()
    {
        // La collection est initialisée pour gérer les commandes liées à cette option.
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        // EasyAdmin affiche le nom de l'option au lieu d'un objet technique.
        return $this->nom ?? 'Personnalisation';
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getFigurine(): ?Figurine
    {
        return $this->figurine;
    }

    public function setFigurine(?Figurine $figurine): static
    {
        $this->figurine = $figurine;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        // On ajoute la commande et on synchronise la relation inverse.
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setPersonnalisation($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // Quand on retire une commande, on enlève aussi le lien côté commande.
            if ($commande->getPersonnalisation() === $this) {
                $commande->setPersonnalisation(null);
            }
        }

        return $this;
    }
}
