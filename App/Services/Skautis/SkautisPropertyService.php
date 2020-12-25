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


        try {
            $data = $this->skautis->Material->WarehouseAll(array('ID_Unit' => $this->skautis->getUser()->ID_Unit));
            $result = array();
            foreach (json_decode($data, true, 512, JSON_THROW_ON_ERROR) as $warehouse) {
                $result[] = array('name' => $warehouse['DisplayNameWithUnitID'], 'id' => $result['ID']);
            }
            return $result;
        } catch (JsonException | Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    /**
     * @param string $Name
     * @param bool $IsDefault default false
     * @param bool $CanUpdate default true
     * @param int|null $IDWarehouseMain default null
     * @param string|null $Street default null
     * @param string|null $City default null
     * @param int|null $Postcode default null
     * @param float|null $GpsLatitude default null
     * @param float|null $GpsLongitude default null
     * @return array
     */
    public function createWarehouse(
        string $Name,
        bool $IsDefault = false, bool $CanUpdate = true, int $IDWarehouseMain = null, string $Street = null, string $City = null, int $Postcode = null, float $GpsLatitude = null, float $GpsLongitude = null
    ): array
    {
        $params = array('DisplayName' => $Name, 'IsDefault' => $IsDefault, 'CanUpdate' => $CanUpdate);

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
            return json_decode($this->skautis->Material->WarehouseInsert($params), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException | Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    /**
     * @param int $WarehouseID
     * @param string|null $Name
     * @param bool|null $IsDefault
     * @param bool|null $CanUpdate
     * @param int|null $IDWarehouseMain
     * @param string|null $Street
     * @param string|null $City
     * @param int|null $Postcode
     * @param float|null $GpsLatitude
     * @param float|null $GpsLongitude
     * @return array|bool[]
     */
    public function editWarehouse(
        int $WarehouseID,
        string $Name = null, bool $IsDefault = null, bool $CanUpdate = null, int $IDWarehouseMain = null, string $Street = null, string $City = null, int $Postcode = null, float $GpsLatitude = null, float $GpsLongitude = null
    ): array
    {
        $params = array('ID' => $WarehouseID);

        if ($Name) {
            $params['DisplayName'] = $Name;
        }

        if ($IsDefault) {
            $params['IsDefault'] = $IsDefault;
        }

        if ($CanUpdate) {
            $params['CanUpdate'] = $CanUpdate;
        }

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
            $this->skautis->Material->WarehouseUpdate($params, "warehouse");
            return array('SUCCESS' => true);
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    /**
     * @param int $WarehouseID
     * @return array|bool[]
     */
    public function deleteWarehouse(int $WarehouseID): array
    {
        try {
            $this->skautis->Material->WarehouseDelete(array('ID' => $WarehouseID));
            return array('SUCCESS' => true);
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    /**
     * @param array $ID_Warehouses
     * @param bool $IncludeChild
     * @return array
     */
    public function getItemsWarehouse(array $ID_Warehouses, bool $IncludeChild = true): array
    {
        try {
            return json_decode($this->skautis->Material->WarehouseItemAll(array("ID_Unit" => $this->skautis->getUser()->getUnitId(), "ID_WarehouseArray" => $ID_Warehouses, "IncludeChild" => $IncludeChild)), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException | Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    /**
     * @param bool $IncludeChild
     * @return array
     */
    public function getItemsUnit(bool $IncludeChild = true): array
    {
        try {
            return json_decode($this->skautis->Material->WarehouseItemAll(array("ID_Unit" => $this->skautis->getUser()->getUnitId(), "IncludeChild" => $IncludeChild)), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException | Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    /**
     * @param string $Name
     * @param int $WarehouseID
     * @param string $IDItemCategory
     * @param string $Description
     * @param string|null $ID_PurchaseType
     * @param string|null $PurchaseDate
     * @param int|null $PurchaseYear
     * @param int|null $PurchasePrice
     * @param int|null $ActualPrice
     * @return array
     */
    public function createItem(
        string $Name, int $WarehouseID, string $IDItemCategory,
        string $Description = null, string $ID_PurchaseType = null, string $PurchaseDate = null, int $PurchaseYear = null, int $PurchasePrice = null, int $ActualPrice = null
    ): array
    {
        $params = array('ID' => 0, 'DisplayName' => $Name, 'ID_Warehouse' => $WarehouseID, 'ID_WarehouseItemCategory' => $IDItemCategory);

        if ($Description) {
            $params['Description'] = $Description;
        }

        if ($ID_PurchaseType) {
            $params['ID_PurchaseType'] = $Description;
        }

        if ($PurchaseDate) {
            $params['PurchaseDate'] = $PurchaseDate;
        }

        if ($PurchaseYear) {
            $params['PurchaseYear'] = $PurchaseYear;
        }

        if ($PurchasePrice) {
            $params['PurchasePrice'] = $PurchasePrice;
        }

        if ($ActualPrice) {
            $params['ActualPrice'] = $ActualPrice;
        }

        try {
            return json_decode($this->skautis->Material->WarehouseItemInsert($params, "warehouseItem"), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException | Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    public function editItem(
        int $ID,
        string $Name = null, int $WarehouseID = null, string $IDItemCategory = null, string $Description = null, string $ID_PurchaseType = null, string $PurchaseDate = null, int $PurchaseYear = null, int $PurchasePrice = null, int $ActualPrice = null
    ): array
    {
        $params = array('ID' => $ID,);

        if ($Name) {
            $params['DisplayName'] = $Name;
        }

        if ($WarehouseID) {
            $params['ID_Warehouse'] = $WarehouseID;
        }

        if ($IDItemCategory) {
            $params['ID_WarehouseItemCategory'] = $IDItemCategory;
        }

        if ($Description) {
            $params['Description'] = $Description;
        }

        if ($ID_PurchaseType) {
            $params['ID_PurchaseType'] = $Description;
        }

        if ($PurchaseDate) {
            $params['PurchaseDate'] = $PurchaseDate;
        }

        if ($PurchaseYear) {
            $params['PurchaseYear'] = $PurchaseYear;
        }

        if ($PurchasePrice) {
            $params['PurchasePrice'] = $PurchasePrice;
        }

        if ($ActualPrice) {
            $params['ActualPrice'] = $ActualPrice;
        }

        try {
            return json_decode($this->skautis->Material->WarehouseItemInsert($params, "warehouseItem"), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException | Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }

    /**
     * @param array $IDs
     * @param string $DeletionDate
     * @param string|null $DeletionNote
     * @return array
     */
    public function deleteItem(array $IDs, string $DeletionDate, string $DeletionNote = null): array
    {
        $params = array('ID' => $IDs, 'DeletionDate' => $DeletionDate);
        if ($DeletionNote) {
            $params['DeletionNote'] = $DeletionNote;
        }
        try {
            $this->skautis->Material->WarehouseItemUpdateDelete($params, "warehouseItem");
            return array('SUCCESS' => true);
        } catch (Exception $e) {
            return array('ERROR_CODE' => $e->getCode(), 'ERROR_MESSAGE' => $e->getMessage());
        }
    }
}