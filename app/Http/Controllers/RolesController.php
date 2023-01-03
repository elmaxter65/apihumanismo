<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function getListJson()
    {
        $roles = Role::all();

        $data = array();

        foreach ($roles as $key => $role) {
            $data[$role->id] = $role->name;
        }

        echo json_encode($data);
    }
}
