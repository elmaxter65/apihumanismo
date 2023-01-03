<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use Exception;
use DB;
use Mail;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function comments()
    {
        try {
            $comments = Contact::orderByCreatedAtDesc()->get();
            return response()->json(['comments' => $comments], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Comentarios no encontrados.'], 404);
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
            'comment' => 'required|string|max:191',
            'accept_private_policy' => 'required|integer'
        ]);

        //Si la validación no pasa (se dispara el error 422)
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);
            return response()->json(["message" => "Error en envío de datos", "errors" => $errors], 422);
        } else {
            DB::beginTransaction();
            try {

                $contact = Contact::create([
                    'email' => trim($request->email),
                    'comment' => trim($request->comment),
                    'accept_private_policy' => $request->accept_private_policy
                ]);

                if($contact) {
                    $date = $contact->created_at;
                    $email = 'javier.medina@nebolus.com';
                    $email_sender = $contact->email;
                    $comment = $contact->comment;

                    Mail::send('mails.contact', ['date' => $date, 'email_sender' => $email_sender, 'comment' => $comment], function($msg) use ($request, $email) {
                        $msg->subject('¡Nuevo contacto en Nebolus!');
                        $msg->to($email);
                    });
                }

                DB::commit();
                return response()->json(["message" => "El comentario se ha agregado exitosamente."], 201);
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
    public function show($id)
    {
        //
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
}
