<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use App\Entity\Product;

class ProductController extends Controller
{
    // https://symfony.com/doc/current/doctrine.html

    // Une route et une action pour ajouter un nouveau Produit
    /**
     * @Route("/product", name="product")
     */
    public function index( EntityManagerInterface $entityManager )
    {
        // On récupère le gestionaire d'entité soit via un paramètre de l'action soit via:
        //$entityManager = $this->getDoctrine()->getManager();

        // Création d'un nouveau produit en dur
        $product = new Product();
        $product->setName('Arrosoir');
        $product->setPrice(54);
        $product->setDescription('Y r\'pleut!');


        // Préparation avant l'insertion réelle en base
        $entityManager->persist($product);

        // Exécution réeele de la requête INSERT
        $entityManager->flush();


        return new Response('Nouveau produit - Id: '.$product->getId());
    }





    // Une route pour afficher un produit en particulier
    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show( $id )
    {

        // Récupération du REPOSITORY de l'entité
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        // Un repository est une classe nous permettant de chercher parmi les entités d'une certaine classe
        // On a les méthodes find, findOneBy, findAll...


        if (!$product) {
            throw $this->createNotFoundException(
                'Aucun produit trouvé ' . $id
            );
        }

        return new Response('Produit ' . $id . ' trouvé: '.$product->getName());

        // or render a template
    }




    // Une route MAGIQUE pour afficher un produit en particulier
    // http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
    /**
     * @Route("/product/magic/{id}", name="product_magic_show")
     */
    public function magic_show( Product $product )
    {

        // Le ParamConverter va se charger de faire le job de recherche
        // Il faut avoir installé le bundle Sensio Extra Bundle
        // composer require sensio/framework-extra-bundle

        return new Response('Produit ' . $product->getId() . ' trouvé: '.$product->getName());

        // or render a template
    }

    // Update

    // Delete

    // More complexe query
}
