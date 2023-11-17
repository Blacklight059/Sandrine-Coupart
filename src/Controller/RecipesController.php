<?php

namespace App\Controller;

use App\Entity\Allergens;
use App\Repository\AllergensRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipes', name: 'recipes')]
class RecipesController extends AbstractController
{
    #[Route('/', name: 'recipes')]
    public function index(RecipeRepository $recipeRepository,
        ): Response
    {
        $user = $this->getUser();
        $allergensIds = [];
        if($user !== null){       

            if($allergensIds !== null){       
                foreach($user->getAllergens() as $allergens)
                {
                    array_push($allergensIds, $allergens->getId());
                }

            }
        }

        $recipesConnect = $recipeRepository->findRecipesWithNoAllergens($allergens);


        return $this->render('recipes/index.html.twig', [
            'controller_name' => 'RecipesController',
            'user' => $user,
            'recipesConnect' => $recipesConnect
        ]);
    }
}
