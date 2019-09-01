<?php

/**
 * @OA\Get(
 *      path="/private/bills/data/{user_id}",
 *      tags={"bills"},
 *      summary="Get aggregations about user's bills.",
 *      @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(
 *           response=200,
 *           description="Bills retrieved.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Bill retrieval failed.",
 *      ),
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="Authorization",
 *      type="http",
 *      scheme="Bearer",
 *      bearerFormat="JWT",
 * ),
 *  *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("GET /private/bills/data/@user_id", function ($user_id) {
    $data = Flight::bm()->get_bill_data($user_id);
    Flight::json($data);
});

/**
 * @OA\Get(
 *      path="/private/bills/{residential_unit_id}/{provider_id}",
 *      tags={"bills"},
 *      summary="Get all bills for residential unit provider.",
 *      @OA\Parameter(
 *         name="residential_unit_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="provider_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(
 *           response=200,
 *           description="Bills retrieved.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Bill retrieval failed.",
 *      ),
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="Authorization",
 *      type="http",
 *      scheme="Bearer",
 *      bearerFormat="JWT",
 * ),
 *  *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("GET /private/bills/@residential_unit_id/@provider_id", function ($residential_unit_id, $provider_id) {
    $bills = Flight::bm()->get_bills($residential_unit_id, $provider_id);
    if ($bills) {
        Flight::json([
            "bills" => $bills,
        ]);
    } else {
        Flight::json([
            "bills" => [],
        ]);
    }
});
