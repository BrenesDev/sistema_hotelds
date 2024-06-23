<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


use Throwable;

class ReporteController extends Controller
{

    public function vista_reportes()
    {
        try {
            $info = DB::select('SELECT nombre, ruta_logo FROM tb_config WHERE id = 1');
            $habitaciones = DB::SELECT("SELECT * FROM tb_habitaciones WHERE activo = 1");
            return view("reportes")->with(["habitaciones" => $habitaciones, "infoHotel" => $info]);
        } catch (Throwable $th) {
            return view("error");
        }
    }

    public function filtrar_reservaciones(Request $request)
    {
        try {
            $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->startOfDay();
            $fechaFin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->endOfDay();
            $filtro_hab = $request->select_hab;
            $info = DB::select('SELECT nombre, ruta_logo FROM tb_config WHERE id = 1');

            $query = "SELECT * FROM tb_reservaciones WHERE fecha_ingreso BETWEEN ? AND ?";
            $params = [$fechaInicio, $fechaFin];

            if ($filtro_hab != "todas") {
                $query .= " AND habitacion = ?";
                $params[] = $filtro_hab;
            }

            $reservaciones = DB::select($query, $params);
            $total_col = 0;
            $total_dol = 0;

            // Recorrer las reservaciones para calcular días y costo total
            foreach ($reservaciones as $reservacion) {
                
                $fechaInicial = Carbon::createFromFormat('Y-m-d H:i:s', $reservacion->fecha_ingreso);
                $fechaFinal = Carbon::createFromFormat('Y-m-d H:i:s', $reservacion->fecha_salida);
                $diferencia = $fechaInicial->diffInDays($fechaFinal) + 1;
                $precioPorNoche = 0;

                $habitacion = DB::table('tb_habitaciones')->where('id', $reservacion->habitacion)->first();
                $costoTotal = 0;

                if ($reservacion->tipo_pago == 1) {
                    $precioPorNoche = $habitacion->costo_dolar;
                    $costoTotal = $diferencia * $precioPorNoche;
                    $total_dol += $costoTotal;
                } else {
                    $precioPorNoche = $habitacion->costo_colon;
                    $costoTotal = $diferencia * $precioPorNoche;
                    $total_col += $costoTotal;
                }
                // Agregar días y costo total a la reserva
                $reservacion->dias = $diferencia;
                $reservacion->costo_total = $costoTotal;
                $reservacion->id_hab = $habitacion->idHotel;
            }

            $habitaciones = DB::SELECT("SELECT * FROM tb_habitaciones WHERE activo = 1");

            return view("reportes", [
                "habitaciones" => $habitaciones,
                "reservaciones" => $reservaciones,
                "total_col" => $total_col,
                "total_dol" => $total_dol,
                "infoHotel" => $info,
            ]);
        } catch (Throwable $th) {
            return view("error");
        }
    }
}
