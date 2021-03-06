<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Services\FileUploader;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $req, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader)
    {

      $form = $this->createFormBuilder()
      ->add("username")
      ->add("password", RepeatedType::class, [
        'type' => PasswordType::class,
        'invalid_message' => 'The password fields must match.',
        'options' => ['attr' => ['class' => 'password-field']],
        'required' => true,
        'first_options'  => ['label' => 'Password'],
        'second_options' => ['label' => 'Confirm Password'],
      ])
      ->add('attachment',FileType::class,  [
        "mapped"=>false
      ])
      ->add("Registrieren",SubmitType::class,[
        "attr"=>[
          "class"=>"btn btn-success btn-block"
        ]
      ])
      ->getForm();

      $form->handleRequest($req);

      if($form->isSubmitted()){
        $params = $form->getData();
        $user = new User();        
        $encoded = $encoder->encodePassword($user,$params["password"]);

        $user->setUsername($params["username"]);
        $user->setPassword($encoded);
        
        $file = $req->files->get("form")["attachment"];
        
        if($file){
         $filename = $fileUploader->uploadFile($file);
         $user->setImage($filename);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl("app_login"));
      }

      return $this->render('registration/index.html.twig', [
          'form' => $form->createView() 
      ]);
    }
}
