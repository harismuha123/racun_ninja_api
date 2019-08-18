<?php
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
 *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("POST /auth/register", function() {
    $data = Flight::request()->data->getData();
    /* Pass in a data object for model validation; if invalid, the request will terminate. */
    Flight::validate(RegisterModel::class, $data);
    $user = Flight::request()->data->getData();

    $validUsername = Flight::rv()->validateUsername($user["username"]);
    $existingUsername = Flight::rm()->get_user($user["username"]);

    $validEmail = Flight::rv()->validateEmail($user["email_address"]);
    $existingEmail = Flight::rm()->get_user_by_email($user["email_address"]);

    $validPassword = Flight::rv()->validatePassword($user["password"]);
    
    switch(true) {
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
 *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("POST /auth/login", function() {
    $data = Flight::request()->data->getData();
    /* Pass in a data object for model validation; if invalid, the request will terminate. */
    Flight::validate(LoginModel::class, $data);
    $db_user = Flight::lm()->get_user($data['username_or_email_address']);
    if ($db_user){
        if (password_verify($data["password"], $db_user["password"])){
            Flight::json(['email' => $db_user["email"], 'valid' => true]);
        }else{
            Flight::json([
                'message' => 'Invalid password!'
            ]);
        }
    }else{
        Flight::json(['message' => 'Invalid username or email address!']);
    }
});

 /**
 * @OA\Get(
 *      path="/auth/login/generate_otp/{email}",
 *      tags={"auth"},
 *      summary="Generate OTP QR Code.",
 *  *     @OA\Parameter(
 *         name="email",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),

 *  *      @OA\Response(
 *           response=200,
 *           description="Successfully logged in.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Log in failed.",
 *      ),
 *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("GET /auth/login/generate_otp/@email", function($email) {
    Flight::json(["otp" => Flight::lv()->generateTOTP($email)]);
});

 /**
 * @OA\Post(
 *      path="/auth/login/generate_otp",
 *      tags={"auth"},
 *      summary="Verify your account using OTP code.",
 *      @OA\RequestBody(
 *          description="Sample request body.",
 *          @OA\JsonContent(ref="#/components/schemas/VerificationCodeModel")
 *       ),
 *       @OA\Response(
 *           response=200,
 *           description="Successfully logged in.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Log in failed.",
 *      ),
 *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("POST /auth/login/generate_otp", function() {
    $data = Flight::request()->data->getData();
    /* Pass in a data object for model validation; if invalid, the request will terminate. */
    Flight::validate(VerificationCodeModel::class, $data);
    $isValid = Flight::lv()->verifyTOTP(trim($data["verification_code"], " "));
    if(!$isValid) {
        Flight::json(array("message" => "Code is not valid!"));
    } else {
        $user = Flight::lm()->get_user($data["email"]);
        if($user) {
            if($user["remember_me"]) {
                Flight::lm()->set_remember($user["email"]);
                Flight::lm()->remember_me($user["username"], $user["password"]);
            }
            Flight::json(array("message" => "Code is valid!", "valid" => true));
        } else {
            Flight::json(array("message" => "User email is invalid!"));
        }
    }
});

 /**
 * @OA\Get(
 *      path="/auth/login/generate_sms/{email}",
 *      tags={"auth"},
 *      summary="Generate SMS verification Code.",
 *  *     @OA\Parameter(
 *         name="email",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),

 *  *      @OA\Response(
 *           response=200,
 *           description="Successfully logged in.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Log in failed.",
 *      ),
 *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("GET /auth/login/generate_sms/@email", function($email) {
    Flight::json(Flight::tm()->generateSMS($email));
});

 /**
 * @OA\Post(
 *      path="/auth/login/generate_sms",
 *      tags={"auth"},
 *      summary="Verify your account using SMS code.",
 *      @OA\RequestBody(
 *          description="Sample request body.",
 *          @OA\JsonContent(ref="#/components/schemas/VerificationSMSModel")
 *       ),
 *       @OA\Response(
 *           response=200,
 *           description="Successfully logged in.",
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Log in failed.",
 *      ),
 *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("POST /auth/login/generate_sms", function() {
    $data = Flight::request()->data->getData();
    /* Pass in a data object for model validation; if invalid, the request will terminate. */
    Flight::validate(VerificationSMSModel::class, $data);
    $token = Flight::tm()->get_token($data["verification_code"], $data["email"]);
    switch(true) {
        case !$token:
            Flight::json(["message" => "Code does not exist!"]);
            break;
        case date('Y-m-d H:i:s') > $token["valid_until"]:
            Flight::json(["message" => "Code has expired!"]);
            break;
        default:
            $user = Flight::lm()->get_user($data["email"]);
            if($user) {
                if($user["remember_me"]) {
                    Flight::lm()->set_remember($user["email"]);
                    Flight::lm()->remember_me($user["username"], $user["password"]);
                }
                Flight::json(array("message" => "Code is valid!", "valid" => true));
            } else {
                Flight::json(array("message" => "User mail is invalid!"));
            }
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
 *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("GET /auth/reset/@email_or_username", function($email_or_username) {
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
 *     security={
 *          {"api_key": {}}
 *      }
 * )
 */
Flight::route("PUT /auth/reset", function() {
    $data = Flight::request()->data->getData();
    /* Pass in a data object for model validation; if invalid, the request will terminate. */
    Flight::validate(PasswordResetModel::class, $data);
    $token = Flight::tm()->get_email_token($data["token"]);
    switch(true) {
        case !$token:
            Flight::json(["message" => "Reset token does not exist!"]);
            break;
        case date('Y-m-d H:i:s') > $token["valid_until"]:
            Flight::json(["message" => "Reset token has expired!"]);
            break;
        default:
            $user = Flight::lm()->get_user($token["email_or_username"]);
            $validPassword = Flight::rv()->validatePassword($data["new_password"]);    

            if($user) {
                switch(true) {
                    case !password_verify($data["old_password"], $user["password"]):
                        Flight::json(["message" => "Existing password is not correct!"]);
                        break;
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
