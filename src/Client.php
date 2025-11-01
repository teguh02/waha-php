<?php

namespace WahaPhp;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use WahaPhp\Exception\WahaException;
use WahaPhp\Exception\WahaAuthenticationException;
use WahaPhp\Exception\WahaNotFoundException;
use WahaPhp\Exception\WahaRateLimitException;
use WahaPhp\Exception\WahaServerException;
use WahaPhp\Module\SessionsModule;
use WahaPhp\Module\MessagesModule;
use WahaPhp\Module\ChatsModule;
use WahaPhp\Module\ContactsModule;
use WahaPhp\Module\GroupsModule;
use WahaPhp\Module\StatusModule;
use WahaPhp\Module\ProfileModule;
use WahaPhp\Module\ChannelsModule;

/**
 * WAHA (WhatsApp HTTP API) PHP Client
 *
 * This is the main client class that provides a high-level interface
 * to interact with the WAHA server.
 *
 * Example:
 * <code>
 * $client = new Client('http://localhost:3000', 'your-api-key');
 *
 * // Send a text message
 * $result = $client->messages()->sendText(
 *     'default',
 *     '1234567890@c.us',
 *     'Hello, World!'
 * );
 * </code>
 */
class Client
{
    protected string $baseUrl;
    private ?string $apiKey;
    private int $timeout;
    private GuzzleClient $httpClient;

    public SessionsModule $sessions;
    public MessagesModule $messages;
    public ChatsModule $chats;
    public ContactsModule $contacts;
    public GroupsModule $groups;
    public StatusModule $status;
    public ProfileModule $profile;
    public ChannelsModule $channels;

    /**
     * Initialize the WAHA client
     *
     * @param string $baseUrl Base URL of the WAHA server
     * @param string|null $apiKey API key for authentication
     * @param int $timeout Request timeout in seconds
     */
    public function __construct(string $baseUrl = 'http://localhost:3000', ?string $apiKey = null, int $timeout = 30)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->timeout = $timeout;

        $this->setupHttpClient();

        // Initialize sub-modules
        $this->sessions = new SessionsModule($this);
        $this->messages = new MessagesModule($this);
        $this->chats = new ChatsModule($this);
        $this->contacts = new ContactsModule($this);
        $this->groups = new GroupsModule($this);
        $this->status = new StatusModule($this);
        $this->profile = new ProfileModule($this);
        $this->channels = new ChannelsModule($this);
    }

    private function setupHttpClient(): void
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($this->apiKey) {
            $headers['X-Api-Key'] = $this->apiKey;
        }

        $this->httpClient = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'headers' => $headers,
        ]);
    }

    /**
     * Make a request to the WAHA API
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $endpoint API endpoint (e.g., "/api/sessions")
     * @param array|null $params URL parameters
     * @param array|null $jsonData JSON body data
     * @return mixed Response data
     * @throws WahaAuthenticationException If authentication fails
     * @throws WahaNotFoundException If resource is not found
     * @throws WahaRateLimitException If rate limit is exceeded
     * @throws WahaServerException If server returns an error
     * @throws WahaException For other errors
     */
    public function request(string $method, string $endpoint, ?array $params = null, ?array $jsonData = null): mixed
    {
        try {
            $options = [];

            if ($params !== null) {
                $options['query'] = $params;
            }

            if ($jsonData !== null) {
                $options['json'] = $jsonData;
            }

            $response = $this->httpClient->request($method, $endpoint, $options);
            return $this->handleResponse($response);

        } catch (GuzzleException $e) {
            throw new WahaException("Request failed: " . $e->getMessage());
        }
    }

    private function handleResponse($response): mixed
    {
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeader('Content-Type')[0] ?? '';

        // Handle different status codes
        if ($statusCode === 401) {
            throw new WahaAuthenticationException(
                "Authentication failed. Please check your API key."
            );
        } elseif ($statusCode === 404) {
            throw new WahaNotFoundException("Resource not found");
        } elseif ($statusCode === 429) {
            throw new WahaRateLimitException("Rate limit exceeded. Please try again later.");
        } elseif ($statusCode >= 500) {
            $errorMsg = "Server error";
            try {
                $errorData = json_decode($response->getBody()->getContents(), true);
                $errorMsg = $errorData['message'] ?? $errorMsg;
            } catch (\Exception $e) {
                // Ignore
            }
            throw new WahaServerException("$errorMsg (Status: $statusCode)");
        }

        // Handle successful responses
        if ($statusCode === 200 || $statusCode === 201 || $statusCode === 204) {
            if (str_contains($contentType, 'application/json')) {
                return json_decode($response->getBody()->getContents(), true);
            } elseif (str_contains($contentType, 'image/') || str_contains($contentType, 'application/octet-stream')) {
                return $response->getBody()->getContents();
            } else {
                return $response->getBody()->getContents();
            }
        }

        // Handle other error codes
        if ($statusCode >= 400) {
            $errorMsg = "Unknown error";
            try {
                $errorData = json_decode($response->getBody()->getContents(), true);
                $errorMsg = $errorData['message'] ?? $errorMsg;
            } catch (\Exception $e) {
                $errorMsg = $response->getBody()->getContents();
            }
            throw new WahaException("$errorMsg (Status: $statusCode)");
        }

        return $response->getBody()->getContents();
    }

    /**
     * Make a GET request
     */
    public function get(string $endpoint, ?array $params = null): mixed
    {
        return $this->request('GET', $endpoint, $params);
    }

    /**
     * Make a POST request
     */
    public function post(string $endpoint, ?array $jsonData = null): mixed
    {
        return $this->request('POST', $endpoint, null, $jsonData);
    }

    /**
     * Make a PUT request
     */
    public function put(string $endpoint, ?array $jsonData = null): mixed
    {
        return $this->request('PUT', $endpoint, null, $jsonData);
    }

    /**
     * Make a DELETE request
     */
    public function delete(string $endpoint): mixed
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Allow accessing modules as methods for backward compatibility
     */
    public function sessions(): SessionsModule
    {
        return $this->sessions;
    }

    public function messages(): MessagesModule
    {
        return $this->messages;
    }

    public function chats(): ChatsModule
    {
        return $this->chats;
    }

    public function contacts(): ContactsModule
    {
        return $this->contacts;
    }

    public function groups(): GroupsModule
    {
        return $this->groups;
    }

    public function status(): StatusModule
    {
        return $this->status;
    }

    public function profile(): ProfileModule
    {
        return $this->profile;
    }

    public function channels(): ChannelsModule
    {
        return $this->channels;
    }
}

