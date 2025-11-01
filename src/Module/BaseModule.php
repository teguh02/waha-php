<?php

namespace WahaPhp\Module;

use WahaPhp\Client;

/**
 * Base class for all WAHA modules
 * Provides common functionality for sub-modules
 */
abstract class BaseModule
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Make a request through the client
     */
    protected function request(string $method, string $endpoint, ?array $params = null, ?array $jsonData = null): mixed
    {
        return $this->client->request($method, $endpoint, $params, $jsonData);
    }

    /**
     * Make a GET request
     */
    protected function get(string $endpoint, ?array $params = null): mixed
    {
        return $this->client->get($endpoint, $params);
    }

    /**
     * Make a POST request
     */
    protected function post(string $endpoint, ?array $jsonData = null): mixed
    {
        return $this->client->post($endpoint, $jsonData);
    }

    /**
     * Make a PUT request
     */
    protected function put(string $endpoint, ?array $jsonData = null): mixed
    {
        return $this->client->put($endpoint, $jsonData);
    }

    /**
     * Make a DELETE request
     */
    protected function delete(string $endpoint): mixed
    {
        return $this->client->delete($endpoint);
    }
}

