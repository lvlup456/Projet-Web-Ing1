<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Organization;

use App\Form\User2Type;
use App\Form\OrganizationType;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserChangePassword extends AbstractController
{

    /**
     * @Route("/{id}", name="changePassword", methods={"GET","POST"})
     */
    public function changePassword(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->getUser()->getId() == $user->getId()){
            $form = $this->createForm(User2Type::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
            
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('home');
            }
            return $this->render('UserChangePassword.html.twig', [
                'user' => $user,
                'form' => $form->createView()
            ]);
        }else{
            $this->redirectToRoute('home');
        }
    }

}
