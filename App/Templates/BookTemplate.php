<?php


namespace App\Templates;


class BookTemplate extends Template
{
    protected array $requiredProperties = array(
        'name' => 'string',
        'location' => 'string'
    );

    protected array $optionalProperties = array(
        'isbn' => 'integer',
        'author' => 'string',
        'edition' => 'string',
        'image' => 'string'
    );
}