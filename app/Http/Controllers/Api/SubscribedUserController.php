<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use Exception;
use DB;
use App\Models\SubscribedUser;
use App\Models\Language;
use App\Models\Newsletter;
use App\Models\User;

class SubscribedUserController extends Controller
{
    /**
     * Constructor.
     *
     *
     */
    public function __construct()
    {
       $this->middleware(['auth:api'])->except(['index', 'show', 'store', 'unsubscribeNewsletter']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $language = trim($request->language);

            if ( isset($request->language) )
            {
                $language = Language::select('id')->where('code', '=', $language)->first();
                if ($language != null) {
                    $language_id = $language->id;
                }
            } else {
                $language = Language::select('id')->where('code', '=', 'ESP')->first();
                $language_id = $language->id;
            }

            $subscribedusers = SubscribedUser::with('newsletter')->byLanguage($language_id)->orderByCreatedAtDesc()->get();
            return response()->json(['subscribedusers' => $subscribedusers], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Usuarios suscritos no encontrados.'], 404);
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
            'email' => 'required|string|email|max:191',
            'language' => 'nullable|string'
        ]);

        //Si la validación no pasa (se dispara el error 422)
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Error en envío de datos", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {

                $subscribeduser = SubscribedUser::where('email', '=', trim($request->get('email')))->first();
                if ($subscribeduser != null) {
                    return response()->json(['message' => 'El correo electrónico ya se encuentra suscrito a la newsletter.'], 500);
                }

                if (isset($request->language)) {
                    $language = Language::where('code', '=', trim($request->language))->first();
                    $language_id = $language->id;
                } else {
                    $language = Language::where('code', '=', 'ESP')->first();
                    $language_id = $language->id;
                }

                $newsletter = Newsletter::where('code','=','DAY')->first();
                SubscribedUser::create([
                    'email' => trim($request->get('email')),
                    'newsletter_id' => $newsletter->id,
                    'language_id' => $language_id
                ]);

                DB::commit();
                return response()->json(["message" => "El usuario se ha suscrito a la newsletter exitosamente."], 201);
            } catch (Exception $e) {
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
    public function show($email)
    {
        try {
            $subscribeduser = SubscribedUser::with('newsletter')->where('email', '=', $email)->first();
            return response()->json(['subscribeduser' => $subscribeduser], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Usuario suscrito no encontrado.'], 404);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
    public function unsubscribeNewsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:191'
        ]);

        //Si la validación no pasa (se dispara el error 422)
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Error en envío de datos", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {

                $subscribeduser = SubscribedUser::where('email', '=', trim($request->get('email')))->first();
                if ($subscribeduser == null) {
                    return response()->json(['message' => 'El correo electrónico no se encuentra suscrito a la newsletter.'], 500);
                }
                $delsubscribeduser = $subscribeduser->forceDelete();

                if ($delsubscribeduser) {
                    $user = User::where('email', '=', trim($request->get('email')))->first();

                    if ($user != null) {
                        $user->newsletter_id = null;
                        $user->save();
                    }
                }

                DB::commit();
                return response()->json(["message" => "El usuario se ha dado de baja a la newsletter exitosamente."], 200);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
            }
        }
    }

}
