<?php
/*
 * Copyright (C) CHEVEREAU Lazare - All Rights Reserved
 *
 * @project    phpavance
 * @file       MovieDetailController.php
 * @author     CHEVEREAU Lazare
 * @date       20/01/2022 11:59
 */

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\File;

class MovieDetailController extends AbstractController
{
    /**
     * @Route("/movie/{id}", name="movie_detail")
     */
    public function index(Movie $id, Request $request, ManagerRegistry $doctrine): Response
    {

        $movie = $id;
        $delete = $this->createFormBuilder()
            ->add(
                'admincode',
                PasswordType::class,
                [
                    'label' => 'Code admin:',
                    'constraints' => [new EqualTo($this->getParameter('admin_code'), null, "Le code n'est pas bon")],
                ]
            )
            ->add('supression', SubmitType::class, ['label' => 'supression'])
            ->getForm();


        $upload = $this->createFormBuilder()
            ->add('file', FileType::class, [
                'label' => 'Fichier: ',
                'constraints' => [
                    new File([
                        'maxSize' => '5024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Uploader l\'affiche'])
            ->getForm();


        $delete->handleRequest($request);
        $upload->handleRequest($request);

        if ($delete->isSubmitted() && $delete->isValid()) {
            $delete->getData();
            $doctrine->getManager()->remove($movie);
            $doctrine->getManager()->flush();
            $this->addFlash("info", "Le film a bien été supprimé");

            return $this->redirectToRoute('home_page');
        }

        if ($upload->isSubmitted() && $upload->isValid()) {
            $file = $upload->get('file')->getData();

            $file->move($this->getParameter('covers_directory'), $movie->getId().'.png');
            $this->addFlash("info", "La cover a bien été ajouté");

            return $this->redirectToRoute('movie_detail', ["id" => $movie->getId()]);
        }


        $package = new Package(new EmptyVersionStrategy());
        $file_link = $package->getUrl($this->getParameter('covers_directory').'/'.$movie->getId().'.png');


        $file_exists = (file_exists($file_link) ? $file_link : null);

        return $this->render('movie_detail/index.html.twig', [
            'controller_name' => 'MovieDetailController',
            'movie' => $id,
            'file_link' => $file_exists,
            'delete' => $delete->createView(),
            'upload' => $upload->createView(),
        ]);
    }
}
