<?php

namespace App\Http\Controllers;

use App\Models\EntryType;
use Illuminate\Http\Request;

class PostsTypesController extends Controller
{
    public function getListJson()
    {
        $roles = EntryType::all();

        $data = array();

        foreach ($roles as $key => $role) {
            $data[$role->id] = $role->name;
        }

        echo json_encode($data);
    }
}
