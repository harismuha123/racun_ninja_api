<?php

/**
 * @OA\Schema(
 * )
 */
class RegisterModel {
    /**
     * @OA\Property(
     * description="Name of user registering.",
     * required=true
     * )
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     * description="Email address of user registering.",
     * required=true
     * )
     * @var string
     */
    public $email_address;

    /**
     * @OA\Property(
     * description="Mobile phone number of user registering.",
     * required=true
     * )
     * @var string
     */
    public $mobile_number;

        /**
     * @OA\Property(
     * description="Username of user registering.",
     * required=true
     * )
     * @var string
     */
    public $username;


        /**
     * @OA\Property(
     * description="Password of user registering.",
     * required=true
     * )
     * @var string
     */
    public $password;

            /**
     * @OA\Property(
     * description="Repeated password of user registering.",
     * required=true
     * )
     * @var string
     */
    public $re_password;
}
