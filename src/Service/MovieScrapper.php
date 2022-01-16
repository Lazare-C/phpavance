<?php
namespace App\Service;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Config\FrameworkConfig;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
    public function scrapeDescription(Movie $movie)
    {
        $client = HttpClient::create();
        $omdbApiKey = $_SERVER['OMBDBAPIKEY'];
        $response = $client->request('GET', 'https://www.omdbapi.com/?apikey='.$omdbApiKey.'&t=' .$movie->getName());
        $content = $response->toArray();

        if($content['Response'] == "True"){
            $movie->setDescription($content['Plot']);
            $movie->setImdbVotes(intval(str_replace(",","", $content['imdbVotes'])));
            $movie->setImdbRating(floatval($content['imdbRating']));
            $movie->setName($content['Title']);
            $this->doctrine->getManager()->persist($movie);
            $this->doctrine->getManager()->flush();
            return $movie;
        }else{
            return null;
        }

    }

}
