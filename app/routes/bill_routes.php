<?php

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
    if($bills) {
        Flight::json([
            "data" => $bills
        ]);
    } else {
        Flight::json([
            "data" => []
        ]);
    }
});

/**
 * @OA\Get(
 *      path="/private/bills/average_debt/{residential_unit_id}/{provider_id}",
 *      tags={"bills"},
 *      summary="Get average debt for residential unit provider.",
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
Flight::route("GET /private/bills/average_debt/@residential_unit_id/@provider_id", function ($residential_unit_id, $provider_id) {
    $average_debt = Flight::bm()->get_average_debt($residential_unit_id, $provider_id);
    if($average_debt) {
        Flight::json([
            "data" => $average_debt
        ]);
    } else {
        Flight::json([
            "data" => json_encode([])
        ]);
    }
});

/**
 * @OA\Get(
 *      path="/private/bills/total_debt/{residential_unit_id}/{provider_id}",
 *      tags={"bills"},
 *      summary="Get total debt for residential unit provider.",
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
Flight::route("GET /private/bills/total_debt/@residential_unit_id/@provider_id", function ($residential_unit_id, $provider_id) {
    $total_debt = Flight::bm()->get_total_debt($residential_unit_id, $provider_id);
    if($total_debt) {
        Flight::json([
            "data" => $total_debt
        ]);
    } else {
        Flight::json([
            "data" => []
        ]);
    }
});