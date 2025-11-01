<?php

namespace WahaPhp\Module;

/**
 * Module for managing WhatsApp groups
 */
class GroupsModule extends BaseModule
{
    /**
     * Get all groups
     *
     * @param string $session Session name
     * @return array List of groups
     */
    public function list(string $session): array
    {
        return $this->get("/api/{$session}/groups");
    }

    /**
     * Get count of groups
     *
     * @param string $session Session name
     * @return array Count
     */
    public function getCount(string $session): array
    {
        return $this->get("/api/{$session}/groups/count");
    }

    /**
     * Get a specific group
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @return array Group data
     */
    public function get(string $session, string $groupId): array
    {
        return $this->request('GET', "/api/{$session}/groups/{$groupId}");
    }

    /**
     * Create a new group
     *
     * @param string $session Session name
     * @param string $subject Group name
     * @param array|null $participants List of participant IDs (optional)
     * @return array Created group data
     */
    public function create(string $session, string $subject, ?array $participants = null): array
    {
        $data = ['subject' => $subject];
        if ($participants !== null) {
            $data['participants'] = $participants;
        }

        return $this->post("/api/{$session}/groups", $data);
    }

    /**
     * Leave a group
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @return array Result
     */
    public function leave(string $session, string $groupId): array
    {
        return $this->post("/api/{$session}/groups/{$groupId}/leave");
    }

    /**
     * Update group subject (name)
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @param string $subject New subject
     * @return array Result
     */
    public function updateSubject(string $session, string $groupId, string $subject): array
    {
        $data = ['subject' => $subject];
        return $this->put("/api/{$session}/groups/{$groupId}/subject", $data);
    }

    /**
     * Update group description
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @param string $description New description
     * @return array Result
     */
    public function updateDescription(string $session, string $groupId, string $description): array
    {
        $data = ['description' => $description];
        return $this->put("/api/{$session}/groups/{$groupId}/description", $data);
    }

    /**
     * Get group invite code
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @return array Invite code
     */
    public function getInviteCode(string $session, string $groupId): array
    {
        return $this->get("/api/{$session}/groups/{$groupId}/invite-code");
    }

    /**
     * Revoke group invite code
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @return array Result
     */
    public function revokeInviteCode(string $session, string $groupId): array
    {
        return $this->post("/api/{$session}/groups/{$groupId}/invite-code/revoke");
    }

    /**
     * Get group picture
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @param bool $acceptJson If true, returns JSON with base64 data
     * @return mixed Picture data
     */
    public function getPicture(string $session, string $groupId, bool $acceptJson = false): mixed
    {
        $endpoint = "/api/{$session}/groups/{$groupId}/picture";

        if ($acceptJson) {
            return $this->request('GET', $endpoint);
        }

        return $this->get($endpoint);
    }

    /**
     * Get group participants
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @return array List of participants
     */
    public function getParticipants(string $session, string $groupId): array
    {
        return $this->get("/api/{$session}/groups/{$groupId}/participants");
    }

    /**
     * Add participants to a group
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @param array $participants List of participant IDs
     * @return array Result
     */
    public function addParticipants(string $session, string $groupId, array $participants): array
    {
        $data = ['participants' => $participants];
        return $this->post("/api/{$session}/groups/{$groupId}/participants/add", $data);
    }

    /**
     * Remove participants from a group
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @param array $participants List of participant IDs
     * @return array Result
     */
    public function removeParticipants(string $session, string $groupId, array $participants): array
    {
        $data = ['participants' => $participants];
        return $this->post("/api/{$session}/groups/{$groupId}/participants/remove", $data);
    }

    /**
     * Promote participants to admin
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @param array $participants List of participant IDs
     * @return array Result
     */
    public function promoteAdmin(string $session, string $groupId, array $participants): array
    {
        $data = ['participants' => $participants];
        return $this->post("/api/{$session}/groups/{$groupId}/admin/promote", $data);
    }

    /**
     * Demote participants from admin
     *
     * @param string $session Session name
     * @param string $groupId Group ID
     * @param array $participants List of participant IDs
     * @return array Result
     */
    public function demoteAdmin(string $session, string $groupId, array $participants): array
    {
        $data = ['participants' => $participants];
        return $this->post("/api/{$session}/groups/{$groupId}/admin/demote", $data);
    }
}

