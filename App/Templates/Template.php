<?php


namespace App\Templates;


class Template
{
    protected array $requiredProperties;
    protected array $optionalProperties;

    /**
     * @param array $items
     * @return array|bool[]
     */
    public function checkItems(array $items): array
    {
        $invalidItems = array();
        $missingItems = array();
        foreach (array_keys($items) as $key) {
            if (array_key_exists($key, $this->requiredProperties)) {
                if (gettype($items[$key]) !== $this->requiredProperties[$key]) {
                    $invalidItems[$key] = 'Invalid property type';
                }
                continue;
            }

            if (array_key_exists($key, $this->optionalProperties)) {
                if (gettype($items[$key]) !== $this->optionalProperties[$key]) {
                    $invalidItems[$key] = 'Invalid property type';
                }
                continue;
            }

            $invalidItems[$key] = 'Invalid property name';
        }
        foreach (array_keys($this->requiredProperties) as $key) {
            if (!array_key_exists($key, $items)) {
                $missingItems[$key] = 'Item is missing';
            }
        }

        if (!empty(array_merge($invalidItems, $missingItems))) {
            return array('valid' => false, 'items' => array_merge($invalidItems, $missingItems));
        }
        return array('valid' => true);

    }
}