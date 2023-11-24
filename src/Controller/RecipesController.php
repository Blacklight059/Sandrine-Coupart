<?php

namespace App\Controller;

use App\Entity\Allergens;
use App\Entity\Comment;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\AllergensRepository;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipes', name: 'recipes')]
class RecipesController extends AbstractController
{
    #[Route('/', name: 'recipes')]
    public function index(RecipeRepository $recipeRepository,
        ): Response
    {
        $recipesPay = $recipeRepository->findBy(array('free' => false));
        $recipesFree = $recipeRepository->findBy(array('free' => true));

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
        if ($allergensIds !== null) {
            $recipesConnect = $recipeRepository->findRecipesWithNoAllergens($allergensIds);
        }




        return $this->render('recipes/index.html.twig', [
            'controller_name' => 'RecipesController',
            'user' => $user,
            'recipesConnect' => $recipesConnect,
            'recipesPay' => $recipesPay,
            'recipesFree' => $recipesFree
        ]);
    }

    #[Route('/_recipe/{id}', name: '_recipe')]
    public function show(RecipeRepository $recipeRepository,
    Recipe $recipe,
    RecipeRepository $recipeRepo,
    CommentRepository $commentRepo,
    int $id=null,
    Request $request,
    EntityManagerInterface $entityManager,
    ): Response
    {
        $recipe = $recipeRepository->findBy(['id' => $id])[0];

        $user = $this->getUser();
        $allergensIds = [];
        $dietTypeIds = [];
        if($user !== null){       

            if($allergensIds !== null){       
                foreach($user->getAllergens() as $allergens)
                {
                    array_push($allergensIds, $allergens->getId());
                }
                foreach($user->getDietTypes() as $dietTypes)
                {
                    array_push($dietTypeIds, $dietTypes->getId());
                }

            }
        }
        if ($allergensIds !== null) {
            $recipesConnect = $recipeRepository->findRecipesWithNoAllergens($allergensIds);
            $recipesConnect2 = $recipeRepository->findRecipesWithNoDietTypes($dietTypeIds);

        }

        // Comment system
        $recipe = $recipeRepo->findBy(['id' => $id])[0];
        $date = new DateTime();
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $comment = $form->getData();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $comment->setRecipe($recipe);
            $comment->setUser($user);
            $comment->setDate($date);
            
            $entityManager->persist($comment);
            $entityManager->flush();
            // do anything else you need here, like send an email

        }
        $comment = $commentRepo->findBy(array('recipe' => $recipe));
        foreach($comment as $comments) {
            $commentUser = $comments->getUser();
        }

        $formView = $form->createView();


        return $this->render('recipes/show.html.twig', [
            'controller_name' => 'RecipesController',
            'user' => $user,
            'recipesConnect' => $recipesConnect,
            'recipesConnect2' => $recipesConnect2,
            'recipes' => $recipe,
            'commentForm' => $form->createView(),
            'formView'=>$formView,
            'comments' => $comment,
            'commentUser' => $commentUser,

        ]);
    }

    public function showComment(
        CommentRepository $commentRepo,
        RecipeRepository $recipeRepo,
        int $id=null

        ) 
        {
            $recipe = $recipeRepo->findBy(['id' => $id])[0];
            $comment = $commentRepo->findBy(array('recipe_id' => $recipe));

            return $this->render('recipes/show.html.twig', [
                'controller_name' => 'RecipesController',
                'comments' => $comment
            ]);
    }
}
