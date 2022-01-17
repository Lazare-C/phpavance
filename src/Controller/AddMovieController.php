<?php
/*
 * Copyright (C) CHEVEREAU Lazare - All Rights Reserved
 *
 * @project    phpavance
 * @file       AddMovieController.php
 * @author     CHEVEREAU Lazare
 * @date       17/01/2022 13:11
 */

namespace App\Controller;

use App\Entity\Movie;
use App\Form\AddMovieType;
use App\Service\MovieScrapper;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\File;

class AddMovieController extends AbstractController
{
    /**
     * @Route("/movie/add", name="add_movie")
     */
    public function index(Request $request, ManagerRegistry $doctrine, MovieScrapper $scrape): Response
    {

        $movie = new Movie();
        $form = $this->createForm(AddMovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $entityManager = $doctrine->getManager();
            $movie = $scrape->scrapeDescription($movie);
            if ($movie != null) {

                /** @var Movie $old_movie */
                $old_movie = $entityManager->getRepository(Movie::class)->findOneBy(['name' => $movie->getName()]);

                if ($old_movie !== null) {
                    $old_movie->setScore(
                        ($old_movie->getScore() * $old_movie->getVotersNumbers() + $movie->getScore(
                            )) / ($old_movie->getVotersNumbers() + 1)
                    );
                    $old_movie->setVotersNumbers($old_movie->getVotersNumbers() + 1);
                    $entityManager->persist($old_movie);
                    $entityManager->remove($movie);
                    $entityManager->detach($movie);
                    $entityManager->flush();
                    $this->addFlash("info", "Film déja existant, ajout de la note");
                } else {
                    $movie->setVotersNumbers(1);
                    $entityManager->persist($movie);
                    $entityManager->flush();
                    $this->addFlash("succes", "Le film a bien été ajouté");
                }

                return $this->redirectToRoute('home_page');
            } else {
                $form->addError(new FormError('Le film n\'existe pas'), "name");
            }
        }
        return $this->renderForm('add_movie/index.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/movie/add/bulk", name="add_movie_bulk")
     */
    public function bulk(Request $request, ManagerRegistry $doctrine, SerializerInterface $serializer, LoggerInterface $logger): Response
    {
        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class,[               'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'application/json',
                        'text/csv',
                        'text/plain'
                    ],
                    'mimeTypesMessage' => 'Merci de donner un fichier CSV ou JSON (type rentré: {{ type }})',
                    'maxSizeMessage' => 'Le fichier est trop gros (taille maximal: {{ limit }} {{ suffix }}).'
                ])]])
            ->add('save', SubmitType::class, ['label' => 'Ajouté'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            /** @var UploadedFile $file */
            $file = $form['attachment']->getData();
            $logger->debug($file->getMimeType());
            if ($file->getMimeType() == "application/json") {
                $movies = $serializer->deserialize(
                    file_get_contents($file->getPathname()),
                    'App\Entity\Movie[]',
                    'json'
                );
            } else {
                $movies = $serializer->deserialize(
                    file_get_contents($file->getPathname()),
                    'App\Entity\Movie[]',
                    'csv'
                );
            }
            $logger->info("CHAUSSURE");
            foreach ($movies as $movie) {
                $movie->setAddBy("admin@movie.com");
                $entityManager->persist($movie);
            }
            $entityManager->flush();

            return $this->redirectToRoute('home_page');
        }

        return $this->renderForm('add_movie/bulk.html.twig', [
            'form' => $form,
        ]);
    }
}
