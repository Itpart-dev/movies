<?php


namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiCallService
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    private $theMovieDbUrl;
    private $theMovieDbApiKey;
    private $theMovieDbApiToken;

    /**
     * ApiCallService constructor.
     * @param HttpClientInterface $client
     * @param $theMovieDbApiKey
     * @param $theMovieDbApiToken
     * @param $theMovieDbUrl
     */
    public function __construct(HttpClientInterface $client, $theMovieDbApiKey, $theMovieDbApiToken, $theMovieDbUrl)
    {
        $this->client = $client;
        $this->theMovieDbUrl = $theMovieDbUrl;
        $this->theMovieDbApiKey = $theMovieDbApiKey;
        $this->theMovieDbApiToken = $theMovieDbApiToken;
    }

    /**
     * @return array
     */
    public function getGenders(): array
    {
        return $this->getApi('genre/movie/list');
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAllMovies()
    {
        return $this->getApi('discover/movie');
    }

    /**
     * @param string $var
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getApi(string $var): array
    {
        $response = $this->client->request(
            'GET',
            $this->theMovieDbUrl . $var
            , [

                'query' => [
                    'api_key' => $this->theMovieDbApiKey,
                ],
                'auth_bearer' => $this->theMovieDbApiToken
            ]
        );

        return $response->toArray();
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPopularYoutubeLink(): array
    {
        $list = $this->getPopular();

        return array_merge($this->getYoutubeLink($list['results'][0]['id']), [
                'original_title' => $list['results'][0]['original_title'],
                'poster_path' => 'https://image.tmdb.org/t/p/original'.$list['results'][0]['poster_path']
            ]
        );
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPopular(): array
    {
        return $this->getApi('discover/movie?sort_by=popularity.desc');
    }

    /**
     * @param $id
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getYoutubeLink($id): array
    {
        $movie = $this->getApi('movie/' . $id . '/videos');
        return [
            'link' => 'https://www.youtube.com/watch?v=' . $movie['results'][0]['key'],
            'trailer_description' => $movie['results'][0]['name']
        ];
    }

    /**
     * @param $query
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function search($query)
    {
        return $this->getApi('search/multi?language=en-US&query='.$query.'&page=1&include_adult=false');
    }

    /**
     * @param $id
     * @return mixed
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getSingleMovie($id)
    {
        $movie = $this->getApi('movie/'. $id);
        $genre = [];
        foreach ( $movie['genres'] as $key => $value) {
            $genre[] = $value['name'];
        }

        return array_merge([
            'original_title' => $movie['original_title'],
            'overview' => $movie['overview'],
            'genres' => implode(", ", $genre),
        ], $this->getYoutubeLink($id));

    }
}