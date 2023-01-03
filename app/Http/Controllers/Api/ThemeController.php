<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use Exception;
use DB;
use App\Models\Section;
use App\Models\Theme;
use Auth;

class ThemeController extends Controller
{
    /**
     * Constructor.
     *
     *
     */
    public function __construct()
    {
       $this->middleware(['auth:api'])->except(['index', 'show', 'preferences', 'initThemes', 'contentThemes', 'allThemes']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $themes = Theme::get();
            return response()->json(['themes' => $themes], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Temas no encontrados.'], 404);
            }
        }
    }

    /**
     * Display a listing of the resource (Temas de Inicio - Registro).
     *
     * @return \Illuminate\Http\Response
     */
    public function preferences()
    {
        try {
            $section = Section::where('code', '=', 'INI')->first();

            if ($section == null) {
                return response()->json(['message' => 'Secciones no encontradas.'], 404);
            }
            $section_id = $section->id;

            $preferences = Theme::bySection($section_id)->get();
            return response()->json(['preferences' => $preferences], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Preferencias no encontradas.'], 404);
            }
        }

    }

    /**
     * Display a listing of the resource (Temas de Inicio - Registro).
     *
     * @return \Illuminate\Http\Response
     */
    public function initThemes()
    {
        try {
            $section = Section::where('code', '=', 'INI')->first();

            if ($section == null) {
                return response()->json(['message' => 'Secciones no encontradas.'], 404);
            }
            $section_id = $section->id;

            $themes = Theme::with('sections.section')->bySection($section_id)->get();
            return response()->json(['themes' => $themes], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Temas de inicio no encontrados.'], 404);
            }
        }

    }

    /**
     * Display a listing of the resource (Temas de Inicio - Registro).
     *
     * @return \Illuminate\Http\Response
     */
    public function contentThemes()
    {
        try {
            $section = Section::where('code', '=', 'CON')->first();

            if ($section == null) {
                return response()->json(['message' => 'Secciones no encontradas.'], 404);
            }
            $section_id = $section->id;

            $themes = Theme::with('sections.section')->bySection($section_id)->get();
            return response()->json(['themes' => $themes], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Temas de contenido no encontrados.'], 404);
            }
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allThemes(Request $request)
    {
        try {

            if ( ( isset($request->per_page) && isset($request->page) ) || ( isset($request->per_page) && !isset($request->page) ) )
            {
                $per_page = $request->per_page;

                if ( !isset($request->page) ) $request->page = 1;
            } else {
                $count = Theme::count();
                $per_page = $count;
                $request->page = 1;
            }

            $themes = Theme::paginate($per_page);
            return response()->json(['themes' => $themes], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Temas no encontrados.'], 404);
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
        try {
            $theme = Theme::with('sections.section')->bySection($section_id)->get();
            return response()->json(['themes' => $themes], 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Temas de contenido no encontrados.'], 404);
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
}
