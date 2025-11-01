<?php

namespace WahaPhp\Module;

/**
 * Module for managing WhatsApp Status (Stories)
 */
class StatusModule extends BaseModule
{
    /**
     * Send a text status
     *
     * @param string $session Session name
     * @param string $text Status text
     * @return array Status result
     */
    public function sendText(string $session, string $text): array
    {
        $data = ['text' => $text];
        return $this->post("/api/{$session}/status/text", $data);
    }

    /**
     * Send an image status
     *
     * @param string $session Session name
     * @param array|string $file File data
     * @param string|null $caption Image caption (optional)
     * @return array Status result
     */
    public function sendImage(string $session, array|string $file, ?string $caption = null): array
    {
        if (is_string($file)) {
            $fileData = base64_encode(file_get_contents($file));
            $mimeType = mime_content_type($file) ?: 'image/jpeg';
            $file = ['data' => $fileData, 'mimetype' => $mimeType, 'filename' => basename($file)];
        }

        $data = ['file' => $file];
        if ($caption !== null) {
            $data['caption'] = $caption;
        }

        return $this->post("/api/{$session}/status/image", $data);
    }

    /**
     * Send a voice status
     *
     * @param string $session Session name
     * @param array|string $file File data
     * @return array Status result
     */
    public function sendVoice(string $session, array|string $file): array
    {
        if (is_string($file)) {
            $fileData = base64_encode(file_get_contents($file));
            $mimeType = mime_content_type($file) ?: 'audio/ogg; codecs=opus';
            $file = ['data' => $fileData, 'mimetype' => $mimeType, 'filename' => basename($file)];
        }

        $data = ['file' => $file];
        return $this->post("/api/{$session}/status/voice", $data);
    }

    /**
     * Send a video status
     *
     * @param string $session Session name
     * @param array|string $file File data
     * @param string|null $caption Video caption (optional)
     * @return array Status result
     */
    public function sendVideo(string $session, array|string $file, ?string $caption = null): array
    {
        if (is_string($file)) {
            $fileData = base64_encode(file_get_contents($file));
            $mimeType = mime_content_type($file) ?: 'video/mp4';
            $file = ['data' => $fileData, 'mimetype' => $mimeType, 'filename' => basename($file)];
        }

        $data = ['file' => $file];
        if ($caption !== null) {
            $data['caption'] = $caption;
        }

        return $this->post("/api/{$session}/status/video", $data);
    }

    /**
     * Delete a status
     *
     * @param string $session Session name
     * @param string $messageId Status message ID
     * @return array Result
     */
    public function delete(string $session, string $messageId): array
    {
        $data = ['messageId' => $messageId];
        return $this->post("/api/{$session}/status/delete", $data);
    }

    /**
     * Get new status message ID
     *
     * @param string $session Session name
     * @return array New message ID
     */
    public function getNewMessageId(string $session): array
    {
        return $this->get("/api/{$session}/status/new-message-id");
    }
}

