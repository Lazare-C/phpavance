<?php
/*
 * Copyright (C) CHEVEREAU Lazare - All Rights Reserved
 *
 * @project    phpavance
 * @file       MovieDetailController.php
 * @author     CHEVEREAU Lazare
 * @date       17/01/2022 13:11
 */

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\EqualTo;

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
                ['constraints' => [new EqualTo($_SERVER['ADMIN_CODE'], null, "Le code n'est pas bon")]]
            )
            ->add('supression', SubmitType::class, ['label' => 'supression'])
            ->getForm();


        $delete->handleRequest($request);

        if ($delete->isSubmitted() && $delete->isValid()) {
             $delete->getData();
             $doctrine->getManager()->remove($movie);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('home_page');
        }

        return $this->render('movie_detail/index.html.twig', [
            'controller_name' => 'MovieDetailController',
            'movie' => $id,
            'delete' => $delete->createView()
        ]);
    }
}
