<?php


namespace App\Controllers;


use App\Services\SessionService;

class SessionController
{
    private SessionService $session;

    public function __construct()
    {
        $this->session = new SessionService();
    }

    /**
     * @param string|null $id
     */
    public function startSession(string $id = null): void
    {
        if (isset($_REQUEST['session_id'])) {
            $this->session->startSession($_REQUEST['session_id']);
        } else {
            $this->session->startSession();
        }
    }

    /**
     * @return array
     */
    public function getSessionID(): array
    {
        $data = $this->session->getSessionID();
        if (!array_key_exists('ERROR_CODE', $data)) {
            return array('CODE' => 200, 'DATA' => $data);
        }

        return array('CODE' => $data['ERROR_CODE'], 'DATA' => [$data['ERROR_MESSAGE']]);
    }
}