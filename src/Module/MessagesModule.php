<?php

namespace WahaPhp\Module;

/**
 * Module for sending and receiving WhatsApp messages
 */
class MessagesModule extends BaseModule
{
    /**
     * Send a text message
     *
     * @param string $session Session name
     * @param string $chatId Chat ID (e.g., "1234567890@c.us")
     * @param string $text Message text
     * @param string|null $replyTo Message ID to reply to
     * @param array|null $mentions List of chat IDs to mention (for groups)
     * @param bool $linkPreview Enable link preview (default: true)
     * @param bool $linkPreviewHighQuality Enable high-quality link preview (default: false)
     * @return array Message result
     */
    public function sendText(
        string $session,
        string $chatId,
        string $text,
        ?string $replyTo = null,
        ?array $mentions = null,
        bool $linkPreview = true,
        bool $linkPreviewHighQuality = false
    ): array {
        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'text' => $text,
        ];

        if ($replyTo !== null) {
            $data['reply_to'] = $replyTo;
        }
        if ($mentions !== null) {
            $data['mentions'] = $mentions;
        }
        if (!$linkPreview) {
            $data['linkPreview'] = false;
        }
        if ($linkPreviewHighQuality) {
            $data['linkPreviewHighQuality'] = true;
        }

        return $this->post('/api/sendText', $data);
    }

    /**
     * Mark message(s) as seen
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param array|null $messageIds List of message IDs to mark as seen
     * @param string|null $participant Participant ID (for group messages)
     * @return array Result
     */
    public function sendSeen(
        string $session,
        string $chatId,
        ?array $messageIds = null,
        ?string $participant = null
    ): array {
        $data = [
            'session' => $session,
            'chatId' => $chatId,
        ];

        if ($messageIds !== null) {
            $data['messageIds'] = $messageIds;
        }
        if ($participant !== null) {
            $data['participant'] = $participant;
        }

        return $this->post('/api/sendSeen', $data);
    }

    /**
     * Send an image
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param array|string $file File data (dict with url/data/mimetype/filename or file path)
     * @param string|null $caption Image caption (optional)
     * @return array Message result
     */
    public function sendImage(string $session, string $chatId, array|string $file, ?string $caption = null): array
    {
        if (is_string($file)) {
            $fileData = base64_encode(file_get_contents($file));
            $mimeType = mime_content_type($file) ?: 'image/jpeg';
            $file = ['data' => $fileData, 'mimetype' => $mimeType, 'filename' => basename($file)];
        }

        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'file' => $file,
        ];

        if ($caption !== null) {
            $data['caption'] = $caption;
        }

        return $this->post('/api/sendImage', $data);
    }

    /**
     * Send a video
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param array|string $file File data
     * @param string|null $caption Video caption (optional)
     * @param bool $asNote Send as video note (rounded video)
     * @param bool $convert Convert video to right format
     * @return array Message result
     */
    public function sendVideo(
        string $session,
        string $chatId,
        array|string $file,
        ?string $caption = null,
        bool $asNote = false,
        bool $convert = false
    ): array {
        if (is_string($file)) {
            $fileData = base64_encode(file_get_contents($file));
            $mimeType = mime_content_type($file) ?: 'video/mp4';
            $file = ['data' => $fileData, 'mimetype' => $mimeType, 'filename' => basename($file)];
        }

        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'file' => $file,
        ];

        if ($caption !== null) {
            $data['caption'] = $caption;
        }
        if ($asNote) {
            $data['asNote'] = true;
        }
        if ($convert) {
            $data['convert'] = true;
        }

        return $this->post('/api/sendVideo', $data);
    }

    /**
     * Send a voice message
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param array|string $file File data
     * @param bool $convert Convert voice to right format
     * @return array Message result
     */
    public function sendVoice(string $session, string $chatId, array|string $file, bool $convert = false): array
    {
        if (is_string($file)) {
            $fileData = base64_encode(file_get_contents($file));
            $mimeType = mime_content_type($file) ?: 'audio/ogg; codecs=opus';
            $file = ['data' => $fileData, 'mimetype' => $mimeType, 'filename' => basename($file)];
        }

        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'file' => $file,
        ];

        if ($convert) {
            $data['convert'] = true;
        }

        return $this->post('/api/sendVoice', $data);
    }

    /**
     * Send a file (document)
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param array|string $file File data
     * @param string|null $caption File caption (optional)
     * @return array Message result
     */
    public function sendFile(string $session, string $chatId, array|string $file, ?string $caption = null): array
    {
        if (is_string($file)) {
            $fileData = base64_encode(file_get_contents($file));
            $mimeType = mime_content_type($file) ?: 'application/octet-stream';
            $file = ['data' => $fileData, 'mimetype' => $mimeType, 'filename' => basename($file)];
        }

        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'file' => $file,
        ];

        if ($caption !== null) {
            $data['caption'] = $caption;
        }

        return $this->post('/api/sendFile', $data);
    }

    /**
     * Send a location
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param float $latitude Latitude
     * @param float $longitude Longitude
     * @param string|null $title Location title (optional)
     * @return array Message result
     */
    public function sendLocation(
        string $session,
        string $chatId,
        float $latitude,
        float $longitude,
        ?string $title = null
    ): array {
        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        if ($title !== null) {
            $data['title'] = $title;
        }

        return $this->post('/api/sendLocation', $data);
    }

    /**
     * Send contact(s) (vCard)
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param array $contacts List of contact dictionaries
     * @return array Message result
     */
    public function sendContact(string $session, string $chatId, array $contacts): array
    {
        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'contacts' => $contacts,
        ];

        return $this->post('/api/sendContactVcard', $data);
    }

    /**
     * Send a poll
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param array $poll Poll data (name, options, multipleAnswers)
     * @return array Message result
     */
    public function sendPoll(string $session, string $chatId, array $poll): array
    {
        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'poll' => $poll,
        ];

        return $this->post('/api/sendPoll', $data);
    }

    /**
     * Forward a message
     *
     * @param string $session Session name
     * @param string $chatId Chat ID to forward to
     * @param string $messageId Message ID to forward
     * @return array Message result
     */
    public function forwardMessage(string $session, string $chatId, string $messageId): array
    {
        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'messageId' => $messageId,
        ];

        return $this->post('/api/forwardMessage', $data);
    }

    /**
     * Add a reaction to a message
     *
     * @param string $session Session name
     * @param string $messageId Message ID
     * @param string $reaction Reaction emoji (use "" to remove)
     * @return array Result
     */
    public function addReaction(string $session, string $messageId, string $reaction): array
    {
        $data = [
            'session' => $session,
            'messageId' => $messageId,
            'reaction' => $reaction,
        ];

        return $this->put('/api/reaction', $data);
    }

    /**
     * Star or unstar a message
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param string $messageId Message ID
     * @param bool $star True to star, False to unstar
     * @return array Result
     */
    public function starMessage(string $session, string $chatId, string $messageId, bool $star = true): array
    {
        $data = [
            'session' => $session,
            'chatId' => $chatId,
            'messageId' => $messageId,
            'star' => $star,
        ];

        return $this->put('/api/star', $data);
    }

    /**
     * Edit a message
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param string $messageId Message ID
     * @param string $text New text
     * @param bool $linkPreview Enable link preview
     * @return array Result
     */
    public function editMessage(string $session, string $chatId, string $messageId, string $text, bool $linkPreview = true): array
    {
        $data = ['text' => $text];
        if (!$linkPreview) {
            $data['linkPreview'] = false;
        }

        return $this->put("/api/{$session}/chats/{$chatId}/messages/{$messageId}", $data);
    }

    /**
     * Delete a message
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param string $messageId Message ID
     * @return array Result
     */
    public function deleteMessage(string $session, string $chatId, string $messageId): array
    {
        return $this->request('DELETE', "/api/{$session}/chats/{$chatId}/messages/{$messageId}");
    }

    /**
     * Pin a message
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param string $messageId Message ID
     * @return array Result
     */
    public function pinMessage(string $session, string $chatId, string $messageId): array
    {
        return $this->post("/api/{$session}/chats/{$chatId}/messages/{$messageId}/pin");
    }

    /**
     * Unpin a message
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param string $messageId Message ID
     * @return array Result
     */
    public function unpinMessage(string $session, string $chatId, string $messageId): array
    {
        return $this->post("/api/{$session}/chats/{$chatId}/messages/{$messageId}/unpin");
    }
}

