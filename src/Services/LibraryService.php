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
        $response = $this->client->request('GET', $this->base_uri.'api/libraries');
        return json_decode($response->getBody()->getContents());
    }

    public function create($data)
    {
        $response = $this->client->request('POST', $this->base_uri.'api/libraries', [
            'json' => $data,
        ]);
        $this->session->getFlashBag()->add('success', 'Wish List was created successfully.');
        return $response->getStatusCode() === 201;
    }

    public function delete($id)
    {
        $response = $this->client->request('DELETE', $this->base_uri.'api/libraries/'.$id, [
        ]);
        $this->session->getFlashBag()->add('success', 'Wish List was deleted successfully.');
        return $response->getStatusCode() === 204;
    }
}