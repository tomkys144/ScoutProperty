<?php


namespace App\Services\Skautis;


use Exception;
use JsonException;

/**
 * Class SkautisPersonService
 * @package App\Services\Skautis
 */
class SkautisPersonService extends SkautisService
{
    /**
     * @return array
     */
    public function getUserDetail(): array
    {
        try {
            $data = $this->skautis->user->UserDetail();
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
        return array("ID" => $data["ID"], "Person" => $data["Person"]);
    }

    /**
     * @param array $user
     * @return array
     */
    public function getPersonRoles(array $user): array
    {
        try {
            $data = $this->skautis->user->userRoleAll(array("ID_User" => $user['ID']));
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
        $result = array();
        foreach ($data as $role) {
            $result[] = array("ID_Role" => $role["ID_Role"], "DisplayName" => $role["Displayname"]);
        }
        return $result;
    }

    public function switchPersonRoles(int $RoleID): array
    {
        try {
            $user = $this->skautis->getUser();
            $this->skautis->getUser()->updateLoginData(null, $RoleID, $user->ID_Unit);
            return array('SUCCESS' => true);
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

}