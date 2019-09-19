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

/**
 * Classe MailController
 * 
 * -> Injection de dépendances
 * -> Entité
 * -> Validation
 * -> Formulaire dans le contrôleur
 * -> Formulaire dans un FormType
 * -> Vue
 */
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
            Ici, utilisation de 3 services
            Ces services sont "demandés" en typant les paramètres de l'action du controller
            C'est ce qu'on appelle l'AUTOWIRING
            https://symfony.com/doc/current/service_container/autowiring.html

            Ainsi, on demande le service Request, le service Mailer et le service Validator
            https://symfony.com/doc/current/service_container.html#content_wrapper
        */

        // ********************************************************

        // ********** CREATION ET VALIDATION D'UNE ENTITE
        // Création d'un objet Mail (une Entity) 
        // et on lui affecte des valeurs de bases
        $mailEntity = new Mail();
        $mailEntity->setSender( "sender@mail.com" );
        $mailEntity->setDest( "dest@mail.com" );
        $mailEntity->setObject( "Test mail" );
        $mailEntity->setBody( "Un message de test");

        // Validation de l'entité
        $errors = $validator->validate( $mailEntity );

        // En présence d'erreur, renvoi d'une réponse avec les erreurs
        if ( count( $errors) > 0 )
        {
            $errorsString = (string) $errors;

            return new Response( $errorsString);
        }
        // ********************************************************


        // ********** CREATION D'UN FORMULAIRE DANS LE CONTRÔLEUR
            // Uniquement pour un formulaire à usage unique
            $formulaireUnique = $this->createFormBuilder( $mailEntity ) // On passe l'entité en paramètre
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
            
            // Gestion de ce formulaire
            $formulaireUnique->handleRequest($request);

            // Validation
            if ($formulaireUnique->isSubmitted() && $formulaireUnique->isValid()) 
            {
                // Mise à jour de l'entité avec les données du formulaire
                // $formulaireUnique->getData()
                $mailEntity = $formulaireUnique->getData();

                // Création d'un message avec les données de l'entité
                $message = (
                        new \Swift_Message( $mailEntity->getObject() )
                    )
                    ->setFrom([ $mailEntity->getSender() => 'John Doe'])
                    ->setTo([ $mailEntity->getDest() => 'A name'])
                    ->setBody( $mailEntity->getBody() 
                );

                // Envoi du message via le service SwiftMailer
                $result = $mailer->send($message);

                return $this->redirectToRoute('mail_success');
            }
        // ********************************************************


        // ********** CREATION D'UN FORMULAIRE ISSU DE FORM_TYPE
            // Réutilisable à différents endrpits de l'app
            $formulaireReutilisable = $this->createForm ( 
                MailType::class,    // Classe du formulaire MailType
                $mailEntity         // Entité à utiliser
            );

            // Gestion de ce formulaire
            $formulaireReutilisable->handleRequest($request);

            // Validation
            if ($formulaireReutilisable->isSubmitted() && $formulaireReutilisable->isValid()) 
            {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                    $mailEntity = $formulaireReutilisable->getData();

                // Create a message
                $message = (
                        new \Swift_Message( $mailEntity->getObject() )
                    )
                        ->setFrom([ $mailEntity->getSender() => 'John Doe'])
                        ->setTo([ $mailEntity->getDest() => 'A name'])
                        ->setBody( $mailEntity->getBody() )
                ;

                // Envoi du message via le service SwiftMailer
                $result = $mailer->send($message);


                return $this->redirectToRoute('mail_success');
            }


        return $this->render('mail/new.html.twig', array(
            'formulaireUnique' => $formulaireUnique->createView(),
            'formulaireReutilisable' => $formulaireReutilisable->createView(),
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
