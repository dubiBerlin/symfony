<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Form\PostType;

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
      
      $form = $this->createForm(PostType::class, $post);
 
      $form->handleRequest($req);

      if($form->isSubmitted()){
        
        // entity manager
        $em = $this->getDoctrine()->getManager();

        //** @var uploadedFile $file */
        $file = $req->files->get("post")["attachment"];
        
        if($file){
          $filename = md5(uniqid()).".".$file->guessClientExtension();

          $file->move(
            $this->getParameter("uploads_directory"),
            $filename
          );

          $post->setImage($filename);
        }

        $em->persist($post);
        $em->flush();
        return $this->redirect($this->generateUrl("postindex"));
      }


      

      // return a response
      // return  $this->redirect($this->generateUrl("postindex"));//new Response("Post was created!");
      return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * description:  gets selected post and displays it
     * @Route("/show/{id}", name="show")
     * @param id
     * @return Response
     */
    public function show($id, PostRepository $postRepository){

      $post = $postRepository->find($id);
      $post = $postRepository->findPostWithCategory($id);

 

      // create the show view
      return $this->render("post/show.html.twig",["post"=>$post,"id"=>$id]);
    }


    /**
     * description:  deletes selected post
     * @Route("/delete/{id}", name="delete")
     * @param id
     * @return Response
     */
    public function remove(Post $post){
      $em = $this->getDoctrine()->getManager();
      $em->remove($post);
      $em->flush();
     
     $this->addFlash("success", "Post was removed");
     
      // create the show view
      return $this->redirect($this->generateUrl("postindex"));
      
    }





    // /**
    //  * description:  gets selected post and displays it
    //  * @Route("/show/{id}", name="show")
    //  * @param Post $post
    //  * @return Response
    //  */
    // public function show(Post $post){
    //   if($post==null){
    //     // dump("");
    //     return new Response("Post with such id does not exist");
    //   }else{
    //     dump($post); 
    //     die;
    //     // create the show view
    //     return $this->render("show.html.twig",["post"=>$post]);
    //   }
    // }
}
