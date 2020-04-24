<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/post", name="post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    
    
    /**
     * @Route("/create}", name="create")
     * @param Request req
     */
    public function create(Request $req){
      // create new post
      $post = new Post();
      $post->setTitle("This is going to be a title!");

      // return a response
    }
}
