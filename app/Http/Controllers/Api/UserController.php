<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use Exception;
use DB;
use App\Models\Theme;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\Role;
use App\Models\Entry;
use Auth;

class UserController extends Controller
{
    /**
     * Constructor.
     *
     *
     */
    public function __construct()
    {
       $this->middleware(['auth:api'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $name = trim($request->search);
            $per_page = $request->per_page;
            $users = User::with('role')->with('newsletter')->with('userpreferences.preference')->byName($name)->orderByCreatedAtDesc()->paginate($per_page);
            foreach ($users as $user) {
                $avatar = ($user->avatar == null) ? null : base64_encode($user->avatar);
                $user['avatar'] = $avatar;
            }
            return response()->json(['users' => $users], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Usuarios no encontrados.'], 404);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191',
            'avatar' => 'image|mimes:jpeg,png,jpg'
        ]);

        //Si la validación no pasa (se dispara el error 422)
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Error en envío de datos", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {

                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                $tok = substr(str_shuffle($permitted_chars), 0, 64);

                $role = Role::where('code', '=', 'USR')->first();
                $user = User::create([
                    'name' => trim($request->get('name')),
                    'email' => trim($request->get('email')),
                    'token' => $tok,
                    'role_id' => $role->id
                ]);

                if ($user) {
                    //avatar del usuario
                    if($request->hasFile('avatar')) {
                        
                        $file = $request->file('avatar');
                        //resizes
                        $medium = Image::make($file)->resize(500, 500);
                        Response::make($medium->encode('jpeg'));
                        //guardar en BD
                        $user->avatar = $medium;
                        $user->save();

                    }
                }

                DB::commit();
                return response()->json(["message" => "El usuario se ha creado exitosamente."], 201);
            } catch (Exception $e) { /* No es creado (500) - Rollback */
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::with('role')->with('newsletter')->with('userpreferences.preference')->findOrFail($id);
            return response()->json(['user' => $user], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Usuario no encontrado.'], 404);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191',
            'avatar' => 'image|mimes:jpeg,png,jpg'
        ]);

        //Si la validación no pasa (se dispara el error 422)
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Error en envío de datos", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {

                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                $tok = substr(str_shuffle($permitted_chars), 0, 64);

                $user = User::findOrFail($id);
                $user->name = trim($request->name);
                $user->email = trim($request->email);
                $user->token = $tok;
                $saveUser = $user->save();

                if ($saveUser) {
                    //avatar del usuario
                    if($request->hasFile('avatar')) {
                        
                        $file = $request->file('avatar');
                        //resizes
                        $medium = Image::make($file)->resize(500, 500);
                        Response::make($medium->encode('jpeg'));
                        //guardar en BD
                        $user->avatar = $medium;
                        $user->save();

                    }
                }

                DB::commit();
                return response()->json(["message" => "El usuario se ha editado exitosamente."], 200);
            } catch (Exception $e) { /* No es creado (500) - Rollback */
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            if ($user != null) {
                $entries = Entry::where('author_id', '=', $user->id)->get();
                $admin = User::where('email', '=', 'admin@mundocryptotv.com')->first();

                if ($admin != null) {
                    foreach ($entries as $entry) {
                        $entry->author_id = $admin->id;
                        $entry->save();
                    }
                }
            }
            $user->delete();
            DB::commit();
            return response()->json(["message" => "El usuario ha sido eliminado"], 200);
        } catch (Exception $e) {
            DB::rollback();
            if ($e instanceof ModelNotFoundException) {
                return response()->json(["message" => "Usuario no encontrado"], 404);
            }
            return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
        }
    }

    public function editPreferences(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'preferences' => 'array'
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

                if (isset($request->preferences) && count($request->preferences) > 0) {
                    $prefs = UserPreference::where('user_id', '=', $user->id)->get();

                    //limpieza de preferencias
                    foreach ($prefs as $pref) {
                        $pref->forceDelete();
                    }

                    $preferences = $request->preferences;
                    $nPreferences = count($preferences);

                    for ($i=0; $i < $nPreferences; $i++) {

                        $idPreference = $preferences[$i];

                        $preference = Theme::where('id', '=', $idPreference)->first();

                        if ($preference == null) {
                            return response()->json(["message" => "Preferencia no encontrada: ".$idPreference], 404);
                        }

                        $userpreference = UserPreference::where('user_id', '=', $user->id)->where('preference_id', '=', $idPreference)->first();

                        if ($userpreference == null) {

                            UserPreference::create([
                                'user_id' => $user->id,
                                'preference_id' => $idPreference
                            ]);
                        }

                    }

                }

                DB::commit();
                return response()->json(["message" => "Perfil completado correctamente."], 200);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ". $e->getMessage()], 500);
            }
        }

    }

    public function changeEmailName(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:191',
            'name'=>'nullable|string|max:191'
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

                //Verificación de que el email no lo posea otro usuario
                $usermail = User::where('id', '!=', $id)->where('email', '=', trim($request->email))->first();
                if ($usermail != null) {
                    return response()->json(['message' => 'El correo electrónico que intentar actualizar ya lo posee otro usuario.'], 500);
                }

                $user->email = trim($request->email);
                if( isset($request->name) && $request->name != '' ) $user->name = trim($request->name);
                $user->save();

                DB::commit();
                return response()->json(["message" => "Los datos se han actualizado correctamente."], 200);

            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ". $e->getMessage()], 500);
            }
        }

    }

    /**
     * Upload profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadAvatar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        //Si la validación no pasa (se dispara el error 422)
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["status_code" => 422, "message" => "Error en envío de la imagen", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {
                $user = User::findOrFail($id);
                $link_image = "";
                $domain = "";

                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                    $url = "https://";
                }else{
                    $url = "http://";
                }
                $domain = $url . $_SERVER['HTTP_HOST'];
				$folder = 'uploads/profile';

				//Subir foto de perfil
				if($request->hasFile('avatar') && $request->file('avatar')->isValid()) {

                    $now = date("YmdHis");

					$file = $request->file('avatar');
					$filenamewithextension = $file->getClientOriginalName();
					$filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
					$extension = $file->getClientOriginalExtension();
					$filenametostore = 'avatar'.$now.'.'.$extension;

					//resize
					$medium = Image::make($file)->resize(250, 250)->encode($extension);

					//Upload
					\Storage::disk('profile')->put($filenametostore, (string)$medium, 'public');

					//link
					$link_image = trim($domain.'/'.$folder.'/'.$filenametostore);
                    //guardar en BD
                    $user->avatar = $link_image;
                    $user->save();
				} else {
                    return response()->json(["message" => "La imagen es inválida (no encontrada) o no se ha enviado correctamente", "avatar" => $request->file('avatar')], 404);
                }


                DB::commit();
                return response()->json(["message" => "La imagen de perfil se ha subido correctamente", "link_image" => $link_image], 200);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["exception" => $e->getMessage(), "message" => "Imagen no almacenada"], 500);
            }
        }
    }

}
