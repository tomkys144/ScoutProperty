<?php


namespace App\Controllers;

use App\Services\DatabaseService;
use App\Templates\BookTemplate;
use App\Templates\GeneralItemTemplate;
use App\Templates\RealEstateTemplate;

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
        if ($type === 'general') {
            $template = new GeneralItemTemplate();
            $validation = $template->checkItems($properties);
            if (!$validation['valid']) {
                return array('CODE' => 400, 'DATA' => $validation['items']);
            }

        } elseif ($type === 'book') {
            $template = new BookTemplate();
            $validation = $template->checkItems($properties);
            if (!$validation['valid']) {
                return array('CODE' => 400, 'DATA' => $validation['items']);
            }

        } elseif ($type === 'real_estate') {
            $template = new RealEstateTemplate();
            $validation = $template->checkItems($properties);
            if (!$validation['valid']) {
                return array('CODE' => 400, 'DATA' => $validation['items']);
            }

        } else {
            return array('CODE' => 400, 'DATA' => ['Type does not exist']);
        }

        $DatabaseService = new DatabaseService();
        $result = $DatabaseService->write($type, $properties);

        if (!$result['SUCCESS']) {
            return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
        }

        return array('CODE' => 200, 'DATA' => []);
    }

    /**
     * @param string $type
     * @return array
     */
    protected function getPossibleProperties(string $type = 'general'): array
    {
        if ($type === 'general') {
            $template = new GeneralItemTemplate();
            return array('CODE' => 200, 'DATA' => $template->getItems());
        }

        if ($type === 'book') {
            $template = new BookTemplate();
            return array('CODE' => 200, 'DATA' => $template->getItems());
        }

        if ($type === 'real_estate') {
            $template = new RealEstateTemplate();
            return array('CODE' => 200, 'DATA' => $template->getItems());
        }

        return array('CODE' => 400, 'DATA' => ['Type does not exist']);
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
     * @param string $type
     * @return array
     */
    protected function removeItem(int $id, string $type = 'general'): array
    {
        $DatabaseService = new DatabaseService();
        $result = $DatabaseService->deleteData($type, $id);

        if (!$result['SUCCESS']) {
            return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
        }

        return array('CODE' => 200, 'DATA' => []);
    }
}