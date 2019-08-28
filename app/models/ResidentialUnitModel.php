<?php

/**
 * @OA\Schema(
 * )
 */
class ResidentialUnitModel
{
    /**
     * @OA\Property(
     * description="Name of service provider.",
     * required=true
     * )
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     * description="URL of company logo, will probably be hosted on a CDN.",
     * required=true
     * )
     * @var string
     */
    public $address;
}
