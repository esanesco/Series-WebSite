<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/series", name="serie_")
 */

class SerieController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(): Response
    {
        //todo : aller chercher series dans bdd
        return $this->render('serie/list.html.twig', [

        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(int $id): Response
    {
        //todo : aficher detail de la serie
        return $this->render('serie/details.html.twig');

    }

    /**
     * @Route("/create", name="create")
     */
    public function create(): Response
    {
        //todo : creation d'une page
        return $this->render('serie/create.html.twig');

    }
}
