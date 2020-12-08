<?php


namespace App\Templates;


class RealEstateTemplate extends Template
{
    protected array $requiredProperties = array(
        'name' => 'string',
        'street' => 'string',
        'house_number' => 'integer',
        'municipality' => 'string',
        'post_code' => 'integer'
    );

    protected array $optionalProperties = array(
        'flat_number' => 'integer',
        'warden' => 'string'
    );
}