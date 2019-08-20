<?php

/**
 * @OA\Schema(
 * )
 */
class ProviderModel
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
    public $logo;

    /**
     * @OA\Property(
     * description="Access point of provider.",
     * required=true
     * )
     * @var string
     */
    public $uri;
}
