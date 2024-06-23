<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Throwable;

class InfoController extends Controller
{
    public function actualizar_cambio($compra)
    {
        $tipo_compra = $compra;
        try {
            DB::update('UPDATE tb_config SET tipo_cambio = ? WHERE id = 1', [$compra]);
            $this->actualizarPrecioColon($compra);
            return response()->json(['success' => true, 'message' => 'Tipo de cambio actualizado.']);
        } catch (Throwable $th) {
            return response()->json(['error' => true, 'message' => 'Error Interno.']);
        }
    }

    //metodo para actualizar el precio en colones en las habitaciones
    public function actualizarPrecioColon($compra)
    {
        try {
            //obtengo todas las habitaciones de la base de datos
            $habitaciones = DB::select(DB::raw('SELECT * FROM tb_habitaciones'));
            //recorro todas las habitaciones para actualizar el precio_colon según la compra.
            foreach ($habitaciones as $habitacion) {
                $precio_dolar = $habitacion->costo_dolar;
                $precio_colon = round((float)$precio_dolar * (float)$compra, 2);

                DB::table('tb_habitaciones')
                    ->where('id', $habitacion->id)
                    ->update([
                        'costo_colon' => $precio_colon
                    ]);
            }
        } catch (Throwable $th) {
            return view("error");
        }
    }

    public function actualizar_info(Request $request)
    {
        try {
            // Validar el formulario
            $request->validate([
                'nombre_hotel' => 'required|string|max:255',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB máximo, ajusta según necesites
            ]);

            // Obtener el archivo de imagen del formulario
            $imagen = $request->file('foto');

            // Obtener el nombre original del archivo
            $nombreOriginal = $imagen->getClientOriginalName();

            // Generar un nombre único para la imagen
            $nombreImagen = Str::slug(pathinfo($nombreOriginal, PATHINFO_FILENAME)) . '-' . time() . '.' . $imagen->getClientOriginalExtension();

            // Guardar la imagen en la carpeta "public/uploads"
            $imagen->storeAs('public/uploads', $nombreImagen);

            // Actualizar la ruta del logo en la base de datos
            DB::table('tb_config')->where('id', 1)->update(['ruta_logo' => 'storage/uploads/' . $nombreImagen, 'nombre' => $request->nombre_hotel]);

            // Redireccionar con un mensaje de éxito
            return redirect()->back();
        } catch (Throwable $th) {
            return view("error");
        }
    }

    public function actualizar_horario(Request $request)
    {
        try {
            // Obtener los datos de la solicitud
            $hora_inicio = $request->hora_ini;
            $hora_fin = $request->hora_end;
            $dia_inicio = $request->dia_ini;
            $dia_fin = $request->dia_end;

            // Verificar si hay un registro existente
            $existingRecord = DB::table('tb_horario')->first();

            if ($existingRecord) {
                // Actualizar el registro existente
                $result = DB::update(
                    "UPDATE tb_horario 
                SET dia_inicio = ?, dia_fin = ?, hora_inicio = ?, hora_fin = ?",
                    [$dia_inicio, $dia_fin, $hora_inicio, $hora_fin]
                );
            } else {
                // Insertar un nuevo registro
                $result = DB::insert(
                    "INSERT INTO tb_horario (dia_inicio, dia_fin, hora_inicio, hora_fin) 
                VALUES (?, ?, ?, ?)",
                    [$dia_inicio, $dia_fin, $hora_inicio, $hora_fin]
                );
            }

            // Verificar si la consulta fue exitosa
            if ($result) {
                // La actualización o inserción fue exitosa
                return redirect()->route('home');
            }
        } catch (Throwable $th) {
            return "error:" . $th;
        }
    }
}
