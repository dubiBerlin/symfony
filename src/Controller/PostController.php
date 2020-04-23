<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post", name="post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post")
     */
    public function index()
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    
    
    /**
     * @Route("/post/create}", name="create")
     * @param Request req
     * @return Response
     */
    // public function create(Request $req){
    //   $post = new Post();

    //   $post->setTitle("This is going to be a title!");
    // }
}
