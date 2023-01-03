<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function getJson()
    {
        $tags = Status::all();

        $data = array();

        foreach ($tags as $key => $tag) {
            $data[$tag->id] = $tag->name;
        }

        echo json_encode($data);
    }
}
