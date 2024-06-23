<?php
// app/Services/CurrencyConversionService.php

namespace App\Services;

class ConversionService
{
    //la funcion se crea static  para que sea accesible sin tener que instanciar el servicio, ya que es una funcion
    //meramente de calculo
    public static function convertirDolarColones   ($dolar, $tipoCambio)
    {
        // Realiza la conversión de dólares a colones
        $colones = $dolar * $tipoCambio;

        return $colones;
    }
}

