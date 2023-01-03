<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use Validator;
use Exception;
use DB;
use Mail;
use Carbon\Carbon;
use App\Models\Theme;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\Role;
use App\Models\Level;
use App\Models\Newsletter;
use App\Models\Language;
use App\Models\SubscribedUser;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:191',
            'password' => 'required|string|min:8|max:191',
            'adult' => 'nullable|integer',
            'accept_private_policy' => 'required|integer',
            'newsletter' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["status_code" => 422, "message" => "Error en envío de datos.", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {

                $checkuser = User::where('email', '=', trim($request->email))->whereNull('deleted_at')->first();
				if ($checkuser != null) {
					return response()->json(['message' => 'Este correo electrónico ya se encuentra en uso.'], 500);
				}

                $role = Role::where('code', '=', 'USR')->first();
                if ($role == null) {
                    return response()->json(["status_code" => 404, "status" => "error", 'message' => 'No se ha podido encontrar el rol para el usuario. Inténtelo nuevamente o contacte al administrador del sistema.'], 404);
                }

                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

                $email = trim($request->email);
                $role_id = $role->id;
                $tok = substr(str_shuffle($permitted_chars), 0, 64);
                $adult = (isset($request->adult) && $request->adult > 0 && $request->adult < 2) ? $request->adult : 0;
                $accept_private_policy = (isset($request->accept_private_policy) && $request->accept_private_policy > 0 && $request->accept_private_policy < 2) ? $request->accept_private_policy : 0;
                $newsletter = (isset($request->newsletter) && $request->newsletter > 0 && $request->newsletter < 2) ? $request->newsletter : 0;
                if($newsletter == 1) {
                    $nl = Newsletter::where('code', '=', 'WEK')->first();
                    $newsletter_id = $nl->id;
                } else {
                    $newsletter_id = null;
                }

                $user = new User([
                    'email' => $email,
                    'password' => bcrypt($request->password),
                    'token' => $tok,
                    'adult' => $adult,
                    'accept_private_policy' => $accept_private_policy,
                    'role_id' => $role_id,
                    'newsletter_id' => $newsletter_id
                ]);

                $saveUser = $user->save();
                $userId = $user->id;

                if ($saveUser) {

                    //suscripción al newsletter
                    if (isset($user->newsletter_id) && $user->newsletter_id > 0) {
                        $subscribeduser = SubscribedUser::where('email', '=', trim($user->email))->first();
                        $language = Language::where('code','=','ESP')->first();
                        $language_id = $language->id;

                        if ($subscribeduser == null) {

                            SubscribedUser::create([
                                'email' => trim($user->email),
                                'newsletter_id' => $newsletter_id,
                                'language_id' => $language_id
                            ]);

                        }

                    }

                    $domain = "";

                    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                        $url = "https://"; 
                    }else{
                        $url = "http://"; 
                    }
                    $domain = $url . $_SERVER['HTTP_HOST'];
    
                    $email = trim($user->email);
                    $url = $domain."/api/activate-account/".$email."/".$tok;

					Mail::send('mails.confirmemail', ['user' => $email, 'url' => $url], function($msg) use ($request, $email) 
					{
						$msg->subject('¡Activar cuenta en Mundo Crypto TV!');
						$msg->to($email);
					});

                }

                if (isset($user->newsletter_id) && $user->newsletter_id > 0) {
                    $us = DB::table('users')
                    ->distinct()
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->join('newsletters', 'users.newsletter_id', '=', 'newsletters.id')
                    ->select(
                        'users.id',
                        'users.email',
                        'users.adult',
                        'users.accept_private_policy',
                        'roles.name AS role',
                        'newsletters.name AS newsletter_name',
                        'newsletters.code AS newsletter_code'
                    )
                    ->where('users.id', '=', $userId)
                    ->first();
                } else {
                    $us = DB::table('users')
                    ->distinct()
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select(
                        'users.id',
                        'users.email',
                        'users.adult',
                        'users.accept_private_policy',
                        'roles.name AS role',
                        DB::raw('null as newsletter_name'),
                        DB::raw('null as newsletter_code')
                    )
                    ->where('users.id', '=', $userId)
                    ->first();
                }

                $data = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role_name' => $role->name
                ];


                $tokenResult = $user->createToken($data);
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

                DB::commit();
                return response()->json(["message" => "Te has registrado correctamente.", 'access_token' => $tokenResult->accessToken, 'user' => $us], 201);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ". $e->getMessage()], 500);
            }
        }

    }

    public function signupSocialNetwork(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:191'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["status_code" => 422, "message" => "Error en envío de datos.", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {

                $checkuser = User::where('email', '=', trim($request->email))->where('active', '=', 1)->first();
				if ($checkuser != null) {
					return response()->json(['message' => 'Este usuario ya se encuentra registrado.'], 500);
				}

				$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

				$nl = Newsletter::where('code', '=', 'WEK')->first();
				if ($nl == null) {
					return response()->json(['message' => 'No se ha podido encontrar el tipo de newletter para el usuario. Inténtelo nuevamente o contacte al administrador del sistema.'], 404);
				}    
				$newsletter_id = $nl->id;

				$role = Role::where('code', '=', 'USR')->first();
				if ($role == null) {
					return response()->json(['message' => 'No se ha podido encontrar el rol para el usuario. Inténtelo nuevamente o contacte al administrador del sistema.'], 404);
				}
				$role_id = $role->id;

				$tok = substr(str_shuffle($permitted_chars), 0, 64);

				$user = new User([
					'email' => trim($request->email),
					'active' => 1,
					'token' => $tok,
					'adult' => 1,
					'accept_private_policy' => 1,
					'role_id' => $role_id,
					'newsletter_id' => $newsletter_id
				]);

                $saveUser = $user->save();
                $userId = $user->id;

                if ($saveUser) {

                    //suscripción al newsletter
                    if (isset($user->newsletter_id) && $user->newsletter_id > 0) {
                        $subscribeduser = SubscribedUser::where('email', '=', trim($user->email))->first();
                        $language = Language::where('code','=','ESP')->first();
                        $language_id = $language->id;

                        if ($subscribeduser == null) {

                            SubscribedUser::create([
                                'email' => trim($user->email),
                                'newsletter_id' => $newsletter_id,
                                'language_id' => $language_id
                            ]);

                        }

                    }


                }
				
				$user_id = $user->id;
				Auth::loginUsingId($user_id);

                if (isset($user->newsletter_id) && $user->newsletter_id > 0) {
                    $us = DB::table('users')
                    ->distinct()
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->join('newsletters', 'users.newsletter_id', '=', 'newsletters.id')
                    ->select(
                        'users.id',
                        'users.email',
                        'users.adult',
                        'users.accept_private_policy',
                        'roles.name AS role',
                        'newsletters.name AS newsletter_name',
                        'newsletters.code AS newsletter_code'
                    )
                    ->where('users.id', '=', $userId)
                    ->first();
                } else {
                    $us = DB::table('users')
                    ->distinct()
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select(
                        'users.id',
                        'users.email',
                        'users.adult',
                        'users.accept_private_policy',
                        'roles.name AS role',
                        DB::raw('null as newsletter_name'),
                        DB::raw('null as newsletter_code')
                    )
                    ->where('users.id', '=', $userId)
                    ->first();
                }

                $data = [
                    'id' => $user->id,
                    'email' => $user->email
                ];


                $tokenResult = $user->createToken($data);
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

                DB::commit();
                return response()->json(["message" => "Te has registrado correctamente.", 'access_token' => $tokenResult->accessToken, 'user' => $us], 201);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ". $e->getMessage()], 500);
            }
        }

    }

    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:191'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["status_code" => 422, "message" => "Error en envío de datos.", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {
                $msg = '';
                $exists = 0;

                $user = User::where('email', '=', $request->email)->first();
                if ($user == null) {
                    $msg = 'Este correo electrónico no existe';
                    $exists = 0;
                } else {
                    $msg = 'Este correo ya está siendo usado';
                    $exists = 1;
                }

                DB::commit();
                return response()->json(["message" => $msg, 'exists' => $exists], 200);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ". $e->getMessage()], 500);
            }
        }

    }

    public function activate_account($email, $token)
    {
        $user = User::where('email', $email)->first();

        if (!$user) echo "El email no existe en nuestros registros.";

        $user2 = User::where('token', $token)->first();

        if (!$user2) echo "El token no existe o ya expiró.";

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

        $user->active = 1;
        $user->token = substr(str_shuffle($permitted_chars), 0, 64);
        $user->save();

        echo "Tu cuenta ha sido activada.";

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string',
            'code' => 'required|string|min:3|max:3'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Error en envío de datos.", "errors" => $errors], 422);
        } else {

            try {

                $remember_me = 1;

                $role = Role::where('code', '=', 'USR')->first();
                if ($role == null) {
                    return response()->json(['message' => 'El rol especificado no es correcto o no existe.'], 404);
                }

                $us_exists = User::where('email', '=', trim($request->email))->first();
                if ($us_exists == null) {
                    return response()->json(['message' => 'El usuario no existe.'], 404);
                }

                //$us_active = User::where('email', '=', trim($request->email))->where('active', '=', 1)->first();
                //if ($us_active == null) {
                    //return response()->json(['message' => 'El usuario no está activo.'], 500);
                //}

                $credentials = ['email' => $request->email, 'password' => $request->password, 'role_id' => $role->id];
                if (!Auth::attempt($credentials)) {
                    return response()->json(['message' => 'Credenciales incorrectas'], 401);
                }

                $user = Auth::user();
                $userId = $user->id;

                $us = DB::table('users')
                ->distinct()
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.active',
                    'users.avatar',
                    'roles.name AS role'
                )
                ->where('users.id', '=', $userId)
                ->first();

                $prefs = DB::table('user_preferences')
                ->distinct()
                ->join('themes', 'themes.id', '=', 'user_preferences.preference_id')
                ->select(
                    'themes.id',
                    'themes.name'
                )
                ->where('user_preferences.user_id', '=', $user->id)
                ->get();

                $us->preferences = $prefs;

                $data = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role_name' => $role->name
                ];

                //$user = $request->user();
                $tokenResult = $user->createToken($data);
                $token = $tokenResult->token;

                if ($remember_me) {//de momento agrego una semana, luego se define eso
                    $token->expires_at = Carbon::now()->addWeeks(1);
                }
                $token->save();

                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type'   => 'Bearer',
                    'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    'user'         => $us
                ], 200);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
            }
        }

    }

    public function loginAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Error en envío de datos.", "errors" => $errors], 422);
        } else {

            try {

                $remember_me = 1;

                $us_exists = User::where('email', '=', trim($request->email))->first();
                if ($us_exists == null) {
                    return response()->json(['message' => 'El usuario no existe.'], 404);
                }

                //$us_active = User::where('email', '=', trim($request->email))->where('active', '=', 1)->first();
                //if ($us_active == null) {
                    //return response()->json(['message' => 'El usuario no está activo.'], 500);
                //}

                $credentials = ['email' => $request->email, 'password' => $request->password];
                if (!Auth::attempt($credentials)) {
                    return response()->json(['message' => 'Credenciales incorrectas'], 401);
                }

                $user = Auth::user();
                $userId = $user->id;

                $us = DB::table('users')
                ->distinct()
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.active',
                    'users.avatar',
                    'roles.name AS role'
                )
                ->where('users.id', '=', $userId)
                ->first();

                $prefs = DB::table('user_preferences')
                ->distinct()
                ->join('themes', 'themes.id', '=', 'user_preferences.preference_id')
                ->select(
                    'themes.id',
                    'themes.name'
                )
                ->where('user_preferences.user_id', '=', $user->id)
                ->get();

                $us->preferences = $prefs;

                $data = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role_name' => $role->name
                ];

                //$user = $request->user();
                $tokenResult = $user->createToken($data);
                $token = $tokenResult->token;

                if ($remember_me) {//de momento agrego una semana, luego se define eso
                    $token->expires_at = Carbon::now()->addWeeks(1);
                }
                $token->save();

                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type'   => 'Bearer',
                    'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    'user'         => $us
                ], 200);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
            }
        }

    }

    public function loginSocialNetwork(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Error en envío de datos.", "errors" => $errors], 422);
        } else {

            try {

                DB::beginTransaction();

                $user = User::where('email', '=', trim($request->email))->where('active', '=', 1)->first();
                if ($user == null) {
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

                    $nl = Newsletter::where('code', '=', 'WEK')->first();
                    if ($nl == null) {
                        return response()->json(['message' => 'No se ha podido encontrar el tipo de newsletter para el usuario. Inténtelo nuevamente o contacte al administrador del sistema.'], 404);
                    }    
                    $newsletter_id = $nl->id;

                    $role = Role::where('code', '=', 'USR')->first();
                    if ($role == null) {
                        return response()->json(['message' => 'No se ha podido encontrar el rol para el usuario. Inténtelo nuevamente o contacte al administrador del sistema.'], 404);
                    }
                    $role_id = $role->id;

                    $tok = substr(str_shuffle($permitted_chars), 0, 64);

                    $user = new User([
                        'email' => trim($request->email),
                        'active' => 1,
                        'token' => $tok,
                        'adult' => 1,
                        'accept_private_policy' => 1,
                        'role_id' => $role_id,
                        'newsletter_id' => $newsletter_id
                    ]);
                    $saveUser = $user->save();

                    if ($saveUser) {

                        //suscripción al newsletter
                        if (isset($user->newsletter_id) && $user->newsletter_id > 0) {
                            $subscribeduser = SubscribedUser::where('email', '=', trim($user->email))->first();
                            $language = Language::where('code','=','ESP')->first();
                            $language_id = $language->id;

                            if ($subscribeduser == null) {

                                SubscribedUser::create([
                                    'email' => trim($user->email),
                                    'newsletter_id' => $newsletter_id,
                                    'language_id' => $language_id
                                ]);

                            }

                        }

                    }
    
                }
                $user_id = $user->id;

                //$credentials = ['email' => $request->email, 'password' => ''];
                //if (!Auth::attempt($credentials)) {
                    //return response()->json(['message' => 'Credenciales incorrectas'], 401);
                //}
                Auth::loginUsingId($user_id);

                if (isset($user->newsletter_id) && $user->newsletter_id > 0) {
                    $us = DB::table('users')
                    ->distinct()
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->join('newsletters', 'users.newsletter_id', '=', 'newsletters.id')
                    ->select(
                        'users.id',
                        'users.name',
                        'users.email',
                        'users.active',
                        'users.avatar',
                        'users.adult',
                        'users.accept_private_policy',
                        'roles.name AS role',
                        'newsletters.name AS newsletter_name',
                        'newsletters.code AS newsletter_code'
                    )
                    ->where('users.id', '=', $user_id)
                    ->first();
                } else {
                    $us = DB::table('users')
                    ->distinct()
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select(
                        'users.id',
                        'users.email',
                        'users.avatar',
                        'users.adult',
                        'users.accept_private_policy',
                        'roles.name AS role',
                        DB::raw('null as newsletter_name'),
                        DB::raw('null as newsletter_code')
                    )
                    ->where('users.id', '=', $user_id)
                    ->first();
                }

                $data = [
                    'id' => $user->id,
                    'email' => $user->email
                ];


                $tokenResult = $user->createToken($data);
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

                DB::commit();
                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type'   => 'Bearer',
                    'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    'user'         => $us
                ], 200);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
            }
        }

    }

    public function logout(Request $request)
    {
        //Elimino el token de la API
        $request->user()->token()->revoke();
        return response()->json(['message' => 'El usuario se ha desconectado exitosamente!', 200]);
    }

    public function user(Request $request)
    {
        try {
            $usr = Auth::guard('api')->user();//usuario actual
            $userId = $usr->id;
            $user = User::where('id', '=' ,$userId)->first();

            return response()->json(["user" => $user], 200);
        } catch (Exception $e) {
            return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|email|max:191'
        ]);
        
        if($validator->fails()){
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Errores con el envío del correo electrónico.", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try 
            {
                //Verificación de existencia de email
                $user = User::where('email', '=', $request->email)->first();
                if ($user == null) {
                    return response()->json(["status" => "error", "message" => "El correo electrónico no está registrado en nuestra base de datos."], 404);
                }
                $email = $user->email;
                $token = $user->token;
                $name = $user->name;
                if (($name == null) || ($name == '')) {
                    $name = $user->email;
                }

                /*
                $domain = "";

                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                    $url = "https://"; 
                }else{
                    $url = "http://"; 
                }
                $domain = $url . $_SERVER['HTTP_HOST'];
                */
                $domain = "https://webmundo.ole.agency";

                $data = [
                    'user' => $name,
                    'url' => $domain."/reset-password/".$email."/".$token
                ];

                Mail::send('mails.recoverypassword', $data, function($msg) use ($request, $email) 
                {
                    $msg->subject('Recuperación de contraseña en Mundo Crypto TV');
                    $msg->to($email);
                });

                DB::commit();
                return response()->json(["message" => "Hemos enviado un correo electrónico para recuperar tu contraseña."], 200);

            } catch (Exception $e) {
                DB::rollback();
                if ($e instanceof ModelNotFoundException) {
                    return response()->json(['message' => 'El usuario no fue encontrado.'], 404);
                }
                return response()->json(["message" => "No se pudo enviar el correo electrónico: ".$e->getMessage()], 500);
            }
        }

    }

    public function recoveryPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'token'=>'required|string|max:191',
            'email'=>'required|email|max:191',
            'password'=>'required|string|min:8|max:191'
        ]);
        
        if($validate->fails()){
            $errors = $validate->errors();
            return response()->json(["message" => "Errores con el envío del correo electrónico.", "errors" => $errors], 404);
        } else {
            DB::beginTransaction();
            try 
            {
                //Verificación de existencia de usuario mediante el email
                $usermail = User::where('email', '=', trim($request->email))->first();
                if ($usermail == null) {
                    return response()->json(["message" => "El usuario no existe."], 404);
                }
                $userId = $usermail->id;
                $token = trim($request->token);

                //Verificación de existencia del token con ese mail consultado
                $user = User::where('id', '=', $userId)->where('token', '=', $token)->first();
                if ($user == null) {
                    return response()->json(["message" => "El token del usuario no existe o ya expiró."], 404);
                }
                $user->password = bcrypt($request->password);
                //$user->token = Str::random(40);
                $usSave = $user->save();
                $email = $user->email;

                $data = [
                    'user' => $name
                ];

                if($usSave) {

                    Mail::send('mails.confirmchangepassword', $data, function($msg) use ($request, $email) 
                    {
                        $msg->subject('Cambio de contraseña en Mundo Crypto TV');
                        $msg->to($email);
                    });
    
                }

                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

                DB::commit();
                return response()->json(["message" => "La contraseña se ha actualizado correctamente.", 'access_token' => $tokenResult->accessToken], 200);

            } catch (Exception $e) {
                DB::rollback();
                if ($e instanceof ModelNotFoundException) {
                    return response()->json(['message' => 'El usuario no existe.'], 404);
                }
                return response()->json(["message" => "No se pudo actualizar la contraseña", "exception" => $e->getMessage()], 500);
            }
        }

    }

    public function loginweb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            //return back()->withErrors(["message" => "Error en envío de datos.", "errors" => $errors]);
            return back()->with('errors','Error en envío de datos.');
        } else {

            try {

                $credentials = ['email' => $request->email, 'password' => $request->password];
                if (!auth()->attempt($credentials)) {
                    //return back()->withErrors(['message' => 'Credenciales incorrectas'], 404);
                    return back()->with('errors','Credenciales incorrectas.');
                }

                //Verificación de existencia del token con ese mail consultado
                $user = User::where('email', '=', trim($request->email))->first();
                if ($user == null) {
                    return back()->with('errors','El usuario no existe.');
                }
                $user->token = Str::random(40);
                $user->save();

                return redirect()->to('/dashboard');

            } catch (Exception $e) {
                DB::rollback();
                //return back()->withErrors(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
                return back()->with('errors','Error en Base de Datos: '.$e->getMessage());
            }
        }

    }

    public function logoutweb(Request $request) {
      \Auth::logout(); // logout user
      return redirect()->to('/');
    }

    public function forgotPasswordWeb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|email|max:191'
        ]);
        
        if($validator->fails()){
            $errors = $validator->errors();
            $errors = json_decode($errors);
            //return back()->withErrors(["message" => "Error en envío de datos.", "errors" => $errors, 422]);
            return back()->with('errors','Error en envío de datos.');
        } else {
            DB::beginTransaction();
            try 
            {
                //Verificación de existencia de email
                $user = User::where('email', '=', $request->email)->first();
                if ($user == null) {
                    //return back()->withErrors(["message" => "El correo electrónico no existe en nuestros registros."], 404);
                    return back()->with('errors','El correo electrónico no existe en nuestros registros.');
                }
                $email = $user->email;
                $token = $user->token;
                $name = $user->name;
                if (($name == null) || ($name == '')) {
                    $name = $user->email;
                }

                /*
                $domain = "";

                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                    $url = "https://"; 
                }else{
                    $url = "http://"; 
                }
                $domain = $url . $_SERVER['HTTP_HOST'];
                */

                $domain = "https://webmundo.ole.agency"; 

                $data = [
                    'name' => $name,
                    'url' => $domain."/reset-password/".$email."/".$token
                ];

                Mail::send('mails.recoverypassword', $data, function($msg) use ($request, $email) 
                {
                    $msg->subject('Recuperación de contraeña en Nebolus');
                    $msg->to($email);
                });

                DB::commit();
                return back()->with('success','Hemos enviado un correo electrónico con el enlace de recuperación de contraseña.');

            } catch (Exception $e) {
                DB::rollback();
                if ($e instanceof ModelNotFoundException) {
                    return back()->with('errors','El usuario no fue encontrado.');
                    //return back()->withErrors(['message' => 'El usuario no fue encontrado.'], 404);
                }

                //return back()->withErrors(["message" => "No se pudo enviar el correo electrónico: ".$e->getMessage()], 500);
                return back()->with('errors',"No se pudo enviar el correo electrónico: ".$e->getMessage());
            }
        }

    }

    public function newPasswordWeb(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'token'=>'required|string|max:191',
            'email'=>'required|email|max:191',
            'password_confirmation' => 'required ',
            'password'=>'required|between:8,191|confirmed'
        ]);
        
        if($validate->fails()){
            $errors = $validate->errors();
            return back()->with('errors','Error en envío de datos.');
        } else {
            DB::beginTransaction();
            try 
            {
                //Verificación de existencia de usuario mediante el email
                $usermail = User::where('email', '=', trim($request->email))->first();
                if ($usermail == null) {
                    return back()->with('errors','El usuario ya no existe en nuestros registros.');
                }
                $userId = $usermail->id;
                $token = trim($request->token);

                //Verificación de existencia del token con ese mail consultado
                $user = User::where('id', '=', $userId)->where('token', '=', $token)->first();
                if ($user == null) {
                    return back()->with('errors','El token del usuario no existe o ya está vencido.');
                }
                $user->password = bcrypt($request->password);
                $user->token = Str::random(40);
                $user->save();

                DB::commit();
                return redirect()->to('/')->with('success','La contraseña se ha actualizado correctamente.');

            } catch (Exception $e) {
                DB::rollback();
                if ($e instanceof ModelNotFoundException) {
                    return back()->with('errors','El usuario no fue encontrado.');
                }
                return back()->with('errors',"No se pudo actualizar la contraseña: ".$e->getMessage());
            }
        }

    }

    public function changePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password'=>'required|between:8,191',
            'new_password'=>'required|between:8,191'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["status_code" => 422, "message" => "Error en envío de datos.", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {

                $user = User::where('id', '=', $id)->first();

                if ($user == null) {
                    return response()->json(['message' => 'No se ha podido encontrar el usuario.'], 404);
                }

                $new_password = trim($request->new_password);

                $credentials = ['email' => $user->email, 'password' => $request->password];
                if (!Auth::attempt($credentials)) {
                    return response()->json(['message' => 'La contraseña actual del usuario es incorrecta.'], 500);
                } else {
                    $user->password = bcrypt($new_password);
                    $user->save();
                }

                DB::commit();
                return response()->json(["message" => "La contraseña se ha actualizado correctamente."], 200);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ". $e->getMessage()], 500);
            }
        }

    }

}
