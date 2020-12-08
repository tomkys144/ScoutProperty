<?php


namespace App\Templates;


class GeneralItemTemplate extends Template
{
    protected array $requiredProperties = array(
        'name' => 'string',
        'location' => 'string'
    );

    protected array $optionalProperties = array(
        'description' => 'string',
        'image' => 'string'
    );
}