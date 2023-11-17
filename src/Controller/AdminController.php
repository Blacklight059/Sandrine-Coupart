<?php

namespace App\Controller;

use App\Entity\DietTypes;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\RecipeType;
use App\Form\UserType;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin', name: 'admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(UserRepository $userRepository): Response
    {
        // we get all user
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users
        ]);

    }

    #[Route('/add', name: 'admin_add')]
    public function add(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        UserAuthenticatorInterface $userAuthenticator, 
        LoginFormAuthenticator $authenticator, 
        EntityManagerInterface $entityManager
    ): Response
    {
    
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_USER']);
            
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request,
            );
        }

        return $this->render('admin/add.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/_remove/{id}', name: 'remove')]
    public function remove(
        EntityManagerInterface $entityManager, 
        UserRepository $userRepository, 
        int $id
    ): Response
    {
        // We retrieve the patient who corresponds to the id passed in the URL
        $user = $userRepository->findBy(['id' => $id])[0];

        // The patient is deleted
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin');
    }

    #[Route('/_edit/{id}', name: 'edit')]
    public function edit(
        EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $userPasswordHasher, 
        UserRepository $userRepository, 
        User $user, 
        HttpFoundationRequest $request, 
        int $id=null
    ): Response
    {

        // We retrieve the patient id in the url
        $user = $userRepository->findBy(['id' => $id])[0];

    
        $form = $this->createForm(UserType::class, $user);
            $user = $form->getData();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
        }


        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin');
        }

        $parameters = array(
            'form'      => $form->createView(),
            'user'      => $user,
        );

        return $this->render('admin/edit.html.twig', $parameters);

    }

    #[Route('/_recipe', name: 'recipe')]
    public function indexRecipe(RecipeRepository $RecipeRepository): Response
    {
        // we get all user
        $recipes = $RecipeRepository->findAll();

        return $this->render('admin/index_recipe.html.twig', [
            'controller_name' => 'AdminController',
            'recipes' => $recipes
        ]);

    }

    #[Route('/add_recipe', name: 'admin_add_recipe')]
    public function addRecipe(
        Request $request, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response
    {
    
        $recipe = new Recipe();

        $form2 = $this->createForm(RecipeType::class, $recipe);
        $recipe = $form2->getData();

        if ($request->getMethod() == 'POST') {
            $form2->handleRequest($request);
        }
        if ($form2->isSubmitted() && $form2->isValid()) {
            // encode the plain password
            /** @var UploadedFile $image */
            $image = $form2->get('imgFilename')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                 $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                 // this is needed to safely include the file name as part of the URL
                 $safeFilename = $slugger->slug($originalFilename);
                 $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                 // Move the file to the directory where image are stored
                 try {
                     $image->move(
                         $this->getParameter('imageFilename_directory'),
                         $newFilename
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }

                 // updates the 'imgFilename' property to store the PDF file name
                 // instead of its contents
                 $recipe->setImageFilename($newFilename);
             }

             // ... persist the $product variable or any other work

                $entityManager->persist($recipe);
                $entityManager->flush();
                // do anything else you need here, like send an email

        }
        $formView = $form2->createView();


        return $this->render('admin/add_recipe.html.twig', [
            'registrationForm2' => $form2->createView(),
            'formView2'=>$formView,
        ]);
    }

    #[Route('/_edit_recipe/{id}', name: 'edit_recipe')]
    public function edit_recipe(
        EntityManagerInterface $entityManager, 
        RecipeRepository $recipeRepository, 
        SluggerInterface $slugger,
        Recipe $recipe, 
        HttpFoundationRequest $request, 
        int $id=null
    ): Response
    {

        // We retrieve the patient id in the url
        $recipe = $recipeRepository->findBy(['id' => $id])[0];

    
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            /** @var UploadedFile $image */
            $image = $form->get('imgFilename')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                 $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                 // this is needed to safely include the file name as part of the URL
                 $safeFilename = $slugger->slug($originalFilename);
                 $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                 // Move the file to the directory where image are stored
                 try {
                     $image->move(
                         $this->getParameter('imageFilename_directory'),
                         $newFilename
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }

                 // updates the 'imgFilename' property to store the PDF file name
                 // instead of its contents
                 $recipe->setImgFilename($newFilename);
             }

             // ... persist the $product variable or any other work

                $entityManager->persist($recipe);
                $entityManager->flush();
                // do anything else you need here, like send an email

        }

        $parameters = array(
            'form'      => $form->createView(),
            'recipe'      => $recipe,
        );

        return $this->render('admin/edit_recipe.html.twig', $parameters);

    }

    #[Route('/_remove_recipe/{id}', name: 'remove_recipe')]
    public function remove_recipe(
        EntityManagerInterface $entityManager, 
        RecipeRepository $recipeRepository, 
        int $id
    ): Response
    {
        // We retrieve the patient who corresponds to the id passed in the URL
        $recipe = $recipeRepository->findBy(['id' => $id])[0];

        // The patient is deleted
        $entityManager->remove($recipe);
        $entityManager->flush();

        return $this->redirectToRoute('admin_recipe');
    }
}
