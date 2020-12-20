<?php


namespace App\Templates;

/**
 * Class GeneralItemTemplate
 * @package App\Templates
 */
class GeneralItemTemplate extends Template
{
    protected array $requiredProperties = array(
        'name' => 'string',
        'warehouse' => 'array'
    );

    protected array $optionalProperties = array(
        'description' => 'string',
        'image' => 'string'
    );
}