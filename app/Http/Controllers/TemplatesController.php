<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Http\Requests\TemplateRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TemplatesController extends Controller
{
    public function index()
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('templates.index', compact('pageConfigs'));
    }

    public function getJson()
    {
        $templates = Template::where( 'init', 0 )->get();

        $templatesDataTable = array();
        $templatesLastDataTable = array();

        foreach( $templates as $key => $template ) {
            $templatesDataTable = $this->loadArray( $template, $templatesDataTable );
        }

        $response = array(
            "draw" => intval(10),
            "iTotalRecords" => count($templatesDataTable),
            "iTotalDisplayRecords" => count($templatesDataTable),
            "aaData" => $templatesDataTable
        );
        echo json_encode($response);
    }

    private function loadArray($template, Array $templates)
    {
        $data = array(
            ''              => '',
            'id'            => $template->id,
            'name'          => $template->name,
            'indexed'       => $template->index_content,
            ''              => '',
        );

        array_push( $templates, $data );
        return $templates;
    }

    public function create()
    {
        $pageConfigs = ['pageHeader' => false,];
        return view('templates.create', compact('pageConfigs'));
    }

    public function edit($id)
    {
        $pageConfigs = ['pageHeader' => false,];
        $template = Template::findOrFail( $id );
        return view('templates.edit', compact( 'template', 'pageConfigs'));
    }

    public function store(TemplateRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = array(
                'name'              => $request->name,
                'index_content'     => $request->indexed,
                'active'            => $request->visibility == "on" ? 1 : 0,
                'init'              => 0,
            );

            $template = Template::create( $data );

            DB::commit();
            return redirect()->route('templates.index')->with('message', 'Item store successfully');
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return back()->with('error', 'Item not store. Server Error');
        }
    }

    public function update(TemplateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $template = Template::findOrFail( $id );
            $template->name              = $request->name;
            $template->index_content     = $request->indexed;
            $template->active            = $request->visibility == "on" ? 1 : 0;
            $template->save();

            DB::commit();
            return redirect()->route('templates.edit', [$template->id])->with('message', 'Item store successfully');
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
            $template = Template::findOrFail($id);
            $template->delete();
            //dd( $template );
            DB::commit();
            return redirect()->route('templates.index')->with(["message" => "Item delete success"], 200);
        } catch (Exception $e) {
            //dd( $template );
            DB::rollback();
            if ($e instanceof ModelNotFoundException) {
                return redirect()->route('templates.index')->with(["message" => "Item not found"], 404);
            }
            return redirect()->route('templates.index')->with(["message" => "Error en Base de Datos: " . $e->getMessage()], 500);
        }
    }
}
