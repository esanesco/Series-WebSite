<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function list(SerieRepository $serieRepository): Response
    {
        //aller chercher series dans bdd
        //$series = $serieRepository->findBy([], ['popularity' => 'DESC', 'vote' => 'DESC'], 30, 10); //fonction qui retourne series ordenés par popularity puis par vote. Litime le resultat à 30 series et commence à partir de la numéro 10
       $series = $serieRepository->findBestSeries();
        // dd($series);
        return $this->render('serie/list.html.twig', [
            "series" => $series
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository -> find($id);

        if (!$serie){
            throw $this->createNotFoundException('OH nOOOOO');
        }


        return $this->render('serie/details.html.twig',
        ['serie' => $serie]);

    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        //dump($request);
        $serie = new Serie();
        $serie->setDateCreated(new \DateTime());
        $serieForm = $this->createForm(SerieType::class, $serie);

       // dump($serie);
        $serieForm->handleRequest($request);
        //dump($serie);

        if ($serieForm->isSubmitted() && $serieForm->isValid()){
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('succes', 'serie added correctly');
            return $this->redirectToRoute('serie_details', ['id'=>$serie->getId()]);

        }

        //todo traiter formulaire
        return $this->render('serie/create.html.twig', [
            "serieForm" => $serieForm->createView()
        ]);

    }

    /**
     * @Route("/demo", name="demo")
     */
    //sur cette demo on crée une nouvelle serie en bdd, on la supprime
    public function demo(EntityManagerInterface $entityManager): Response
    {
        //cree instance de mon entité
        $serie = new Serie();


        //hydrate toutes les propriétés
        $serie->setName('pif');
        $serie->setBackdrop('qwerty');
        $serie->setPoster('asdf');
        $serie->setDateCreated(new \DateTime("-2 hours"));
        $serie->setFirstAirDate(new \DateTime("-1 year"));
        $serie->setLastAirDate(new \DateTime("-6 months"));
        $serie->setGenres('comedy');
        $serie->setOverview('blablabla');
        $serie->setPopularity(152.00);
        $serie->setVote(6.2);
        $serie->setStatus('Canceled');
        $serie->setTmdbId(123564);

        dump($serie);

        //insert
        $entityManager->persist($serie);
        $entityManager->flush();

        dump($serie);

        //update
        $serie->setGenres('terror');
        $entityManager->flush();

        //delete
       // $entityManager->remove($serie);
       // $entityManager->flush();

       // $entityManager = $this->getDoctrine()->getManager(); --> deprecated


        return $this->render('serie/create.html.twig');

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Serie $serie, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('succes', 'serie deleted correctly');

        return $this->redirectToRoute('main_home');

    }
}
