<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class MailType extends AbstractType
{
    public function buildForm ( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add('sender', EmailType::class,
                array(
                    'required' => false,
                    'label'  => 'Expéditeur: ',

                    'attr' => array(
                        'data-truc' => 'truc'
                    )
                ))
            ->add('dest', EmailType::class)
            ->add('object', TextType::class)
            ->add('body', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Send Mail'))
            ;
    }
}