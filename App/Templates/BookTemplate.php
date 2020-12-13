<?php


namespace App\Templates;

/**
 * Class BookTemplate
 * @package App\Templates
 */
class BookTemplate extends Template
{
    protected array $requiredProperties = array(
        'name' => 'string',
        'location' => 'array'
    );

    protected array $optionalProperties = array(
        'isbn' => 'integer',
        'author' => 'string',
        'edition' => 'string',
        'image' => 'string'
    );
}