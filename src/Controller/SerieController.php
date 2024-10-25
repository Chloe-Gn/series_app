<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/serie', name: 'serie')]
class SerieController extends AbstractController
{
    #[Route('/test', name: '_test')]
    public function test(EntityManagerInterface $em): Response
    {

        //a chaque visite URL/test, persistence serie dans BDD
        $serie = new Serie();
        $serie-> setName('Smallville')
            ->setStatus('ENDED')
            ->setDateCreated(new \DateTime())
            ->setFirstAirDate(new \DateTime('2001-10-16'))
            ->setGenres('Vieux');

        $em->persist($serie);
        $em->flush();

        return new Response("Nouvelle série créée avec succès");
    }

    #[Route('/list/{status}', name: '_list')]
    public function list(SerieRepository $serieRepository, ?string $status = null): Response {
        //ci dessus, injection de service
        //les méthodes du controller, associées à routes peuvent invoquer
        //ce qu'elles veulent

        //$serieRepository va seulement chercher dans table Serie
       // $series = $serieRepository->findAll();


        if ($status && !\in_array($status, ['returning', 'ended', 'canceled'])) {
            throw $this->createNotFoundException('statut non valide');
        }

        $criteria = ['genres' => 'Drama'];

        if ($status) {
            $criteria['status'] = $status;
        }


/*
        $series = $serieRepository->findBy(
            $criteria,
            ['firstAirDate' => 'DESC']
        );

        */

        $series = $serieRepository->findSeriesByGenre('Drama', $status);

      //  Retour, on a un tableau d'objets. pas de tableau de tableau

        return $this->render('serie/serie.html.twig', [
            'series' => $series
        ]);
    }

    #[Route('/detail/{id}', name: '_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, SerieRepository $serieRepository): Response{
//ordre arguments pas important

       $serie = $serieRepository->find($id);

       if (!$serie) {

           throw $this->createNotFoundException();
       }


        return $this->render('serie/detail.html.twig', [
            'serie' => $serie
        ]);

    }

    /*

    #[Route('/detail/{id}', name: '_update', requirements: ['id' => '\d+'])]
    public function update(Serie $serie) : Response {

      //le param converter convertit un paramètre en objet

        return $this->render('serie/detail.html.twig', [
            'serie' => $serie
        ]);

    }
 */


    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, EntityManagerInterface $em, Serie $serie) : Response {


        $form =  $this->createForm(SerieType::class,  $serie);

        $form->handleRequest($request);


        //clause pour quand form est soumis (method post)
        if ($form->isSubmitted()){

            $em->flush();

            $this->addFlash('success', '🎉 Une nouvelle série a été modifiée avec succès !');

            return $this->redirectToRoute('serie_list');


        }

        return $this->render('serie/edit.html.twig', ['form' => $form]);

    }


    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em) : Response {

     $serie = new Serie();

     $form =  $this->createForm(SerieType::class, $serie);

     $form->handleRequest($request);

     //new line of comment

     //clause pour quand form est soumis (method post)
     if ($form->isSubmitted() && $form->isValid()){
        $em->persist($serie);
        $em->flush();

        $this->addFlash('success', '🎉 Une nouvelle série a été créée avec succès !');

        return $this->redirectToRoute('serie_list');


        // dd($serie);

     }

        return $this->render('serie/edit.html.twig', ['form' => $form]);

}






}
