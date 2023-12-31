<?php

namespace App\DataFixtures;

use App\Entity\Allergens;
use App\Entity\Comment;
use App\Entity\DietTypes;
use App\Repository\RecipeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use Faker;

class RecipesFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger,
    ){}

    public function load(ObjectManager $manager): void
    {

        $allergen1 = new Allergens();
        $allergen1->setName('Arachide');
        $manager->persist($allergen1);

        $allergen2 = new Allergens();
        $allergen2->setName('Gluten');
        $manager->persist($allergen2);

        $allergen3 = new Allergens();
        $allergen3->setName('Mollusques');
        $manager->persist($allergen3);

        $dietType1 = new DietTypes();
        $dietType1->setName('Sans sel');
        $manager->persist($dietType1);

        $dietType2 = new DietTypes();
        $dietType2->setName('végétarien');
        $manager->persist($dietType2);
        
        $dietType3 = new DietTypes();
        $dietType3->setName('Sans lactose');        
        $manager->persist($dietType3);

        $faker = Faker\Factory::create('fr-FR');
        for($usr2 = 1; $usr2 <= 5; $usr2++) {
            $comment = new Comment();
            $comment->setComment($faker->paragraph());
            $comment->setDate($faker->dateTime());
            $comment->setRecipe($recipe);
            $comment->setUser($faker->ramdomElement($user));

            $manager->persist($comment);
        }
            

        $manager->flush();

    }
}
