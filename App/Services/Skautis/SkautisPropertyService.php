<?php


namespace App\Services\Skautis;


use Exception;
use JsonException;

/**
 * Class SkautisPropertyService
 * @package App\Services\Skautis
 */
class SkautisPropertyService extends SkautisService
{
    /**
     * @return array
     */
    public function getWarehouses(): array
    {
        $data = $this->skautis->Material->WarehouseAll(array('ID_Unit' => $this->skautis->getUser()->ID_Unit));
        $result = array();

        try {
            foreach (json_decode($data, true, 512, JSON_THROW_ON_ERROR) as $warehouse) {
                $result[] = array('name' => $warehouse['DisplayNameWithUnitID'], 'id' => $result['ID']);
            }
        } catch (JsonException $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
        return $result;
    }

    /**
     * @param string $name
     * @param bool $IsDefault default false
     * @param int|null $IDWarehouseMain
     * @param string|null $Street
     * @param string|null $City
     * @param int|null $Postcode
     * @param float|null $GpsLatitude
     * @param float|null $GpsLongitude
     * @return array
     */
    public function createWarehouse(
        string $name,
        bool $IsDefault = false, int $IDWarehouseMain = null, string $Street = null, string $City = null, int $Postcode = null, float $GpsLatitude = null, float $GpsLongitude = null
    ): array
    {
        $params = array('DisplayName' => $name, 'IsDefault' => $IsDefault);

        if ($IDWarehouseMain) {
            $params['ID_WarehouseMain'] = $IDWarehouseMain;
        }

        if ($Street) {
            $params['Street'] = $Street;
        }

        if ($City) {
            $params['City'] = $City;
        }

        if ($Postcode) {
            $params['Postcode'] = $Postcode;
        }

        if ($GpsLatitude && $GpsLongitude) {
            $params = array_merge($params, array('GPSLongitude' => $GpsLongitude, 'GPSLatitude' => $GpsLatitude));
        }

        try {
            return $this->skautis->Material->WarehouseInsert($params);
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    public function editWarehouse()
    {
        //TODO
    }

    public function deleteWarehouse()
    {
        //TODO
    }

    /**
     * @param array $ID_Warehouses
     * @param bool $IncludeChild
     * @return array
     */
    public function getItemsWarehouse(array $ID_Warehouses, bool $IncludeChild = true): array
    {
        try {
            return $this->skautis->Material->WarehouseItemAll(array("ID_Unit" => $this->skautis->getUser()->getUnitId(), "ID_WarehouseArray" => $ID_Warehouses, "IncludeChild" => $IncludeChild));
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    public function getItemsUnit(bool $IncludeChild = true): array
    {
        try {
            return $this->skautis->Material->WarehouseItemAll(array("ID_Unit" => $this->skautis->getUser()->getUnitId(), "IncludeChild" => $IncludeChild));
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    public function createItem()
    {
        //TODO
    }

    public function editItem()
    {
        //TODO
    }

    public function deleteItem()
    {
        //TODO
    }
}