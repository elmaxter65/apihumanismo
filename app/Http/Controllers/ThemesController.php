<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThemeRequest;
use App\Http\Requests\ThemeUpdateRequest;
use App\Models\Theme;
use App\Models\ThemeSection;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ThemesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('themes.index', compact('pageConfigs'));
    }

    /**
     * Show the form for creating a theme resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('themes.create', compact('pageConfigs'));
    }

    /**
     * Store a themely created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ThemeRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = array(
                'name'              => $request->name,
                'slug'              => $request->slug,
                'indexed'           => $request->indexed,
                'visibility'        => $request->visibility == "on" ? 1 : 0,
                'start_position'    => $request->start_position_flat == "true" ? $request->start_position : 0,
            );

            $theme = Theme::create( $data );

            //dd($request->visibility);

            $sections = $request->sections;

            if ( is_array( $sections ) ) {
                foreach( $sections as $key => $section ) {
                    $dataSection = array(
                        'theme_id'      => $theme->id,
                        'section_id'    => $section,
                    );

                    $sectionTheme = ThemeSection::create( $dataSection );
                }
            }

            DB::commit();
            return redirect()->route('themes.index')->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store. Server Error');
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
        $pageConfigs = ['pageHeader' => false,];
        $theme = Theme::findOrFail( $id );
        return view('themes.edit', compact( 'theme', 'pageConfigs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ThemeUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $theme = Theme::findOrFail( $id );

            $theme->name              = $request->name;
            $theme->slug              = $request->slug;
            $theme->indexed           = $request->indexed;
            $theme->visibility        = $request->visibility == "on" ? 1 : 0;
            $theme->start_position    = $request->start_position_flat == "true" ? $request->start_position : 0;
            $theme->save();

            //dd($request->visibility);

            $sections = $theme->sections;

            if ( $sections != null ) {
                foreach( $sections as $key => $section ) {
                    $section = ThemeSection::findOrFail( $section->id );
                    $section->delete();
                }
            }

            $sections = $request->sections;

            if ( is_array( $sections ) ) {
                foreach( $sections as $key => $section ) {
                    $dataSection = array(
                        'theme_id'      => $theme->id,
                        'section_id'    => $section,
                    );

                    $sectionTheme = ThemeSection::create( $dataSection );
                }
            }

            DB::commit();
            return redirect()->route('themes.edit', [$theme->id])->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store. Server Error');
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
            $theme = Theme::findOrFail($id);
            $themesSections = $theme->sections;
            if ( $themesSections != null ) {
                foreach ( $themesSections as $key => $themesSection ) {
                    $themesSection = ThemeSection::findOrFail( $themesSection->id );
                    $themesSection->delete();
                }
            }
            $theme->delete();
            DB::commit();
            return redirect()->route('themes.index')->with(["message" => "Item delete success"], 200);
        } catch (Exception $e) {
            DB::rollback();
            if ($e instanceof ModelNotFoundException) {
                return redirect()->route('themes.index')->with(["message" => "Item not found"], 404);
            }
            return redirect()->route('themes.index')->with(["message" => "Error en Base de Datos: " . $e->getMessage()], 500);
        }
    }

    public function getListJson()
    {
        $themes = Theme::orderBy('start_position', 'ASC')->get();

        $themesDataTable = array();
        $themesLastDataTable = array();

        foreach($themes as $theme) {
            switch($theme->start_position > 0 && $theme->start_position <= 9) {
                case 1:
                    $themesDataTable = $this->loadArray($theme, $themesDataTable);
                    break;
                case 0:
                    $themesLastDataTable = $this->loadArray($theme, $themesLastDataTable);
                    break;
            }
        }

        for($i = count($themesDataTable) - 1; $i > 0; $i--) {
            $aux = $themesDataTable[$i - 1];
            $current  = $themesDataTable[$i];
            $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $aux['updateAt']);
            $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $current['updateAt']);

            if ( $aux['order'] == $current['order'] && $date2->gte($date1)) {
                $themesDataTable[$i - 1] = $themesDataTable[$i];
                $themesDataTable[$i] = $aux;
            }
        }

        foreach ( $themesLastDataTable as $keyData => $themeData ) {
            array_push($themesDataTable, $themeData);
        }

        foreach ( $themesDataTable as $key => $theme ) {
            $themeId = $themesDataTable[$key]['id'];
            $themesDataTable[$key]['order'] = $key + 1;
            $themeOrder = $themesDataTable[$key]['order'];
            $theme = Theme::findOrFail($themeId);
            if ( $theme != null ) {
                if ( $themeOrder >= 1 && $themeOrder <= 9 ) {
                    $theme->start_position = $themeOrder;
                } else {
                    $theme->start_position = 0;
                }
                $theme->save();
            }
        }

        $response = array(
            "draw" => intval(10),
            "iTotalRecords" => count($themesDataTable),
            "iTotalDisplayRecords" => count($themesDataTable),
            "aaData" => $themesDataTable
        );
        echo json_encode($response);
    }

    private function loadArray($theme, Array $themes)
    {
        $data = array(
            ''              => '',
            'id'            => $theme->id,
            'name'          => $theme->name == null ? '' : $theme->name,
            'section'       => '',
            'indexed'       => $theme->indexed,
            'order'         => $theme->start_position,
            'updateAt'      => Carbon::parse($theme->updated_at)->format('Y-m-d H:i:s'),
        );

        $sections = $theme->sections;

        foreach ( $sections as $key => $section ) {
            $data['section'] .= $section->section->name;
            if ( count( $sections ) -1 != $key ) {
                $data['section'] .= ', ';
            }
        }

        array_push( $themes, $data );
        return $themes;
    }

    public function getSlug(Request $request)
    {
        $name   = $request->name;
        $slug   = Str::slug($name);
        $slug   = substr( $slug, 0, 190 );
        echo json_encode($slug);
    }

    public function getJson()
    {
        $tags = Theme::all();

        $data = array();

        foreach ($tags as $key => $tag) {
            $data[$tag->id] = $tag->name;
        }

        echo json_encode($data);
    }
}
