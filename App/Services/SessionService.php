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
     * @param false $sessionID
     * @return array
     */
    public function startSession($sessionID = false): array
    {
        if ($sessionID) {
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
                return array(200, session_id());
            } catch (Exception $e) {
                return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
            }
        } else {
            return array(400);
        }
    }

}