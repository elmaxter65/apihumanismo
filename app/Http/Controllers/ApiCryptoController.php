<?php

namespace App\Http\Controllers;

use App\Models\ApiCrypto;
use Illuminate\Http\Request;

class ApiCryptoController extends Controller
{
    public function getApiCryptoListJson()
    {
        $apiCryptos = ApiCrypto::all();

        $data = array();

        foreach ($apiCryptos as $key => $apiCrypto) {
            $data[$apiCrypto->id] = $apiCrypto->cg_name;
        }

        echo json_encode($data);
    }
}
