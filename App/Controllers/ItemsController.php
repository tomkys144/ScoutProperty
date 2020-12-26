<?php


namespace App\Controllers;

use App\Services\DatabaseService;
use App\Services\Skautis\SkautisPropertyService;

/**
 * Class ItemsController
 * @package App\Controllers
 */
class ItemsController
{
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
     * @param array $properties
     * @param string $type
     * @return array
     */
    protected function createItem(array $properties, string $type = 'general'): array
    {
        $DatabaseService = new DatabaseService();


        $PropertyService = new SkautisPropertyService();
        if ($properties["createWarehouse"]) {
            $result = $DatabaseService->write($type, array_merge($properties, array('WarehouseExists' => true)));
            if (!$result['SUCCESS']) {
                return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
            }
            $result = call_user_func_array(array($PropertyService, "createWarehouse"), $properties['warehouseInfo']);
            if (array_key_exists('ERROR_CODE', $result)) {
                return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
            }
            unset($properties["warehouseInfo"], $properties["createWarehouse"]);
            $properties['WarehouseID'] = $result["ID"];
        } elseif ($properties["createWarehouse"] === false) {
            $result = $DatabaseService->write($type, array_merge($properties, array('WarehouseExists' => false)));
            if (!$result['SUCCESS']) {
                return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
            }
        } else {
            $result = $DatabaseService->write($type, $properties);
            if (!$result['SUCCESS']) {
                return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
            }
        }

        $PropertyService = new SkautisPropertyService();

        $result = $PropertyService->createItem();
        call_user_func_array(array($PropertyService, "createItem"), $properties);
        return array('CODE' => 200, 'DATA' => []);
    }

    /**
     * @param string $type
     * @return array
     */
    protected function viewItems(string $type = 'general'): array
    {
        $DatabaseService = new DatabaseService();
        $result = $DatabaseService->getData($type);

        if (array_key_exists('ERROR_CODE', $result)) {
            return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
        }

        return array('CODE' => 200, 'DATA' => $result);
    }

    /**
     * @param int $id
     * @param string $DeletionDate
     * @param string $type
     * @param int|null $WarehouseID
     * @param string|null $DeletionNote
     * @return array
     */
    protected function removeItem(int $id, string $DeletionDate, string $type = 'general', int $WarehouseID = null, string $DeletionNote = null): array
    {
        $DatabaseService = new DatabaseService();
        if ($type === 'nemovitost') {
            $isWarehouse = $DatabaseService->getData('nemovitost', ['WarehouseExists'], "ID = $id");
        }
        $result = $DatabaseService->deleteData($type, $id);

        if (!$result['SUCCESS']) {
            return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
        }

        $PropertyService = new SkautisPropertyService();
        if (isset($isWarehouse) && $isWarehouse) {
            $result = $PropertyService->deleteWarehouse($WarehouseID);
            if (!$result['SUCCESS']) {
                return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
            }
        }
        if (isset($DeletionNote)) {
            $result = $PropertyService->deleteItem((array)$id, $DeletionDate, $DeletionNote);
        } else {
            $result = $PropertyService->deleteItem((array)$id, $DeletionDate);
        }

        if (!$result['SUCCESS']) {
            return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
        }

        return array('CODE' => 200, 'DATA' => []);
    }
}