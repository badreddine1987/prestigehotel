<?php

namespace App\Entity;

use App\Entity\Commande;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{

    use TimestampableEntity;
    use SoftDeleteableEntity;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionCourte = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptionLong = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column(length: 5)]
    private ?string $prixJournalier = null;

    #[ORM\OneToOne(mappedBy: 'chambre', cascade: ['persist', 'remove'])]
    private ?Commande $commande = null;

    #[ORM\Column(length: 100)]
    private ?string $alias = null;

    #[ORM\Column(length: 100)]
    private ?string $categorie = null;

    public function __construct()
    {
        $this->commentaries = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescriptionCourte(): ?string
    {
        return $this->descriptionCourte;
    }

    public function setDescriptionCourte(string $descriptionCourte): self
    {
        $this->descriptionCourte = $descriptionCourte;

        return $this;
    }

    public function getDescriptionLong(): ?string
    {
        return $this->descriptionLong;
    }

    public function setDescriptionLong(string $descriptionLong): self
    {
        $this->descriptionLong = $descriptionLong;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrixJournalier(): ?string
    {
        return $this->prixJournalier;
    }

    public function setPrixJournalier(string $prixJournalier): self
    {
        $this->prixJournalier = $prixJournalier;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        // set the owning side of the relation if necessary
        if ($commande->getChambre() !== $this) {
            $commande->setChambre($this);
        }

        $this->commande = $commande;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
