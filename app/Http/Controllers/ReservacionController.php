<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReservacionController extends Controller
{

  public function desocupar_hab($id_reservacion)
  {
    $id = $id_reservacion;

    try {
      DB::beginTransaction();

      // Cambiar el estado de la reservación
      DB::update('UPDATE tb_reservaciones SET activo = 0  WHERE id = ?', [$id]);

      // Obtener el ID de la habitación asociada a esta reservación
      $id_habitacion = DB::table('tb_reservaciones')
        ->where('id', $id)
        ->value('habitacion');

      if (!$id_habitacion) {
        throw new \Exception("Habitación no encontrada.");
      }

      // Cambiar el estado de la habitación
      DB::update('UPDATE tb_habitaciones SET estado = 0 WHERE id = ?', [$id_habitacion]);

      DB::commit();

      // Si todo va bien, devolvemos una respuesta JSON con el mensaje de éxito
      return response()->json(['success' => true, 'message' => 'Estado cambiado correctamente.']);
    } catch (Throwable $th) {
      DB::rollBack();
      // Si ocurre un error, devolvemos una respuesta JSON con el mensaje de error
      return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
    }
  }
  public function verificarReservacion($id_habitacion)
  {
    try {
      $reserva = DB::select("
              SELECT * FROM tb_reservaciones
              WHERE habitacion = ? AND activo = 1
              LIMIT 1
          ", [$id_habitacion]);

      if (!empty($reserva)) {
        return response()->json(['reservacion_activa' => $reserva[0]]);
      } else {
        return response()->json(['reservacion_inactiva' => null]);
      }
    } catch (Throwable $th) {
      return response()->json(['error' => 'Error al verificar reserva']);
    }
  }

  public function agregar_reserva(Request $request)
  {
    //del request recuperamos unicamente las variables que se van a necesitar o agregar en la base de datos
    //cliente, cantidad huespedes, fecha ingreso, fecha salida, dias pagados, y el estado de reservacion activo
    //se va a guardar el monto pagado y el tipo de moneda con el cual se pagó
    $id_habitacion = $request->id_habitacion;
    //en klugar de usar la fecha del cliente, obtengo la fecha y momento en que se va a relziar la transaccion
    $fecha_transaccion = date('Y-m-d H:i:s');
    $fecha_salida = $request->fecha_salida;
    $dias_pagados =  $request->dias_pago;
    $cliente = $request->nombre_cliente;
    $cantidad_huespedes = $request->cantidad_personas;
    $tipo_pago = $request->tipo_moneda;
    $monto_pagado = $request->monto_cancelar;
    $monto_pendiente = $request->monto_pendiente;

    try {
      $query  = DB::insert('INSERT INTO tb_reservaciones(habitacion, nombre_cliente, cantidad_personas, fecha_ingreso, fecha_salida,
      dias_pagados, tipo_pago, cancelado, pendiente, activo)
              VALUES(?,?,?,?,?,?,?,?,?,?)', [
        $id_habitacion,
        $cliente,
        $cantidad_huespedes,
        $fecha_transaccion,
        $fecha_salida,
        $dias_pagados,
        $tipo_pago,
        $monto_pagado,
        $monto_pendiente,
        1,
      ]);
    } catch (Throwable $th) {
      $query = 0;
      $error = $th->getMessage();
    }

    if ($query == true) {
      //si todo salio bien, actualizamos el estado de la habitacion a reservado
      $this->actualizarEstado($id_habitacion);
      return back()->with("correcto", "Reservación registrada");
    } else {
      return back()->with("error", $error);
    }
  }
  public function actualizarEstado($id_hab)
  {
    DB::table('tb_habitaciones')
      ->where('id', $id_hab)
      ->update(['estado' =>  '1']);
  }

}
