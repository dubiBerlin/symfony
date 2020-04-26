<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register()
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
      ->add("Registrieren",SubmitType::class,[
        "attr"=>[
          "class"=>"btn btn-success float-right"
        ]
      ])
      ->getForm();

      return $this->render('registration/index.html.twig', [
          'form' => $form->createView() 
      ]);
    }
}
