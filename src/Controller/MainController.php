<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render("home/index.html.twig");
    }

    
    /**
     * // ? after name makes is optional
     * @Route("/custom/{name?}", name="custom")
     * @param Request req
     * @return Response
     */
    public function custom(Request $req){
      $name = $req->get("name");
      return   $this->render("home/custom.html.twig",["name"=>$name]);
    }
}
