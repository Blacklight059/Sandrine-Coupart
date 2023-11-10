<?php

namespace App\Controller;

use App\Entity\DietTypes;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

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


}
