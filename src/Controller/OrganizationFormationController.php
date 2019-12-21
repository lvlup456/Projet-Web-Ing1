<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Inscrit;
use App\Entity\User;

use App\Form\FormationType;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("orga/formation")
 */
class OrganizationFormationController extends AbstractController
{
    /**
     * @Route("/", name="organization_formation_index", methods={"GET"})
     */
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('formationOrga/index.html.twig', [
            'formations' => $formationRepository->findBy(
                ['organization' => $this->getUser()->getGereOrganization() ]
            )
        ]);
    }

    /**
     * @Route("/new", name="organization_formation_new", methods={"GET","POST"})
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

        return $this->render('formationOrga/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("inscrit/{id}", name="organization_formation_show_inscrit", methods={"GET"})
     */
    public function showInscrit(Inscrit $inscrit): Response
    {
        return $this->render('formationOrga/showInscrit.html.twig', [
            'inscrit' => $inscrit,
        ]);
    }
    /**
     * @Route("/{id}", name="organization_formation_show", methods={"GET"})
     */
    public function show(Formation $formation): Response
    {
        return $this->render('formationOrga/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    /**
     * @Route("/{id}", name="organization_formation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Formation $formation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_formation_index');
    }

    /**
     * @Route("/inscritDelete/{id}", name="organization_inscrit_delete", methods={"GET","POST"})
     */
    public function deleteInscrit(Request $request, Inscrit $inscrit): Response
    {
        $id = $inscrit->getFormation()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($inscrit);
        $entityManager->flush();

        return $this->redirectToRoute('organization_formation_show',['id' => $id]);
    }

  /**
     * @Route("/inscritAccept/{id}", name="organization_inscrit_accept", methods={"GET","POST"})
     */
    public function acceptInscrit(Request $request, Inscrit $inscrit): Response
    {
        $id = $inscrit->getFormation()->getId();
        $inscrit->setConfirmer(TRUE);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('organization_formation_show',['id' => $id]);
    }
}
