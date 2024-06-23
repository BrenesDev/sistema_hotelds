<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Throwable;

class HomeController extends Controller
{
    public function home()
    {
        try {
            //extraigo las hhabitaciones del hotel
            $habitaciones = DB::select(DB::raw('SELECT * FROM tb_habitaciones'));

            //también necesito extraer el nombre y el logo del hotel
            $info = DB::select('SELECT nombre, ruta_logo FROM tb_config WHERE id = 1');

            return view("home", [
                "habitaciones" => $habitaciones,
                "infoHotel" => $info,
            ]);
        } catch (Throwable $th) {
            return view("error");
        }
    }
}
