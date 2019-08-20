<?php

use \Firebase\JWT\JWT;

/**
 * @OA\Post(
 *      path="/auth/register",
 *      tags={"auth"},
 *      summary="Register account.",
 *      @OA\RequestBody(
 *          description="Sample request body.",
 *          @OA\JsonContent(ref="#/components/schemas/RegisterModel")
 *       ),
 *      @OA\Response(
 *           response=200,
 *           description="Successfully registered.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Registration failed.",
 *      ),
 * )
 */
Flight::route("POST /auth/register", function () {
    $user = Flight::request()->data->getData();    
    /* Pass in a data object for model validation; if invalid, the request will terminate. */
    Flight::validate(RegisterModel::class, $user);

    $validUsername = Flight::rv()->validateUsername($user["username"]);
    $existingUsername = Flight::rm()->get_user($user["username"]);

    $validEmail = Flight::rv()->validateEmail($user["email_address"]);
    $existingEmail = Flight::rm()->get_user_by_email($user["email_address"]);

    $validPassword = Flight::rv()->validatePassword($user["password"]);

    switch (true) {
        case $existingUsername:
            Flight::json(["message" => "Username is already taken!"]);
            break;
        case !$validUsername["valid"]:
            Flight::json(["message" => $validUsername["message"]]);
            break;
        case $existingEmail:
            Flight::json(["message" => "Email already exists in the system!"]);
            break;
        case !$validEmail["valid"]:
            Flight::json(["message" => $validEmail["message"]]);
            break;
        case !$validPassword["valid"]:
            Flight::json(["message" => $validPassword["message"]]);
            break;
        case $user["password"] != $user["re_password"]:
            Flight::json(["message" => "Passwords do not match!"]);
            break;
        default:
            $response = Flight::rm()->add_user($user);
            Flight::json($response);
            break;
    }
});

/**
 * @OA\Post(
 *      path="/auth/login",
 *      tags={"auth"},
 *      summary="Log into account.",
 *      @OA\RequestBody(
 *          description="Sample request body.",
 *          @OA\JsonContent(ref="#/components/schemas/LoginModel")
 *       ),
 *      @OA\Response(
 *           response=200,
 *           description="Successfully logged in.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Log in failed.",
 *      ),
 * )
 */
Flight::route("POST /auth/login", function () {
    $data = Flight::request()->data->getData();
    /* Pass in a data object for model validation; if invalid, the request will terminate. */
    Flight::validate(LoginModel::class, $data);
    $db_user = Flight::lm()->get_user($data['username_or_email_address']);
    if ($db_user) {
        if (password_verify($data["password"], $db_user["password"])) {

            $secret_key = AUTH_SECRET;
            $issuer_claim = "RACUN_NINJA_SERVER";
            $audience_claim = "RACUN_NINJA_USER";
            $issuedat_claim = time();
            $notbefore_claim = $issuedat_claim + 10;
            $expire_claim = $issuedat_claim + 86400;

            unset($db_user["password"]);
            unset($db_user["mobile_number"]);
            unset($db_user["name"]);
            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => $db_user,
            );

            Flight::json([
                'message' => 'Successful login!',
                'jwt' => JWT::encode($token, $secret_key),
                'expireAt' => $expire_claim,
            ]);
        } else {
            Flight::json([
                'message' => 'Invalid password!',
            ]);
        }
    } else {
        Flight::json(['message' => 'Invalid username or email address!']);
    }
});

/**
 * @OA\Get(
 *      path="/auth/reset/{email_or_username}",
 *      tags={"auth"},
 *      summary="Generate e-mail for resetting user password.",
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
 * )
 */
Flight::route("GET /auth/reset/@email_or_username", function ($email_or_username) {
    Flight::json(Flight::tm()->generate_email_token($email_or_username));
});

/**
 * @OA\Put(
 *      path="/auth/reset/",
 *      tags={"auth"},
 *      summary="Reset email password.",
 *      @OA\RequestBody(
 *          description="Sample request body.",
 *          @OA\JsonContent(ref="#/components/schemas/PasswordResetModel")
 *       ),
 *       @OA\Response(
 *           response=200,
 *           description="Successfully logged in.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Log in failed.",
 *      ),
 * )
 */
Flight::route("PUT /auth/reset", function () {
    $data = Flight::request()->data->getData();
    /* Pass in a data object for model validation; if invalid, the request will terminate. */
    Flight::validate(PasswordResetModel::class, $data);
    $token = Flight::tm()->get_email_token($data["token"]);
    switch (true) {
        case !$token:
            Flight::json(["message" => "Reset token does not exist!"]);
            break;
        case date('Y-m-d H:i:s') > $token["valid_until"]:
            Flight::json(["message" => "Reset token has expired!"]);
            break;
        default:
            $user = Flight::lm()->get_user($token["email_or_username"]);
            $validPassword = Flight::rv()->validatePassword($data["new_password"]);

            if ($user) {
                switch (true) {
                    case !$validPassword["valid"]:
                        Flight::json(["message" => $validPassword["message"]]);
                        break;
                    case $data["new_password"] != $data["re_password"]:
                        Flight::json(["message" => "Passwords do not match!"]);
                        break;
                    default:
                        $response = Flight::tm()->update_password($token, $data["new_password"]);
                        Flight::json($response);
                        break;
                }
            }
    }
});
