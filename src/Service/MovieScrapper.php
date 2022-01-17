<?php
/*
 * Copyright (C) CHEVEREAU Lazare - All Rights Reserved
 *
 * @project    phpavance
 * @file       MovieScrapper.php
 * @author     CHEVEREAU Lazare
 * @date       17/01/2022 13:11
 */

namespace App\Service;

use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;

class MovieScrapper extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;

    }

    /**
     * @param Movie $movie
     */
    public function scrapeDescription(Movie $movie): ?Movie
    {
        $client = HttpClient::create();
        $omdbApiKey = $_SERVER['OMBDBAPIKEY'];
        $response = $client->request('GET', 'https://www.omdbapi.com/?apikey='.$omdbApiKey.'&t='.$movie->getName());
        $content = $response->toArray();

        if ($content['Response'] == "True") {
            $movie->setDescription($content['Plot']);
            $movie->setImdbVotes(intval(str_replace(",", "", $content['imdbVotes'])));
            $movie->setImdbRating(floatval($content['imdbRating']));
            $movie->setName($content['Title']);

            return $movie;
        } else {
            return null;
        }

    }

}
