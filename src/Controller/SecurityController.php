<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/newAdmin/{id}", name="new_admin")
     */
    public function addAdmin(Request $request, User $user): Response
    {
        $secret = "432156789cba";

        $form = $this->createForm(AdminType::class);
        $form->handleRequest($request);

        // $user= $this->doctrine->getRepository(User::class)->find($id);
        dump($user);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get("secret")->getData() === $secret) {
                $user->setRoles(["ROLE_ADMIN"]);
                $em = $this->doctrine->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash("success", "Nouvel admin enregistré");

                return $this->redirectToRoute("index");
            } else {
                throw $this->createAccessDeniedException("Vous n'avez pas les droits admin");
            }
        }

        return $this->render("security/addAdmin.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}
