<?php
/**
 * @OA\Schema(
 * )
 */
class VerificationSMSModel {
        /**
     * @OA\Property(
     * description="Verification code sent by SMS",
     * required=true
     * )
     * @var string
     */
    public $verification_code;

            /**
     * @OA\Property(
     * description="Email of verification code",
     * required=true
     * )
     * @var string
     */
    public $email;
}