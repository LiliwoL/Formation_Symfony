<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;

use symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



use Symfony\Component\Form\Extension\Core\Type\TextType;

class IndexController extends Controller
{
    /**
     * Une page daccueil avec les liens vers les différentes routes
     *
     * https://symfony.com/doc/current/routing.html
     *
     * Fichiers de configuration .env
     *
     * Appel de service (Injection de dépendance, Service Container)
     * Ici, utilisation du service REQUEST
     *
     *
     * GET
     *
     * GET + Parametres / Parametres optionnels
     *
     * PUT + Parametres / Params optionnels
     *
     *
     * DELETE + Parametres
     */


    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function indexAction()
    {
        $output = "01 - Introduction - Requests - Responses <br/><br/><pre>";
        $output .= <<<EOL
             ______   ____  __ _____ ___  _   ___   __
            / ___\ \ / /  \/  |  ___/ _ \| \ | \ \ / /
            \___ \\ V /| |\/| | |_ | | | |  \| |\ V /
             ___) || | | |  | |  _|| |_| | |\  | | |
            |____/ |_| |_|  |_|_|   \___/|_| \_| |_|
            
             ____   ___  _   _ _____ ___ _   _  ____
            |  _ \ / _ \| | | |_   _|_ _| \ | |/ ___|
            | |_) | | | | | | | | |  | ||  \| | |  _
            |  _ <| |_| | |_| | | |  | || |\  | |_| |
            |_| \_\\___/ \___/  |_| |___|_| \_|\____|
EOL;
        $output .= "</pre>";
        
        // Création d'un objet Response
        $response = new Response ($output);

        // Header avec un mime type
        // https://developer.mozilla.org/fr/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types
        $response->headers->set('Content-Type', 'text/html');

        // Code de réponse
        $response->setStatusCode(Response::HTTP_OK);
        
        return $response;
    }




    /**
     * @Route("get", name="get")
     * @return Response
     */
    public function getAction()
    {
        $output = "<pre>";
        $output .= <<<EOL
              ____ _____ _____
             / ___| ____|_   _|
            | |  _|  _|   | |
            | |_| | |___  | |
             \____|_____| |_|
EOL;
        $output .= "</pre>";


        // Appel du service REQUEST
        $request = Request::createFromGlobals();

        $output .= "<pre>";
        $output .= print_r($request, true);
        $output .= "</pre>";


        // the URI being requested (e.g. /about) minus any query parameters
        $request->getPathInfo();

        // retrieves $_GET and $_POST variables respectively
        $request->query->get('id');

        // retrieves $_SERVER variables
        $request->server->get('HTTP_HOST');

        // retrieves an instance of UploadedFile identified by "attachment"
        $request->files->get('attachment');

        // retrieves a $_COOKIE value
        $request->cookies->get('PHPSESSID');

        // retrieves an HTTP request header, with normalized, lowercase keys
        $request->headers->get('host');
        $request->headers->get('content_type');

        $request->getMethod();    // e.g. GET, POST, PUT, DELETE or HEAD
        $request->getLanguages(); // an array of languages the client accepts


        return new Response ($output);
    }


    /**
     * @Route(
     *     "getWithParam/{id}",
     *     name="getWithParam",
     *     defaults={
     *      "id" = "NONE"
     *     }
     * )
     * @TODO: With Default defaults={"id" = 1}
     * @return Response
     */

    public function getWithParamAction( $id )
    {
        $output = "<pre>";
        $output .= <<<EOL
              ____ _____ _____  __        _____ _____ _   _
             / ___| ____|_   _| \ \      / /_ _|_   _| | | |
            | |  _|  _|   | |    \ \ /\ / / | |  | | | |_| |
            | |_| | |___  | |     \ V  V /  | |  | | |  _  |
             \____|_____| |_|      \_/\_/  |___| |_| |_| |_|
            
             ____   _    ____      _    __  __ ____
            |  _ \ / \  |  _ \    / \  |  \/  / ___|
            | |_) / _ \ | |_) |  / _ \ | |\/| \___ \
            |  __/ ___ \|  _ <  / ___ \| |  | |___) |
            |_| /_/   \_\_| \_\/_/   \_\_|  |_|____/

