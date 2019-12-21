<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;


class AProposController extends AbstractController{

    /**
     * @Route("/{_locale}/apropos", name="apropos")
     */

    public function index(Request $request):Response{
        return $this->render('apropos.html.twig',['current_menu' => 'apropos']);
    }
}