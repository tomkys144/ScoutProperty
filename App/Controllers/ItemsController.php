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
     * ItemsController constructor.
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

    protected function createItem(array $properties, string $type = 'general')
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

        }
        $DatabaseService = new DatabaseService();
        $result = $DatabaseService->write($type, $properties);
        if (!$result['SUCCESS']) {
            return array('CODE' => $result['ERROR_CODE'], 'DATA' => [$result['ERROR_MESSAGE']]);
        }
        return array('CODE' => 200, 'DATA' => []);
    }
}