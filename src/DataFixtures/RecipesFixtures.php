<?php

namespace App\DataFixtures;

use App\Entity\Allergens;
use App\Entity\DietTypes;
use App\Entity\Recipe;
use App\Repository\AllergensRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use Faker;

class RecipesFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
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

        $recipe = new Recipe();
            $recipe->setName('Courgettes farcies légères');
            $recipe->setDescription("Vous allez enfin vous réconcilier avec les légumes ! Aussi diététique que savoureuse, cette recette de courgettes farcies accompagnées de riz basmati constitue le repas complet idéal durant un régime minceur. Facile et rapide à réaliser, elle mise sur une farce à base de viande de bœuf dégraissée, un coulis de tomates et une touche de parmesan pour la gourmandise. Un plat qui vous apporte tous les nutriments nécessaires... sans vous faire prendre un gramme !");
            $recipe->setPreparationTime('15 min');
            $recipe->setBreak("0");
            $recipe->setCookingTime("50 min");
            $recipe->setIngredients("
            Courgette(s)
            2
            Bœuf haché 5% de matière grasse
            Bœuf haché 5% de matière grasse
            400 g
            Riz basmati
            Riz basmati
            200 g
            Oignon(s)
            Oignon(s)
            1
            Ail
            Ail
            1 Gousse
            Coulis de tomates
            Coulis de tomates
            200 g
            Parmesan
            Parmesan
            30 g
            Huile d'olive
            Huile d'olive
            2 c. à soupe
            Thym
            Thym
            2 branche(s)
            Basilic
            Basilic
            8 feuille (s)
            Persil
            Persil
            4 brin(s)
            Sel
            Sel
            1 pincée(s)
            Poivre
            Poivre
            1 pincée(s)");
            $recipe->setSteps("1. Préchauffez votre four th.7 (200°C). Lavez les courgettes et coupez-les en deux dans le sens de la longueur. À l'aide d'une cuillère, évidez chaque moitié de courgette et conservez la chair à part. Pelez les oignons et l’ail. Émincez les oignons et hachez l'ail. Réservez.
            2. Dans une poêle, faites revenir dans l'huile d'olive la chair des courgettes avec les oignons émincés et l’ail en remuant de temps en temps. Ajoutez le bœuf haché et faites revenir le tout en mélangeant de nouveau. Saupoudrez de thym et de basilic haché, salez et poivrez. La farce est prête.            
            3. Remplissez les demi-courgettes avec la farce et disposez-les dans un plat allant au four. Versez le coulis de tomates sur les courgettes. Enfournez et laissez cuire 40 min.
            4. Quelques minutes avant la fin de la cuisson de vos courgettes farcies, saupoudrez de parmesan et replacez le plat au four sous le gril quelques minutes encore. Pendant ce temps, portez à ébullition un grand volume d’eau. Mettez le riz à cuire 8 à 10 min selon le temps indiqué sur le paquet. Servez les courgettes farcies accompagnées du riz et décorez-les de brins de persil.");
            $recipe->addAllergen($allergen1);
            $recipe->addDietType($dietType1, $dietType2);
        $manager->persist($recipe);

        $manager->flush();

    }
}
