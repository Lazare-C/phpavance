<?php
/*
 * Copyright (C) CHEVEREAU Lazare - All Rights Reserved
 *
 * @project    phpavance
 * @file       StatisticsController.php
 * @author     CHEVEREAU Lazare
 * @date       17/01/2022 13:11
 */

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/statistics", name="statistics")
     */
    public function index(MovieRepository $movieRepository): Response
    {

        $movies = $movieRepository->findAll();

        return $this->render('statistics/index.html.twig', [
            'movies' => $movies,
        ]);
    }
}
