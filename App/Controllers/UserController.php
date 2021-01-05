<?php


namespace App\Controllers;

use App\Services\Skautis\SkautisPersonService;
use App\Services\Skautis\SkautisService;
use Exception;

require dirname(__DIR__) . '/../bootstrap.php';

/**
 * Class UserController
 * @package App\Controllers
 */
class UserController
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return array
     */
    public function __call(string $name, array $arguments): array
    {
        if (method_exists($this, $name)) {
            return call_user_func_array(array($this, $name), $arguments);
        }

        return array('CODE' => 400, 'DATA' => []);
    }

    /**
     * @param bool $response
     * @param array $data
     * @return array
     */
    protected function login(bool $response = false, array $data = []): array
    {
        try {
            $skautisService = new SkautisService();
            if (!$response) {
                $url = $skautisService->getLoginUrl('https://' . $_SERVER['HTTP_HOST'] . '/confirm_login');
                return array('CODE' => 200, 'DATA' => [$url]);
            }

            $skautisService->confirmLogin($data);
            return array('CODE' => 200, 'DATA' => []);
        } catch (Exception $e) {
            return array('CODE' => $e->getCode(), 'DATA' => [$e->getMessage()]);
        }
    }

    /**
     * @return array
     */
    protected function logout(): array
    {
        try {
            $skautisService = new SkautisService();
            $url = $skautisService->getLogoutUrl();
            return array('CODE' => 200, 'DATA' => [$url]);
        } catch (Exception $e) {
            return array('CODE' => $e->getCode(), 'DATA' => [$e->getMessage()]);
        }
    }

    /**
     * @return array
     */
    protected function getRoles(): array
    {
        try {
            $personService = new SkautisPersonService();
            $user = $personService->getUserDetail();
            if (!isset($user['ERROR_CODE'])) {
                $data = $personService->getPersonRoles($user);
                if (!isset($data['ERROR_CODE'])) {
                    return array('CODE' => 200, 'DATA' => $data);
                }

                return array('CODE' => $data['ERROR_CODE'], 'DATA' => [$data['ERROR_MESSAGE']]);
            }

            return array('CODE' => $user['ERROR_CODE'], 'DATA' => [$user['ERROR_MESSAGE']]);

        } catch (Exception $e) {
            return array('CODE' => $e->getCode(), 'DATA' => [$e->getMessage()]);
        }
    }

    /**
     * @param int $RoleID
     * @return array
     */
    protected function switchRoles(int $RoleID): array
    {
        try {
            $personService = new SkautisPersonService();
            $data = $personService->switchPersonRoles($RoleID);
            if (!isset($data['ERROR_CODE'])) {
                return array('CODE' => 200, 'DATA' => $data);
            }

            return array('CODE' => $data['ERROR_CODE'], 'DATA' => [$data['ERROR_MESSAGE']]);
        } catch (Exception $e) {
            return array('CODE' => $e->getCode(), 'DATA' => [$e->getMessage()]);
        }
    }
}