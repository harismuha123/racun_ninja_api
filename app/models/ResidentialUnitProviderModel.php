<?php

/**
 * @OA\Schema(
 * )
 */
class ResidentialUnitProviderModel
{
    /**
     * @OA\Property(
     * description="Name of service provider.",
     * required=true
     * )
     * @var string
     */
    public $user_id;

    /**
     * @OA\Property(
     * description="URL of company logo, will probably be hosted on a CDN.",
     * required=true
     * )
     * @var string
     */
    public $residential_unit_id;

    /**
     * @OA\Property(
     * description="URL of company logo, will probably be hosted on a CDN.",
     * required=true
     * )
     * @var string[]
     */
    public $providers;

    /**
     * @OA\Property(
     * description="URL of company logo, will probably be hosted on a CDN.",
     * required=true
     * )
     * @var string[]
     */
    public $credentials;
}
