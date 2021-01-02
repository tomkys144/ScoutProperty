<?php


namespace App\Services;

use Exception;

/**
 * Class SessionService
 * @package App\Services
 */
class SessionService
{
    /**
     * @param string|null $sessionID
     * @return array
     */
    public function startSession(string $sessionID = null): array
    {
        if (isset($sessionID)) {
            session_id($sessionID);
        }
        try {
            session_start();
            return array(200);
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    /**
     * @return array|int[]
     */
    public function getSessionID(): array
    {
        if ((session_status() === PHP_SESSION_ACTIVE)) {
            try {
                return array(session_id());
            } catch (Exception $e) {
                return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
            }
        } else {
            return array('ERROR_CODE' => 400);
        }
    }

}