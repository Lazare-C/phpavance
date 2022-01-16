<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\AddMovieType;
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
use App\Service\MovieScrapper;
use Symfony\Component\Serializer\SerializerInterface;
use function Amp\Iterator\map;

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

           // $scrape = new MovieScrapper($doctrine);
            $movie =$scrape->scrapeDescription($movie);
            if($movie !=null){
                $entityManager->persist($movie);
                $entityManager->flush();
                return $this->redirectToRoute('home_page');
            }else{
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
            if( $file->getMimeType() == "application/json"){
                $movies = $serializer->deserialize(file_get_contents($file->getPathname()), 'App\Entity\Movie[]', 'json');
            }else{
                $movies = $serializer->deserialize(file_get_contents($file->getPathname()), 'App\Entity\Movie[]', 'csv');
            }
            $logger->info("CHAUSSURE");
                foreach ($movies as $movie)
                {
                    $movie->setAddBy("admin@movie.com");
                    $entityManager->persist($movie);
                }
                $entityManager->flush();
            return $this->redirectToRoute('home_page');
        }
        return $this->renderForm('add_movie/index.html.twig', [
            'form' => $form,
        ]);
    }

}
