<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $preparationTime = null;

    #[ORM\Column(length: 255)]
    private ?string $break = null;

    #[ORM\Column(length: 255)]
    private ?string $cooking_time = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ingredients = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $steps = null;

    #[ORM\ManyToMany(targetEntity: Allergens::class, mappedBy: 'recipe')]
    private Collection $allergens;

    #[ORM\ManyToMany(targetEntity: DietTypes::class, mappedBy: 'recipe')]
    private Collection $dietTypes;

    #[ORM\Column(length: 255)]
    private ?string $imgFilename = null;

    #[ORM\Column]
    private ?bool $free = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Comment::class)]
    private Collection $comments;

    public function __construct()
    {
        $this->allergens = new ArrayCollection();
        $this->dietTypes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPreparationTime(): ?string
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(string $preparationTime): static
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getBreak(): ?string
    {
        return $this->break;
    }

    public function setBreak(string $break): static
    {
        $this->break = $break;

        return $this;
    }

    public function getCookingTime(): ?string
    {
        return $this->cooking_time;
    }

    public function setCookingTime(string $cooking_time): static
    {
        $this->cooking_time = $cooking_time;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getSteps(): ?string
    {
        return $this->steps;
    }

    public function setSteps(string $steps): static
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * @return Collection<int, Allergens>
     */
    public function getAllergens(): Collection
    {
        return $this->allergens;
    }

    public function addAllergen(Allergens $allergen): static
    {
        if (!$this->allergens->contains($allergen)) {
            $this->allergens->add($allergen);
            $allergen->addRecipe($this);
        }

        return $this;
    }

    public function removeAllergen(Allergens $allergen): static
    {
        if ($this->allergens->removeElement($allergen)) {
            $allergen->removeRecipe($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DietTypes>
     */
    public function getDietTypes(): Collection
    {
        return $this->dietTypes;
    }

    public function addDietType(DietTypes $dietType): static
    {
        if (!$this->dietTypes->contains($dietType)) {
            $this->dietTypes->add($dietType);
            $dietType->addRecipe($this);
        }

        return $this;
    }

    public function removeDietType(DietTypes $dietType): static
    {
        if ($this->dietTypes->removeElement($dietType)) {
            $dietType->removeRecipe($this);
        }

        return $this;
    }

    public function getImgFilename(): ?string
    {
        return $this->imgFilename;
    }

    public function setImgFilename(string $imgFilename): static
    {
        $this->imgFilename = $imgFilename;

        return $this;
    }

    public function isFree(): ?bool
    {
        return $this->free;
    }

    public function setFree(bool $free): static
    {
        $this->free = $free;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setRecipe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRecipe() === $this) {
                $comment->setRecipe(null);
            }
        }

        return $this;
    }
}
