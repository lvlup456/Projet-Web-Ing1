<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Entity\User;

use App\Form\OrganizationType;
use App\Form\User1Type;

use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/admin/organization")
 */
class AdminOrganizationController extends AbstractController
{
    /**
     * @Route("/", name="organization_index", methods={"GET"})
     */
    public function index(OrganizationRepository $organizationRepository): Response
    {
        return $this->render('organization/index.html.twig', [
            'organizations' => $organizationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="organization_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder,\Swift_Mailer $mailer): Response
    {
        $organization = new Organization();
        $user = new User();
        $user->setRoles(["role_organization"]);
        $length = 10;
        $user->setPlainPassword(substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length));
 
        $organization->setUser($user);
        $user->setGereOrganization($organization);

        $form = $this->createForm(OrganizationType::class, $organization);
        $form1 = $this->createForm(User1Type::class, $user);

        $form1->handleRequest($request);
        $form->handleRequest($request);

        //link user form

        if ($form1->isSubmitted() && $form1->isValid() && $form->isSubmitted() && $form->isValid()) {
            
            $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('formationdevwebeisti@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/envoieMdpCreationUser.html.twig',
                            ['mdp' => $user->getPlainPassword()]
                        ),
                        'text/html'
                    );

            $mailer->send($message);

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword() );
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($organization);
            $entityManager->flush();

            return $this->redirectToRoute('organization_index');
        }

        return $this->render('organization/new.html.twig', [
            'organization' => $organization,
            'user' => $user,
            'form' => $form->createView(),
            'form1' => $form1->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="organization_show", methods={"GET"})
     */
    public function show(Organization $organization): Response
    {
        return $this->render('organization/show.html.twig', [
            'organization' => $organization,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="organization_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Organization $organization): Response
    {
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('organization_index');
        }

        return $this->render('organization/edit.html.twig', [
            'organization' => $organization,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="organization_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Organization $organization): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organization->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($organization);
            $entityManager->flush();
        }

        return $this->redirectToRoute('organization_index');
    }
}
