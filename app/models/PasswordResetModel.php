<?php
/**
 * @OA\Schema(
 * )
 */
class PasswordResetModel {
        /**
     * @OA\Property(
     * description="Verification token",
     * required=true
     * )
     * @var string
     */
    public $token;

            /**
     * @OA\Property(
     * description="Old password",
     * required=true
     * )
     * @var string
     */
    public $old_password;

            /**
     * @OA\Property(
     * description="New password",
     * required=true
     * )
     * @var string
     */
    public $new_password;

                /**
     * @OA\Property(
     * description="Repeat password",
     * required=true
     * )
     * @var string
     */
    public $re_password;

}