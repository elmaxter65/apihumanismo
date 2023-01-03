<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use Exception;
use DB;
use App\Models\Language;
use App\Models\Tag;
use Auth;

class TagController extends Controller
{
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
                $language = Language::select('id')->where('code','=','ESP')->first();
                $language_id = $language->id;
            }

            if ( ( isset($request->per_page) && isset($request->page) ) || ( isset($request->per_page) && !isset($request->page) ) )
            {
                $per_page = $request->per_page;

                if ( !isset($request->page) ) $request->page = 1;
            } else {
                $count = Entry::count();
                $per_page = $count;
                $request->page = 1;
            }

            $tags = Tag::with('taglanguage')->byLanguage($language_id)->paginate($per_page);
            return response()->json(['tags' => $tags], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Tags no encontrados.'], 404);
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
        //
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
