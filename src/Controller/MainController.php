<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        // return $this->json([
        //     'message' => 'Welcome to freeCodeCamp!',
        //     'path' => 'src/Controller/MainController.php',
        // ]);
        return new Response("<h1>Welcome VueJs and Symfony</h1>".
                            "<h2>WhatsUp</h2>"); 
    }

    
    /**
     * @Route("/custom/{name}", name="custom")
     */
    public function custom(Request $req){
      return new Response("<h1>Custom Vue js is coming</h1>".
                            "<h2>Custom</h2>"); 
    }
}
