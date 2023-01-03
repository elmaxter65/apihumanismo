<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Response;
use Validator;
use Exception;
use DB;
use Mail;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function login(Request $request)
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

                $credentials = ['email' => $request->email, 'password' => $request->password];
                if (!Auth::attempt($credentials)) {
                    return response()->json(['message' => 'Credenciales incorrectas'], 401);
                }

                $user = $request->user();
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;

                if ($remember_me) { //de momento agrego una semana, luego se define eso
                    $token->expires_at = Carbon::now()->addWeeks(1);
                }
                $token->save();

                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type'   => 'Bearer',
                    'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString() //,
                    //'info'         => $user
                ], 200);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: " . $e->getMessage()], 500);
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
            $usr = Auth::guard('api')->user(); //usuario actual
            $userId = $usr->id;
            $user = User::where('id', '=', $userId)->first();

            return response()->json(["user" => $user], 200);
        } catch (Exception $e) {
            return response()->json(["message" => "Error en Base de Datos: " . $e->getMessage()], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:191'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Errores con el envío del correo electrónico.", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {
                //Verificación de existencia de email
                $user = User::where('email', '=', $request->email)->first();
                if ($user == null) {
                    return response()->json(["status" => "error", "message" => "El correo electrónico no existe en nuestros registros."], 404);
                }
                $email = $user->email;
                $token = $user->token;
                $name = $user->name;
                if (($name == null) || ($name == '')) {
                    $name = $user->email;
                }

                $domain = "";

                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                    $url = "https://";
                } else {
                    $url = "http://";
                }
                $domain = $url . $_SERVER['HTTP_HOST'];

                $data = [
                    'name' => $name,
                    'url' => $domain . "/reset-password/" . $email . "/" . $token
                ];

                Mail::send('mails.recoverypassword', $data, function ($msg) use ($request, $email) {
                    $msg->subject('Recuperación de contraeña en Nebolus');
                    $msg->to($email);
                });

                DB::commit();
                return response()->json(["message" => "Hemos enviado un correo electrónico para recuperar tu contraseña."], 200);
            } catch (Exception $e) {
                DB::rollback();
                if ($e instanceof ModelNotFoundException) {
                    return response()->json(['message' => 'El usuario no fue encontrado.'], 404);
                }
                return response()->json(["message" => "No se pudo enviar el correo electrónico: " . $e->getMessage()], 500);
            }
        }
    }

    public function recoveryPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'token' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'password' => 'required|string|min:8|max:191'
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json(["message" => "Errores con el envío del correo electrónico.", "errors" => $errors], 404);
        } else {
            DB::beginTransaction();
            try {
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
                    return response()->json(["message" => "El token del usuario no existe o ya está vencido."], 404);
                }
                $user->password = bcrypt($request->password);
                //$user->token = Str::random(40);
                $user->save();
                $email = $user->email;

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
            return back()->with('errors', 'Error en envío de datos.');
        } else {

            try {

                $credentials = ['email' => $request->email, 'password' => $request->password];
                if (!auth()->attempt($credentials)) {
                    //return back()->withErrors(['message' => 'Credenciales incorrectas'], 404);
                    return back()->with('errors', 'Credenciales incorrectas.');
                }

                //Verificación de existencia del token con ese mail consultado
                $user = User::where('email', '=', trim($request->email))->first();
                if ($user == null) {
                    return back()->with('errors', 'El usuario no existe.');
                }
                $user->token = Str::random(40);
                $user->save();

                return redirect()->to('/dashboard');
            } catch (Exception $e) {
                DB::rollback();
                //return back()->withErrors(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
                return back()->with('errors', 'Error en Base de Datos: ' . $e->getMessage());
            }
        }
    }

    public function logoutweb(Request $request)
    {
        \Auth::logout(); // logout user
        return redirect()->to('/');
    }

    public function forgotPasswordWeb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:191'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            //return back()->withErrors(["message" => "Error en envío de datos.", "errors" => $errors, 422]);
            return back()->with('errors', 'Error en envío de datos.');
        } else {
            DB::beginTransaction();
            try {
                //Verificación de existencia de email
                $user = User::where('email', '=', $request->email)->first();
                if ($user == null) {
                    //return back()->withErrors(["message" => "El correo electrónico no existe en nuestros registros."], 404);
                    return back()->with('errors', 'El correo electrónico no existe en nuestros registros.');
                }
                $email = $user->email;
                $token = $user->token;
                $name = $user->name;
                if (($name == null) || ($name == '')) {
                    $name = $user->email;
                }

                $domain = "";

                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                    $url = "https://";
                } else {
                    $url = "http://";
                }
                $domain = $url . $_SERVER['HTTP_HOST'];

                $data = [
                    'name' => $name,
                    'url' => $domain . "/reset-password/" . $email . "/" . $token
                ];

                Mail::send('mails.recoverypassword', $data, function ($msg) use ($request, $email) {
                    $msg->subject('Recuperación de contraeña en Nebolus');
                    $msg->to($email);
                });

                DB::commit();
                return back()->with('success', 'Hemos enviado un correo electrónico con el enlace de recuperación de contraseña.');
            } catch (Exception $e) {
                DB::rollback();
                if ($e instanceof ModelNotFoundException) {
                    return back()->with('errors', 'El usuario no fue encontrado.');
                    //return back()->withErrors(['message' => 'El usuario no fue encontrado.'], 404);
                }

                //return back()->withErrors(["message" => "No se pudo enviar el correo electrónico: ".$e->getMessage()], 500);
                return back()->with('errors', "No se pudo enviar el correo electrónico: " . $e->getMessage());
            }
        }
    }

    public function newPasswordWeb(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'token' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'password_confirmation' => 'required ',
            'password' => 'required|between:8,191|confirmed'
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors();
            return back()->with('errors', 'Error en envío de datos.');
        } else {
            DB::beginTransaction();
            try {
                //Verificación de existencia de usuario mediante el email
                $usermail = User::where('email', '=', trim($request->email))->first();
                if ($usermail == null) {
                    return back()->with('errors', 'El usuario ya no existe en nuestros registros.');
                }
                $userId = $usermail->id;
                $token = trim($request->token);

                //Verificación de existencia del token con ese mail consultado
                $user = User::where('id', '=', $userId)->where('token', '=', $token)->first();
                if ($user == null) {
                    return back()->with('errors', 'El token del usuario no existe o ya está vencido.');
                }
                $user->password = bcrypt($request->password);
                $user->token = Str::random(40);
                $user->save();

                DB::commit();
                return redirect()->to('/')->with('success', 'La contraseña se ha actualizado correctamente.');
            } catch (Exception $e) {
                DB::rollback();
                if ($e instanceof ModelNotFoundException) {
                    return back()->with('errors', 'El usuario no fue encontrado.');
                }
                return back()->with('errors', "No se pudo actualizar la contraseña: " . $e->getMessage());
            }
        }
    }
}
