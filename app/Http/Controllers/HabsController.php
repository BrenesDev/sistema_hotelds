<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ConversionService;
use Throwable;

class HabsController extends Controller
{
    public function paginaHabitaciones()
    {
        try {
            $info = DB::select('SELECT nombre, ruta_logo FROM tb_config WHERE id = 1');
            $habitaciones = DB::select(DB::raw('SELECT * FROM tb_habitaciones'));
            if($info && $habitaciones){
                return view("vistaHabitaciones")->with(["habitaciones" => $habitaciones, "infoHotel" => $info]);
            }
        } catch (Throwable $e) {
            //report($e);
            return redirect()->view("error");
        }
    }

    //se crea una funcion que se utiliza para obtener el cambio del dolar en la base de datos con el objetivo
    //que sea reutilizable
    public function obtenerCambioDolar()
    {
        //puedo hacer una consulta a la tabla de configuraciones donde se tiene actualizado el tipo de cambio
        //del dolar y realizar la conversión ?? si para que automaticamente se realice la conversión

        try {
            //se obtiene el ultimo registro de configuraciones de la base de datos
            $ultimo_registro = DB::table('tb_config')
                ->orderBy('id', 'desc')
                ->first();
            //manejo de que haya registros de config dentro de la base de datos
            if ($ultimo_registro) {
                //se obtiene el tipo de cambio
                $tipo_cambio = $ultimo_registro->tipo_cambio;
                return $tipo_cambio;
            } else {
                //se retorna a una vista con el mensaje de error especifico
                return view("error");
            }
        } catch (Throwable $th) {
        }
    }

    public function agregar(Request $request)
    {
        //obtenemos el ultimo tipo de cambio actualizado en la bd
        $tipo_cambio = $this->obtenerCambioDolar();
        //obtenemos el costo en dolares
        $costo_dolar = $request->costo;
        //realizamos la conversión usando el service creado
        $costo_colon = ConversionService::convertirDolarColones($costo_dolar, $tipo_cambio);

        //el estado en este caso como estamos agregando una nueva habitacion, por defecto va en disponible = 0

        try {
            $query  = DB::insert('INSERT INTO tb_habitaciones(idHotel, capacidad, costo_dolar, costo_colon, estado, tipo, activo)
                    VALUES(?,?,?,?,?,?,?)', [
                $request->numerohabitacion,
                $request->capacidad,
                $request->costo,
                $costo_colon,
                0,
                $request->tipo,
                1,
            ]);
        } catch (Throwable $th) {
            $query = 0;
            $error = $th->getMessage();
        }

        if ($query == true) {
            return back()->with("correcto", "Habitación agregada al sistema");
        } else {
            return view("error");
        }
    }

    public function actualizar(Request $request)
    {
        // Se obtiene el tipo de cambio actualizado para realizar la conversión
        $tipo_cambio = $this->obtenerCambioDolar();
        $costo = $request->costoA;
        $costo_colon = ConversionService::convertirDolarColones($costo, $tipo_cambio);

        try {
            $id_hab = $request->idA;
            $capacidad = $request->capacidadA;
            $costo_dolar = $request->costoA;
            $tipo = $request->tipoA;

            $query = DB::update("
            UPDATE tb_habitaciones
            SET capacidad = ?,
                costo_dolar = ?,
                costo_colon = ?,
                tipo = ?
            WHERE id = ?
        ", [$capacidad, $costo_dolar, $costo_colon, $tipo, $id_hab]);

            if ($query) {
                // Si la actualización fue exitosa
                return back()->with('correcto', 'Habitación actualizada exitosamente');
            } else {
                // Si hubo un error en la actualización
                return back()->with('error', 'Error al actualizar la habitación');
            }
        } catch (Throwable $th) {
            // Manejo de excepciones
            return view("error");
        }
    }

    public function eliminar($id)
    {
        // Si estás en un namespace diferente, asegúrate de incluir Redirect en los imports

        try {
            $query = DB::update("
                UPDATE tb_habitaciones
                SET activo = 0
                WHERE id = ?
            ", [$id]);

            if ($query) {
                return back()->with("correcto", "Se ha desactivado la habitación en el sistema.");
            } else {
                return back()->with("error", "No se pudo desactivar la habitación");
            }
        } catch (Throwable $th) {
            return back()->with('error', 'Ocurrió un error inesperado, intente nuevamente o comuníquese con el administrador');
        }
    }
}
