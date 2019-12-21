<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Inscrit;

use App\Form\FormationType;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/formation")
 */
class AdminFormationController extends AbstractController
{
    /**
     * @Route("/", name="admin_formation_index", methods={"GET"})
     */
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/NonConfirmer", name="admin_formation_index_non_accepte", methods={"GET"})
     */
    public function indexNonAccepter(FormationRepository $formationRepository): Response
    {
        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findBy(
                ['confirmer' => FALSE]
            ),
        ]);
    }

    /**
     * @Route("/new", name="admin_formation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('admin_formation_index');
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_formation_show", methods={"GET"})
     */
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_formation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Formation $formation): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_formation_index');
        }



        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

      /**
     * @Route("/accept/{id}", name="admin_formation_accept", methods={"GET","POST"})
     */
    public function accept(Request $request, Formation $formation): Response
    {
        $formation->setConfirmer(TRUE);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_formation_index');
    }

    /**
     * @Route("delete/{id}", name="admin_formation_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Formation $formation): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('admin_formation_index');
    }

    /**
     * @Route("inscrit/{id}", name="admin_formation_show_inscrit", methods={"GET"})
     */
    public function showInscrit(Inscrit $inscrit): Response
    {
        return $this->render('formation/showInscrit.html.twig', [
            'inscrit' => $inscrit,
        ]);
    }

    /**
     * @Route("/inscritDelete/{id}", name="admin_inscrit_delete", methods={"GET","POST"})
     */
    public function deleteInscrit(Request $request, Inscrit $inscrit): Response
    {
        $id = $inscrit->getFormation()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($inscrit);
        $entityManager->flush();

        return $this->redirectToRoute('admin_formation_show',['id' => $id]);
    }

    /**
     * @Route("/inscritAccept/{id}", name="admin_inscrit_accept", methods={"GET","POST"})
     */
    public function acceptInscrit(Request $request, Inscrit $inscrit,\Swift_Mailer $mailer): Response
    {
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('formationdevwebeisti@gmail.com')
        ->setTo($inscrit->getMail())    
        ->setBody(
            $this->renderView(
                'emails/validationInscription.html.twig'
            ),
            'text/html'
        );
        $mailer->send($message);

        $id = $inscrit->getFormation()->getId();
        $inscrit->setConfirmer(TRUE);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin_formation_show',['id' => $id]);
    }
}
