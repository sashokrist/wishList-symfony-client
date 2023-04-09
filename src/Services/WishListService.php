<?php

namespace App\Services;

class WishListService
{
    protected $client;
    protected $base_uri = 'http://wishlist.test/';
    protected $cache;

    public function __construct(SessionInterface $session)
    {
        $this->client = new Client([
            'base_uri' => $this->base_uri,
        ]);
        $this->cache = new FilesystemAdapter();
        $this->session = $session;
    }

    public function getAll()
    {
        $response = $this->client->get('api/wishlists');
        return json_decode($response->getBody()->getContents());
    }

    public function getLibraries()
    {
        $response = $this->client->get('api/libraries');
        return json_decode($response->getBody()->getContents());
    }

    public function create($data)
    {
        $response = $this->client->post('api/wishlists', [
            'form_params' => $data,
        ]);
        $this->session->getFlashBag()->add('success', 'Wish List was created successfully.');
        return $response->getStatusCode() === 201;
    }

    public function delete($id)
    {
        $response = $this->client->delete('api/wishlists/' . $id);
        $this->session->getFlashBag()->add('success', 'Wish List was deleted successfully.');
        return $response->getStatusCode() === 201;
    }

}