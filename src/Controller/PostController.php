<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Repository\PostRepository;

/**
 * @Route("/post", name="post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(PostRepository $postRepository)
    {
      $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    
    
    /**
     * @Route("/create", name="create")
     * @param Request req
     * @return Response
     */
    public function create(Request $req){
      // create new post
      $post = new Post();
      $post->setTitle("This is going to be a title!");
 
      // entity manager
      $em = $this->getDoctrine()->getManager();
      $em->persist($post);
      $em->flush();

      // return a response
      return new Response("Post was created!");
    }

    /**
     * description:  gets selected post and displays it
     * @Route("/show/{id}", name="show")
     * @param id
     * @return Response
     */
    public function show($id, PostRepository $postRepository){

      $post = $postRepository->find($id);
      dump($post); 
       die;


      // create the show view
      return $this->render("show.html.twig",["post"=>$post]);
    }
}
