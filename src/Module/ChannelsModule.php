<?php

namespace WahaPhp\Module;

/**
 * Module for managing WhatsApp Channels
 */
class ChannelsModule extends BaseModule
{
    /**
     * List all channels
     *
     * @param string $session Session name
     * @return array List of channels
     */
    public function list(string $session): array
    {
        return $this->get("/api/{$session}/channels");
    }

    /**
     * Get a specific channel
     *
     * @param string $session Session name
     * @param string $channelId Channel ID
     * @return array Channel data
     */
    public function get(string $session, string $channelId): array
    {
        return $this->request('GET', "/api/{$session}/channels/{$channelId}");
    }

    /**
     * Create a new channel
     *
     * @param string $session Session name
     * @param string $name Channel name
     * @param string|null $description Channel description (optional)
     * @return array Created channel data
     */
    public function create(string $session, string $name, ?string $description = null): array
    {
        $data = ['name' => $name];
        if ($description !== null) {
            $data['description'] = $description;
        }

        return $this->post("/api/{$session}/channels", $data);
    }

    /**
     * Delete a channel
     *
     * @param string $session Session name
     * @param string $channelId Channel ID
     * @return array Result
     */
    public function delete(string $session, string $channelId): array
    {
        return $this->request('DELETE', "/api/{$session}/channels/{$channelId}");
    }

    /**
     * Get messages from a channel
     *
     * @param string $session Session name
     * @param string $channelId Channel ID
     * @param int|null $limit Limit number of messages
     * @return array List of messages
     */
    public function getMessages(string $session, string $channelId, ?int $limit = null): array
    {
        $params = [];
        if ($limit !== null) {
            $params['limit'] = $limit;
        }

        return $this->get("/api/{$session}/chats/{$channelId}/messages", !empty($params) ? $params : null);
    }
}

