<?php
/*
 * Copyright (C) CHEVEREAU Lazare - All Rights Reserved
 *
 * @project    phpavance
 * @file       HomePageController.php
 * @author     CHEVEREAU Lazare
 * @date       20/01/2022 11:59
 */

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $movies = $doctrine->getRepository(Movie::class)->findAll();
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            'movies' => $movies
        ]);
    }
}
