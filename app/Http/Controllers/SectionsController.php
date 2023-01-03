<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    public function getJson()
    {
        $sections = Section::all();

        $data = array();

        foreach ($sections as $key => $section) {
            $newData = array(
                'id'    => $section->id,
                'name'  => $section->name,
                'code'  => $section->code,
            );
            array_push( $data, $newData );
        }

        echo json_encode($data);
    }
}
