<?php
/**
 * @OA\Schema(
 * )
 */
class BillModel
{
    /**
     * @OA\Property(
     * description="Date on which the bill was created.",
     * required=true
     * )
     * @var string
     */
    public $date;

    /**
     * @OA\Property(
     * description="Debt of user for that month.",
     * required=true
     * )
     * @var number
     */
    public $debt;

    /**
     * @OA\Property(
     * description="Value of payment by user.",
     * required=true
     * )
     * @var number
     */
    public $paid;

}
