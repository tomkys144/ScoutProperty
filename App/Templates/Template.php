<?php


namespace App\Templates;

/**
 * Class Template
 * @package App\Templates
 */
class Template
{
    protected array $requiredProperties;
    protected array $optionalProperties;

    /**
     * @param array $items
     * @return array
     */
    public function checkItems(array $items): array
    {
        $invalidItems = array();
        $missingItems = array();
        foreach (array_keys($items) as $key) {
            if (isset($this->requiredProperties[$key])) {
                if (gettype($items[$key]) !== $this->requiredProperties[$key]) {
                    $invalidItems[$key] = 'Invalid property type';
                }
                continue;
            }

            if (isset($key, $this->optionalProperties)) {
                if (gettype($items[$key]) !== $this->optionalProperties[$key]) {
                    $invalidItems[$key] = 'Invalid property type';
                }
                continue;
            }

            $invalidItems[$key] = 'Invalid property name';
        }
        foreach (array_keys($this->requiredProperties) as $key) {
            if (!isset($items[$key])) {
                $missingItems[$key] = 'Item is missing';
            }
        }

        if (!empty(array_merge($invalidItems, $missingItems))) {
            return array('valid' => false, 'items' => array_merge($invalidItems, $missingItems));
        }
        return array('valid' => true);

    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return array('required' => $this->requiredProperties, 'optional' => $this->optionalProperties);
    }
}