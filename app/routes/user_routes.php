<?php

/**
 * @OA\Get(
 *      path="/private/users/{email_or_username}",
 *      tags={"users"},
 *      summary="Get user by email or username.",
 *  *     @OA\Parameter(
 *         name="email_or_username",
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
Flight::route("GET /private/users/@email_or_username", function ($email_or_username) {
    $user = Flight::rm()->get_user($email_or_username);
    if ($user) {
        unset($user["password"]);
        Flight::json($user);
    } else {
        Flight::json(["message" => "User does not exist."]);
    }
});
