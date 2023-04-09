<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LibraryService
{
    protected $client;
    protected $base_uri = 'http://wishlist.test/';

    public function __construct(Client $client, SessionInterface $session)
    {
        $this->client = $client;
        $this->session = $session;
    }

    public function all()
    {
        $cacheKey = 'libraries.all';
        $cacheTime = 60 * 60;
        $response = $this->client->request('POST', $this->base_uri.'oauth/token');
        $access_token = json_decode((string) $response->getBody(), true)['access_token'];
        $response = $this->client->request('GET', $this->base_uri.'api/libraries', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $access_token",
            ]
        ]);
        return json_decode($response->getBody()->getContents());
    }

    public function create($data)
    {
        $response = $this->client->request('POST', $this->base_uri.'oauth/token');
        $access_token = json_decode((string) $response->getBody(), true)['access_token'];
        $response = $this->client->request('POST', $this->base_uri.'api/libraries', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $access_token",
            ],
            'json' => $data,
        ]);
        $this->session->getFlashBag()->add('success', 'Wish List was created successfully.');
        return $response->getStatusCode() === 201;
    }

    public function delete($id)
    {
        $response = $this->client->request('POST', $this->base_uri.'oauth/token');
        $access_token = json_decode((string) $response->getBody(), true)['access_token'];
        $response = $this->client->request('DELETE', $this->base_uri.'api/libraries/'.$id, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $access_token",
            ]
        ]);
        $this->session->getFlashBag()->add('success', 'Wish List was deleted successfully.');
        return $response->getStatusCode() === 204;
    }
}