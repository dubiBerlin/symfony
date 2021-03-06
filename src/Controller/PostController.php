<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Form\PostType;
use App\Services\FileUploader;
use App\Services\Notification;
use Symfony\Component\Security\Core\User\UserInterface;


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
      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
      
      $posts = $postRepository->findAllPostByUser($this->getUser());

      return $this->render('post/index.html.twig', [
        'posts' => $posts,"profile_image"=>$this->getUser()->getImage()
      ]);
    }
    
    /**
     * @Route("/create", name="create")
     * @param Request req
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function create(Request $req, FileUploader $fileUploader, Notification $notification, UserInterface $user){
      // create new post
      $post = new Post();
      
      $form = $this->createForm(PostType::class, $post);
 
      $form->handleRequest($req);

      if($form->isSubmitted()){
        
        // entity manager
        $em = $this->getDoctrine()->getManager();

        //** @var uploadedFile $file  */
        $file = $req->files->get("post")["attachment"];
        
        if($file){
         $filename = $fileUploader->uploadFile($file);
          $post->setImage($filename);
        }
        $post->setCreatedAt(new \DateTime());
        $post->setUser($user);


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

      // $post = $postRepository->find($id);
      $post = $postRepository->findPostWithCategory($id);

      if(sizeof($post)>0){
        $post = $post["0"];  
      }
      // create the show view
      return $this->render("post/show.html.twig",["post"=>$post,"id"=>$id]);
    }


    /**
     * description:  deletes selected post
     * @Route("/delete/{id}", name="delete")
     * @param id
     * @return JsonResponse
     */
    public function remove(Post $post){
      $em = $this->getDoctrine()->getManager();
      $em->remove($post);
      $em->flush();
     
     $this->addFlash("success", "Post was removed");
     
      // create the show view
      return $this->redirect($this->generateUrl("postindex"));
    } 

    /**
     * description: returns a random post from database
     * @Route("/random", name="random")
     * @return Response
     */
    public function random(PostRepository $postRepository){
      
      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
      
      $posts = $postRepository->findAllPostByUser($this->getUser());  
      
      $post = sizeof($posts) > 0 ? $posts[random_int(0, sizeof($posts)-1)] : null;       
      
      return new JsonResponse(["randomPost"=>$post]);  
    }
}
