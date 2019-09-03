<?php

/**
 * @OA\Get(
 *      path="/private/residential_units/{user_id}",
 *      tags={"residential_units"},
 *      summary="Get residential units by user id.",
 *  *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(
 *           response=200,
 *           description="Successfully logged in.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Log in failed.",
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
Flight::route("GET /private/residential_units/@user_id", function ($user_id) {
    $residential_units = Flight::rum()->get_residential_units($user_id);
    if($residential_units) {
        Flight::json([
            "residential_units" => $residential_units
        ]);
    } else {
        Flight::json([
            "residential_units" => []
        ]);
    }
});

/**
 * @OA\Post(
 *      path="/private/residential_units",
 *      tags={"residential_units"},
 *      summary="Create new residential unit.",
 *      @OA\RequestBody(
 *          description="Sample request body.",
 *          @OA\JsonContent(ref="#/components/schemas/ResidentialUnitModel")
 *       ),
 *      @OA\Response(
 *           response=200,
 *           description="Successfully registered.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Registration failed.",
 *      ),
 *  * @OA\SecurityScheme(
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
Flight::route("POST /private/residential_units", function() {
    $residential_unit = Flight::request()->data->getData();  
    Flight::validate(ResidentialUnitModel::class, $residential_unit);

    $success = Flight::rum()->add_residential_unit($residential_unit);
    
    if ($success) {
        Flight::json([
            "message" => "Residential unit added successfully!"
        ]);
    } else {
        Flight::json([
            "message" => "Adding of residential unit failed!"
        ]);
    }
});

/**
 * @OA\Put(
 *      path="/private/residential_units/{residential_unit_id}",
 *      tags={"residential_units"},
 *      summary="Update residential unit.",
 *      @OA\RequestBody(
 *          description="Sample request body.",
 *          @OA\JsonContent(ref="#/components/schemas/ResidentialUnitModel")
 *       ),
 *      @OA\Parameter(
 *         name="residential_unit_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *      @OA\Response(
 *           response=200,
 *           description="Successfully registered.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Registration failed.",
 *      ),
 *  * @OA\SecurityScheme(
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
Flight::route("PUT /private/residential_units/@residential_unit_id", function($residential_unit_id) {
    $residential_unit = Flight::request()->data->getData();  
    Flight::validate(ResidentialUnitModel::class, $residential_unit);

    Flight::rum()->update_residential_unit($residential_unit, $residential_unit_id);
    
    Flight::json([
        "message" => "Residential unit updated successfully!"
    ]);
});

/**
 * @OA\Delete(
 *      path="/private/residential_units/delete_provider",
 *      tags={"residential_units"},
 *      summary="Delete provider for residential unit.",
 *      @OA\Parameter(
 *         name="residential_unit_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *      @OA\Parameter(
 *         name="provider_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *      @OA\Response(
 *           response=200,
 *           description="Successfully registered.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Registration failed.",
 *      ),
 *  * @OA\SecurityScheme(
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
Flight::route("DELETE /private/residential_units/delete_provider", function() {
    Flight::rum()->delete_provider_for_residential_unit(array(
        "residential_unit_id" => Flight::request()->query->residential_unit_id,
        "provider_id" => Flight::request()->query->provider_id
    ));
    
    Flight::json([
        "message" => "Provider for residential unit successfully deleted."
    ]);
});

/**
 * @OA\Delete(
 *      path="/private/residential_units/{residential_unit_id}",
 *      tags={"residential_units"},
 *      summary="Delete residential unit.",
 *      @OA\Parameter(
 *         name="residential_unit_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *      @OA\Response(
 *           response=200,
 *           description="Successfully registered.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Registration failed.",
 *      ),
 *  * @OA\SecurityScheme(
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
Flight::route("DELETE /private/residential_units/@residential_unit_id", function($residential_unit_id) {
    Flight::rum()->delete_residential_unit($residential_unit_id);
    
    Flight::json([
        "message" => "Residential unit deleted successfully!"
    ]);
});

/**
 * @OA\Post(
 *      path="/private/residential_units/add_provider",
 *      tags={"residential_units"},
 *      summary="Create new residential unit.",
 *      @OA\RequestBody(
 *          description="Sample request body.",
 *          @OA\JsonContent(ref="#/components/schemas/ResidentialUnitProviderModel")
 *       ),
 *      @OA\Response(
 *           response=200,
 *           description="Successfully registered.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Registration failed.",
 *      ),
 *  * @OA\SecurityScheme(
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
Flight::route("POST /private/residential_units/add_provider", function() {
    $request = Flight::request()->data->getData();
    Flight::validate(ResidentialUnitProviderModel::class, $request);

    $message = Flight::rum()->add_providers_to_residential_unit($request);
    
    Flight::json([
        "message" => $message
    ]);
});