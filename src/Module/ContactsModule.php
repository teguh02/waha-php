<?php

namespace WahaPhp\Module;

/**
 * Module for managing WhatsApp contacts
 */
class ContactsModule extends BaseModule
{
    /**
     * Get all contacts
     *
     * @param string $session Session name
     * @param int|null $limit Limit number of results
     * @param int|null $offset Skip number of results
     * @param string|null $sortBy Sort by field (id, name)
     * @param string|null $sortOrder Sort order (asc, desc)
     * @return array List of contacts
     */
    public function listAll(
        string $session,
        ?int $limit = null,
        ?int $offset = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ): array {
        $params = ['session' => $session];
        if ($limit !== null) {
            $params['limit'] = $limit;
        }
        if ($offset !== null) {
            $params['offset'] = $offset;
        }
        if ($sortBy !== null) {
            $params['sortBy'] = $sortBy;
        }
        if ($sortOrder !== null) {
            $params['sortOrder'] = $sortOrder;
        }

        return $this->get('/api/contacts/all', $params);
    }

    /**
     * Get a specific contact
     *
     * @param string $session Session name
     * @param string $contactId Contact ID (phone number or chat ID)
     * @return array Contact data
     */
    public function getContact(string $session, string $contactId): array
    {
        $params = ['session' => $session, 'contactId' => $contactId];
        return $this->request('GET', '/api/contacts', $params);
    }

    /**
     * Update a contact
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @param string $firstName First name
     * @param string $lastName Last name
     * @return array Result
     */
    public function update(string $session, string $chatId, string $firstName, string $lastName): array
    {
        $data = ['firstName' => $firstName, 'lastName' => $lastName];
        return $this->put("/api/{$session}/contacts/{$chatId}", $data);
    }

    /**
     * Check if a phone number exists in WhatsApp
     *
     * @param string $session Session name
     * @param string $phone Phone number
     * @return array Result with numberExists and chatId fields
     */
    public function checkExists(string $session, string $phone): array
    {
        $params = ['session' => $session, 'phone' => $phone];
        return $this->get('/api/contacts/check-exists', $params);
    }

    /**
     * Get contact's "about" information
     *
     * @param string $session Session name
     * @param string $contactId Contact ID
     * @return array About information
     */
    public function getAbout(string $session, string $contactId): array
    {
        $params = ['session' => $session, 'contactId' => $contactId];
        return $this->get('/api/contacts/about', $params);
    }

    /**
     * Get contact's profile picture
     *
     * @param string $session Session name
     * @param string $contactId Contact ID
     * @param bool $refresh Force refresh the picture
     * @return array Profile picture URL
     */
    public function getProfilePicture(string $session, string $contactId, bool $refresh = false): array
    {
        $params = ['session' => $session, 'contactId' => $contactId];
        if ($refresh) {
            $params['refresh'] = true;
        }
        return $this->get('/api/contacts/profile-picture', $params);
    }

    /**
     * Block a contact
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @return array Result
     */
    public function block(string $session, string $chatId): array
    {
        $data = ['session' => $session, 'chatId' => $chatId];
        return $this->post('/api/contacts/block', $data);
    }

    /**
     * Unblock a contact
     *
     * @param string $session Session name
     * @param string $chatId Chat ID
     * @return array Result
     */
    public function unblock(string $session, string $chatId): array
    {
        $data = ['session' => $session, 'chatId' => $chatId];
        return $this->post('/api/contacts/unblock', $data);
    }
}

