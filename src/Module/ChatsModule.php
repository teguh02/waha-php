<?php

namespace WahaPhp\Module;

/**
 * Module for managing WhatsApp chats
 */
class ChatsModule extends BaseModule
{
    /**
     * Get all chats
     *
     * @param string $session Session name
     * @param int|null $limit Limit number of results
     * @param int|null $offset Skip number of results
     * @return array List of chats
     */
    public function list(string $session, ?int $limit = null, ?int $offset = null): array
    {
        $params = [];
        if ($limit !== null) {
            $params['limit'] = $limit;
        }
        if ($offset !== null) {
            $params['offset'] = $offset;
        }

        return $this->get("/api/{$session}/chats", !empty($params) ? $params : null);
    }

    /**
     * Get chats overview
     *
     * @param string $session Session name
     * @return array Chats overview
     */
    public function getOverview(string $session): array
    {
        return $this->get("/api/{$session}/chats/overview");
    }

    /**
     * Get chat picture
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param bool $acceptJson If true, returns JSON with base64 data
     * @return mixed Picture data
     */
    public function getPicture(string $session, string $chatId, bool $acceptJson = false): mixed
    {
        $endpoint = "/api/{$session}/chats/{$chatId}/picture";

        if ($acceptJson) {
            return $this->request('GET', $endpoint);
        }

        return $this->get($endpoint);
    }

    /**
     * Mark chat as unread
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @return array Result
     */
    public function unread(string $session, string $chatId): array
    {
        return $this->post("/api/{$session}/chats/{$chatId}/unread");
    }

    /**
     * Archive a chat
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @return array Result
     */
    public function archive(string $session, string $chatId): array
    {
        return $this->post("/api/{$session}/chats/{$chatId}/archive");
    }

    /**
     * Unarchive a chat
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @return array Result
     */
    public function unarchive(string $session, string $chatId): array
    {
        return $this->post("/api/{$session}/chats/{$chatId}/unarchive");
    }

    /**
     * Delete a chat
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @return array Result
     */
    public function delete(string $session, string $chatId): array
    {
        return $this->request('DELETE', "/api/{$session}/chats/{$chatId}");
    }

    /**
     * Read messages in a chat
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param array|null $messageIds Optional list of message IDs to read
     * @return array Result
     */
    public function readMessages(string $session, string $chatId, ?array $messageIds = null): array
    {
        $data = [];
        if ($messageIds !== null) {
            $data['messageIds'] = $messageIds;
        }

        return $this->post("/api/{$session}/chats/{$chatId}/messages/read", $data);
    }

    /**
     * Get messages from a chat
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param int|null $limit Limit number of messages
     * @param bool $downloadMedia Download media files
     * @return array List of messages
     */
    public function getMessages(string $session, string $chatId, ?int $limit = null, bool $downloadMedia = false): array
    {
        $params = [];
        if ($limit !== null) {
            $params['limit'] = $limit;
        }
        if ($downloadMedia) {
            $params['downloadMedia'] = true;
        }

        return $this->get("/api/{$session}/chats/{$chatId}/messages", !empty($params) ? $params : null);
    }

    /**
     * Get a specific message by ID
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param string $messageId Message ID
     * @param bool $downloadMedia Download media file
     * @return array Message data
     */
    public function getMessage(string $session, string $chatId, string $messageId, bool $downloadMedia = false): array
    {
        $params = [];
        if ($downloadMedia) {
            $params['downloadMedia'] = true;
        }

        return $this->get("/api/{$session}/chats/{$chatId}/messages/{$messageId}", !empty($params) ? $params : null);
    }
}

