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
        $cacheKey = 'libraries.all'; // A unique key for this cache entry
        $cacheTime = 60 * 60; // Time to cache the response (in seconds)
        $response = $this->cache->get($cacheKey, function () use ($cacheTime) {
            $result = $this->client->post('oauth/token');
            $access_token = json_decode((string)$result->getBody(), true)['access_token'];
            $response = $this->client->get('api/wishlists', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer $access_token",
                ]
            ]);
            return json_decode($response->getBody()->getContents());
        })->get();

        return $response;
    }

    public function getLibraries()
    {
        $cacheKey = 'libraries.all'; // A unique key for this cache entry
        $cacheTime = 60 * 60; // Time to cache the response (in seconds)
        $response = $this->cache->get($cacheKey, function () use ($cacheTime) {
            $result = $this->client->post('oauth/token');
            $access_token = json_decode((string)$result->getBody(), true)['access_token'];
            $response = $this->client->get('api/libraries', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer $access_token",
                ]
            ]);
            return json_decode($response->getBody()->getContents());
        })->get();

        return $response;
    }

    public function create($data)
    {
        $result = $this->client->post('oauth/token');
        $access_token = json_decode((string)$result->getBody(), true)['access_token'];
        $response = $this->client->post('api/wishlists', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $access_token",
            ],
            'form_params' => $data,
        ]);
        $this->session->getFlashBag()->add('success', 'Wish List was created successfully.');
        return $response->getStatusCode() === 201;
    }

    public function delete($id)
    {
        $result = $this->client->post('oauth/token');
        $access_token = json_decode((string)$result->getBody(), true)['access_token'];
        $response = $this->client->delete('api/wishlists/' . $id, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $access_token",
            ]
        ]);
        $this->session->getFlashBag()->add('success', 'Wish List was deleted successfully.');
        return $response->getStatusCode() === 201;
    }

}