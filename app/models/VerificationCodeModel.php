<?php
/**
 * @OA\Schema(
 * )
 */
class VerificationCodeModel {
        /**
     * @OA\Property(
     * description="Verification code created by OTP",
     * required=true
     * )
     * @var string
     */
    public $verification_code;

            /**
     * @OA\Property(
     * description="Email of user",
     * required=true
     * )
     * @var string
     */
    public $email;
}