EOL;
        $output .= "</pre>";



        // Appel du service REQUEST
        $request = Request::createFromGlobals();

        $output .= "<pre>";
        $output .= print_r( $request, true );
        $output .= "</pre>";




        // the URI being requested (e.g. /about) minus any query parameters
        $request->getPathInfo();

        // retrieves $_GET and $_POST variables respectively
        $output .= "Parametre: " . $id;

        if ( $id == "NONE" )
        {
            return $this->redirectToRoute(
                'getWithParamTyped',
                array(
                    'id' => 'test',
                    'name' => 123
                )
            );
        }

        if ( $id == "FORWARD" )
        {
            return $this->forward(
                '\App\Controller\IndexController::indexAction',
                array()
            );
        }

        if ( $id == "ERREUR" )
        {
            throw  $this->createNotFoundException('Ce compte client est inconnu');
        }

        return new Response ($output);
    }

    /**
     * @Route(
     *     "get/{id}/{name}",
     *      name="getWithParamTyped",
     *      requirements={
     *          "id" = "\w+",
     *          "name" = "\d+"
     *      }
     *     )
     * @return Response
     */
    public function getWithParamTypedAction( $id, $name)
    {
        $output = "<pre>";
        $output .= <<<EOL
              ____ _____ _____  __        _____ _____ _   _
             / ___| ____|_   _| \ \      / /_ _|_   _| | | |
            | |  _|  _|   | |    \ \ /\ / / | |  | | | |_| |
            | |_| | |___  | |     \ V  V /  | |  | | |  _  |
             \____|_____| |_|      \_/\_/  |___| |_| |_| |_|
            
             _______   ______  _____ ____
            |_   _\ \ / /  _ \| ____|  _ \
              | |  \ V /| |_) |  _| | | | |
              | |   | | |  __/| |___| |_| |
              |_|   |_| |_|   |_____|____/
            
             ____   _    ____      _    __  __ ____
            |  _ \ / \  |  _ \    / \  |  \/  / ___|
            | |_) / _ \ | |_) |  / _ \ | |\/| \___ \
            |  __/ ___ \|  _ <  / ___ \| |  | |___) |
            |_| /_/   \_\_| \_\/_/   \_\_|  |_|____/

EOL;
        $output .= "</pre>";



        // Appel du service REQUEST
        $request = Request::createFromGlobals();

        // retrieves $_GET and $_POST variables respectively
        $output .= "Parametre Obligatoirement un chiffre: " . $id . " " . $name;

        return new Response ($output);
    }



    /**
     * @Route(
     *     "delete/{parametre}",
     *     name="deleteWithParamTyped",
     *     requirements={"parametre" = "\w+"}
     *     )
     * @Method({"DELETE"})
     * @return Response
     */
    public function deleteWithParamTypedAction( $parametre )
    {
        $output = "<pre>";
        $output .= <<<EOL
             ____  _____ _     _____ _____ _____
            |  _ \| ____| |   | ____|_   _| ____|
            | | | |  _| | |   |  _|   | | |  _|
            | |_| | |___| |___| |___  | | | |___
            |____/|_____|_____|_____| |_| |_____|
            
            __        _____ _____ _   _
            \ \      / /_ _|_   _| | | |
             \ \ /\ / / | |  | | | |_| |
              \ V  V /  | |  | | |  _  |
               \_/\_/  |___| |_| |_| |_|
            

             _______   ______  _____ ____
            |_   _\ \ / /  _ \| ____|  _ \
              | |  \ V /| |_) |  _| | | | |
              | |   | | |  __/| |___| |_| |
              |_|   |_| |_|   |_____|____/
            
             ____   _    ____      _    __  __ ____
            |  _ \ / \  |  _ \    / \  |  \/  / ___|
            | |_) / _ \ | |_) |  / _ \ | |\/| \___ \
            |  __/ ___ \|  _ <  / ___ \| |  | |___) |
            |_| /_/   \_\_| \_\/_/   \_\_|  |_|____/

EOL;
        $output .= "</pre>";


        // retrieves $_GET and $_POST variables respectively
        $output .= "Parametre Du delete Obligatoirement du texte: " . $parametre;

        return new Response ($output);
    }

}