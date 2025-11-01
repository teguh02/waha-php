<?php

namespace WahaPhp\Module;

/**
 * Module for managing WAHA sessions
 * A session represents a WhatsApp account connected to WAHA
 */
class SessionsModule extends BaseModule
{
    /**
     * List all sessions
     *
     * @param bool $allSessions If true, returns all sessions including STOPPED ones
     * @return array List of session information
     */
    public function list(bool $allSessions = false): array
    {
        $params = $allSessions ? ['all' => true] : null;
        return $this->get('/api/sessions', $params);
    }

    /**
     * Get session information
     *
     * @param string $sessionName Name of the session
     * @return array Session information
     */
    public function getSession(string $sessionName): array
    {
        return $this->request('GET', "/api/sessions/{$sessionName}");
    }

    /**
     * Create a new session
     *
     * @param string|null $name Session name (optional, will be auto-generated if not provided)
     * @param array|null $config Session configuration (optional)
     * @param bool $start Whether to start the session immediately (default: true)
     * @return array Created session information
     */
    public function create(?string $name = null, ?array $config = null, bool $start = true): array
    {
        $data = [];
        if ($name !== null) {
            $data['name'] = $name;
        }
        if ($config !== null) {
            $data['config'] = $config;
        }
        if (!$start) {
            $data['start'] = false;
        }

        return $this->post('/api/sessions', $data);
    }

    /**
     * Update session configuration
     *
     * @param string $sessionName Name of the session
     * @param array $config New configuration (full config required)
     * @return array Updated session information
     */
    public function update(string $sessionName, array $config): array
    {
        $data = ['name' => $sessionName, 'config' => $config];
        return $this->request('PUT', "/api/sessions/{$sessionName}", null, $data);
    }

    /**
     * Start a session
     *
     * @param string $sessionName Name of the session
     * @return array Session information
     */
    public function start(string $sessionName): array
    {
        return $this->post("/api/sessions/{$sessionName}/start");
    }

    /**
     * Stop a session
     *
     * @param string $sessionName Name of the session
     * @return array Session information
     */
    public function stop(string $sessionName): array
    {
        return $this->post("/api/sessions/{$sessionName}/stop");
    }

    /**
     * Restart a session
     *
     * @param string $sessionName Name of the session
     * @return array Session information
     */
    public function restart(string $sessionName): array
    {
        return $this->post("/api/sessions/{$sessionName}/restart");
    }

    /**
     * Logout from a session
     *
     * @param string $sessionName Name of the session
     * @return array Logout result
     */
    public function logout(string $sessionName): array
    {
        return $this->post("/api/sessions/{$sessionName}/logout");
    }

    /**
     * Delete a session
     *
     * @param string $sessionName Name of the session
     * @return array Delete result
     */
    public function delete(string $sessionName): array
    {
        return $this->request('DELETE', "/api/sessions/{$sessionName}");
    }

    /**
     * Get information about the associated account for the session
     *
     * @param string $sessionName Name of the session
     * @return array|null Account information or null if not authenticated
     */
    public function getMe(string $sessionName): ?array
    {
        return $this->get("/api/sessions/{$sessionName}/me");
    }

    /**
     * Get QR code for pairing WhatsApp
     *
     * @param string $sessionName Name of the session
     * @param string $format QR format ('image' or 'raw')
     * @param bool $acceptJson If true, returns JSON with base64 data
     * @return mixed QR code data (binary, base64, or raw value)
     */
    public function getQr(string $sessionName, string $format = 'image', bool $acceptJson = false): mixed
    {
        $endpoint = "/api/{$sessionName}/auth/qr";
        $params = ['format' => $format];

        if ($acceptJson || $format === 'raw') {
            return $this->request('GET', $endpoint, $params);
        }

        return $this->request('GET', $endpoint, $params);
    }

    /**
     * Request authentication code for pairing
     *
     * @param string $sessionName Name of the session
     * @param string $phoneNumber Phone number to pair with
     * @return array Pairing code information
     */
    public function requestCode(string $sessionName, string $phoneNumber): array
    {
        $data = ['phoneNumber' => $phoneNumber];
        return $this->post("/api/{$sessionName}/auth/request-code", $data);
    }

    /**
     * Get screenshot of the session
     *
     * @param string $sessionName Name of the session
     * @param bool $acceptJson If true, returns JSON with base64 data
     * @return mixed Screenshot data (binary or base64)
     */
    public function getScreenshot(string $sessionName, bool $acceptJson = false): mixed
    {
        $endpoint = '/api/screenshot';
        $params = ['session' => $sessionName];

        if ($acceptJson) {
            return $this->request('GET', $endpoint, $params);
        }

        return $this->request('GET', $endpoint, $params);
    }
}

