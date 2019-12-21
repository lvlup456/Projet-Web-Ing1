<?php


namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController{

    /**
     * @Route("/", name="home_redirect")
     * @Route("/{_locale}/home", name="home")
     */

    public function index(Request $request, FormationRepository $formationRepository):Response{
        if ($request->attributes->get("_route") == "home_redirect"){
            return $this->redirect("/fr/home");
        } else if ($request->attributes->get("_route") == "home_redirect2"){
            return $this->redirectToRoute("home", array("_locale" => $request->attributes->get('_locale')));
        }

        $formation = $formationRepository->findAll();
        $list = [];

        foreach ($formation as $form){
            $diplome = $form->getDiplome();
            $domaine = $diplome->getDomaine()->getName();
            if (array_key_exists($domaine, $list)){
                $list1 = $list[$domaine];
                array_push($list1, $diplome->getName());
                $list[$domaine] = $list1;
            } else {
                $list[$domaine] = array($diplome->getName());
            }
        }
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search, array("data" => $list));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();
            if ($search["category"] == "categorie"){
                return $this->render('home.html.twig',
                    [
                        'current_menu' => 'home',
                        'users' => $list,
                        'form' => $form->createView(),
                        'errorcategory' => true
                    ]
                );
            }



            $forms = $formationRepository->findResult($search);
            $found = true;
            if (count($forms) == 0) {
                $found = false;
                $search["departement"] = "all";
                $forms = $formationRepository->findResult($search);
            }

            if (count($forms) == 0) {

                return $this->render('home.html.twig',
                    [
                        'current_menu' => 'home',
                        'users' => $list,
                        'form' => $form->createView()
                    ]
                );
            }

            return $this->render('result.html.twig',
                [
                    'current_menu' => 'home',
                    'data' => $forms,
                    'found' => $found
                ]
            );
        }
        return $this->render('home.html.twig',
            [
                'current_menu' => 'home',
                'users' => $list,
                'form' => $form->createView()
            ]
        );
    }
}