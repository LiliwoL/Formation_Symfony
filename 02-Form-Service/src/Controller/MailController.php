<?php

// src/Controller/MailController.php
namespace App\Controller;

use App\Entity\Mail;

use App\Form\MailType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class MailController extends Controller
{
    /**
     * @Route("/", name="newMail")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, \Swift_Mailer $mailer, ValidatorInterface $validator )
    {

        /*
            Ici, utilisation de 2 services
            Ces services sont "demandés" en typant les paramètres de l'action du controller

            Ainsi, on demande le service Request et le service Mailer
            https://symfony.com/doc/current/service_container.html#content_wrapper
        */


        // Création d'un objet Mail (une Entity) et on lui affecte des valeurs de bases
        $mail = new Mail();
        $mail->setSender( "sendermail.com" );
        $mail->setDest( "dest@mail.com" );
        $mail->setObject( "Test mail" );
        $mail->setBody( "Un message de test");

        $errors = $validator->validate( $mail );

        if ( count( $errors) > 0 )
        {
            $errorsString = (string) $errors;

            return new Response( $errorsString);
        }



        // Formulaire créé dans le controlleur --> uniquement pour un formulaire à usage unique
        $form = $this->createFormBuilder( $mail )
            ->add('sender', EmailType::class,
                array(
                    'required' => false,
                    'label'  => 'Expéditeur: ',
                ))
            ->add('dest', EmailType::class)
            ->add('object', TextType::class)
            ->add('body', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Send Mail'))
        ->getForm();


        /*
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $mail = $form->getData();

            // Create a message
            $message = (
            new \Swift_Message( $mail->getObject() ))
                ->setFrom([ $mail->getSender() => 'John Doe'])
                ->setTo([ $mail->getDest() => 'A name'])
                ->setBody( $mail->getBody() )
            ;

            // Send the message
            $result = $mailer->send($message);

            return $this->redirectToRoute('mail_success');
        }
        */

        // ##################################@


        // Formulaire créé dans un fichier différent, réutilisable à différents endrpits de l'app
        $mailForm = $this->createForm ( MailType::class, $mail );



        $mailForm->handleRequest($request);

        if ($mailForm->isSubmitted() && $mailForm->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
                $mail = $mailForm->getData();

            // Create a message
            $message = (
                new \Swift_Message( $mail->getObject() ))
                    ->setFrom([ $mail->getSender() => 'John Doe'])
                    ->setTo([ $mail->getDest() => 'A name'])
                    ->setBody( $mail->getBody() )
            ;

            // Send the message
            $result = $mailer->send($message);


            return $this->redirectToRoute('mail_success');
        }


        return $this->render('mail/new.html.twig', array(
            'form' => $mailForm->createView(),
            'variable' => "Une variable"
        ));
    }


    /**
     * @Route("/mail_success", name="mail_success")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailSuccess ()
    {
        echo "SUCCESS";
        return new Response();
    }
}
