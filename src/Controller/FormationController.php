<?php


namespace App\Controller;


use App\Entity\Inscrit;
use App\Entity\Formation;

use App\Form\InscritType;
use App\Form\FormationType;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController{

    protected $entityManager;
    protected $repository;

    /**
     * @Route("/{_locale}/showformation/{_id}", name="form_show")
     */

    public function index(Request $request, FormationRepository $formationRepository):Response{
        $form = $formationRepository->find($request->attributes->get('_id'));

        return $this->render('form/show.html.twig',
            [
                'current_menu' => 'form_show',
                'form' => $form
            ]
        );
    }

        /**
     * @Route("/new", name="formation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $formation = new Formation();

        if ( $this->getUser() != NULL && $this->getUser()->getRoles() == array('role_organizateur')){
            $formation->setOrganization($this->getUser()->getGereOrganization());
            $formation->setMail($this->getUser()->getEmail());
            $formation->setConfirmer(TRUE);
        }else{
            $formation->setConfirmer(FALSE);
        }

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('form/new.html.twig', [
            'current_menu' => 'addFormation',
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{_locale}/showformation/{_id}/inscription", name="form_inscription")
     */

    public function addAction(Request $request, FormationRepository $formationRepository,\Swift_Mailer $mailer)
    {
        // Set up required variables
        $this->initialise();

        // New object
        $inscrit = new Inscrit();
        $formulaire = $formationRepository->find($request->attributes->get('_id'));

        // Build the form
        $form = $this->createForm(InscritType::class, $inscrit);
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            // Check form data is valid
            if ($form->isValid())
            {
                // Save data to database
                $inscrit->setConfirmer(false);
                $formulaire->addInscrit($inscrit);
                $this->entityManager->persist($inscrit);
                $this->entityManager->flush();
                
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('formationdevwebeisti@gmail.com')
                    ->setTo($inscrit->getMail())
                    ->setBody(
                        $this->renderView(
                            'emails/inscriptionFormation.html.twig'
                        ),
                        'text/html'
                    );

                $mailer->send($message);

                // Redirect to view page
                return $this->redirectToRoute("home");
            }

            $inscrit2 = new Inscrit();
            $inscrit2->setMail($inscrit->getMail());
            $inscrit2->setDatedenaissance($inscrit->getDatedenaissance());
            $inscrit2->setNom($inscrit->getNom());
            $inscrit2->setPrenom($inscrit->getPrenom());
            unset($inscrit);
            unset($form);
            // New object
            $inscrit = $inscrit2;
            $form = $this->createForm(InscritType::class, $inscrit);
        }
        // If we are here it means that either
        //	- request is GET (user has just landed on the page and has not filled the form)
        //	- request is POST (form has invalid data)
        return $this->render(
            'form/form.html.twig',
            array (
                'form'	=>	$form->createView(),
                'formulaire' => $formulaire
            )
        );
    }


    protected function initialise()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->repository = $this->entityManager->getRepository('App:Inscrit');
    }

}