<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\TagRequest;
use App\Models\TagLanguage;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index()
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('tags.index', compact('pageConfigs'));
    }

    public function getJson()
    {
        $tags = Tag::all();

        $tagsDataTable = array();

        foreach( $tags as $key => $tag ) {
            $tagsDataTable = $this->loadArray( $tag, $tagsDataTable );
        }

        foreach ( $tagsDataTable as $key => $tag ) {
            $tagsDataTable[$key]['order'] = $key + 1;
        }

        $response = array(
            "draw"                  => intval(10),
            "iTotalRecords"         => count($tagsDataTable),
            "iTotalDisplayRecords"  => count($tagsDataTable),
            "aaData"                => $tagsDataTable
        );
        echo json_encode($response);
    }

    private function loadArray($tag, Array $tags)
    {
        $name = '';

        if ( $tag->taglanguage != null &&  count($tag->taglanguage) >= 1 ) {
            $name = $tag->taglanguage[0]->name;
        }

        if ( $name == '' ) return $tags;

        $data = array(
            'order'         => '',
            'id'            => $tag->id,
            'name'          => $name,
            ''              => '',
        );

        array_push( $tags, $data );
        return $tags;
    }

    public function create()
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('tags.create', compact('pageConfigs'));
    }

    public function edit($id)
    {
        $pageConfigs = ['pageHeader' => false,];
        $tag = Tag::findOrFail( $id );
        return view('tags.edit', compact( 'tag', 'pageConfigs'));
    }

    public function store(TagRequest $request)
    {
        DB::beginTransaction();
        try {
            $tag = Tag::create();

            $dataLanguage = array(
                'tag_id'        => $tag->id,
                'language_id'   => 1,
                'name'          => $request->name,
                'slug'          => $request->slug,
            );

            $tag = TagLanguage::create( $dataLanguage );

            DB::commit();
            return redirect()->route('tags.index')->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store. Server Error');
        }
    }

    public function update(TagRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $tag = Tag::findOrFail( $id );
            $tagsLanguage = TagLanguage::where( 'tag_id', $id )->get();

            if ($tagsLanguage == null ) return back()->with('error', 'Item not store. Server Error');
            if ( count( $tagsLanguage ) < 1 ) return back()->with('error', 'Item not store. Server Error');

            foreach( $tagsLanguage as $key => $tagLanguage ) {
                $tagLanguage = TagLanguage::findOrFail( $tagLanguage->id );
                $tagLanguage->delete();
            }

            $dataLanguage = array(
                'tag_id'        => $tag->id,
                'language_id'   => 1,
                'name'          => $request->name,
                'slug'          => $request->slug,
            );

            $tag = TagLanguage::create( $dataLanguage );

            DB::commit();
            return redirect()->route('tags.edit', [$id])->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store. Server Error');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $template = Tag::findOrFail($id);
            $template->delete();
            //dd( $template );
            DB::commit();
            return redirect()->route('tags.index')->with(["message" => "Item delete success"], 200);
        } catch (Exception $e) {
            //dd( $template );
            DB::rollback();
            if ($e instanceof ModelNotFoundException) {
                return redirect()->route('tags.index')->with(["message" => "Item not found"], 404);
            }
            return redirect()->route('tags.index')->with(["message" => "Error en Base de Datos: " . $e->getMessage()], 500);
        }
    }

    public function getSlug(Request $request)
    {
        $name   = $request->name;
        $slug   = Str::slug($name);
        $slug   = substr( $slug, 0, 190 );
        echo json_encode($slug);
    }

    public function getListJson()
    {
        $tags = Tag::all();

        $data = array();



        foreach ($tags as $key => $tag) {
            $name = '';

            if ( $tag->taglanguage != null &&  count($tag->taglanguage) >= 1 ) {
                $name = $tag->taglanguage[0]->name;
            }

            if ( $name == '' ) continue;

            $data[$tag->id] = $name;
        }

        echo json_encode($data);
    }
}
