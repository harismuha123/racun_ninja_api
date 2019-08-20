<?php

/**
 * @OA\Post(
 *      path="/private/providers",
 *      tags={"providers"},
 *      summary="Create new provider.",
 *      @OA\RequestBody(
 *          description="Sample request body.",
 *          @OA\JsonContent(ref="#/components/schemas/ProviderModel")
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
Flight::route("POST /private/providers", function() {
    $provider = Flight::request()->data->getData();  
    Flight::validate(ProviderModel::class, $provider);

    Flight::pm()->add_provider($provider);
    
    Flight::json([
        "message" => "Provider added successfully!"
    ]);
});

/**
 * @OA\Get(
 *      path="/private/providers",
 *      tags={"providers"},
 *      summary="Get all providers.",
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
Flight::route("GET /private/providers", function () {
    $providers = Flight::pm()->get_providers();
    
    Flight::json([
        "data" => [$providers]
    ]);
});

/**
 * @OA\Get(
 *      path="/private/providers/{provider_id}",
 *      tags={"providers"},
 *      summary="Get provider by id.",
 *  *     @OA\Parameter(
 *         name="provider_id",
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
Flight::route("GET /private/providers/@provider_id", function ($provider_id) {
    $provider = Flight::pm()->get_provider_by_id($provider_id);
    Flight::json([
        "data" => $provider
    ]);
});

