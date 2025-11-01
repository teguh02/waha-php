<?php

namespace WahaPhp\Module;

use WahaPhp\Client;

/**
 * Module for managing WhatsApp profile
 */
class ProfileModule extends BaseModule
{
    /**
     * Get profile picture URL
     *
     * @param string $session Session name
     * @return string Profile picture URL
     */
    public function getPictureUrl(string $session): string
    {
        return $this->client->baseUrl . "/api/{$session}/profile/picture";
    }
}

