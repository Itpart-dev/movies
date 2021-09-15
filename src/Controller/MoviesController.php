<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Service\ApiCallService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    /**
     * @Route("/movies", name="movies")
     */
    public function index(ApiCallService $apiCallService): Response
    {
        $allMovies = $apiCallService->getAllMovies();

        return $this->render('movies/index.html.twig', [
            'movies' => $allMovies
        ]);
    }

    /**
     * @param ApiCallService $apiCallService
     * @return Response
     */
    public function genre(ApiCallService $apiCallService): Response
    {
        $genres = $apiCallService->getGenders();

        return $this->render('movies/genre.html.twig', [
            'genres' => $genres
        ]);
    }

    /**
     * @param ApiCallService $apiCallService
     * @return Response
     */
    public function popular(ApiCallService $apiCallService): Response
    {
        $data = $apiCallService->getPopularYoutubeLink();

        return $this->render('movies/popular.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * @Route("/search/{query}", methods="GET", name="movies_search_utility")
     */
    public function search(ApiCallService  $apiCallService, $query)
    {
        $searchedMovies = $apiCallService->search($query);
        //dd($searchedMovies);
        $names = [];
        if (!empty($searchedMovies['results'][1])) {
            foreach ($searchedMovies['results'] as $key => $value) {
                $names[$value['id']] = $value['title'] ?? $value['name'];

            }
        }

        return $this->json([
            'movies' => $names
        ], 200, [], []);

    }

    /**
     * @Route("/movie/{id}", methods="GET", name="load_movie")
     */
    public function getSingleMovie(ApiCallService  $apiCallService, $id)
    {
        return $this->json(
            $apiCallService->getSingleMovie($id)
        , 200, [], []);
    }

}
