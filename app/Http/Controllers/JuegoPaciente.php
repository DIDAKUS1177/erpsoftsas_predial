<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JuegoPaciente extends Controller
{
    public function guardar(Request $request) {
        $tipo = $request->all();

        $guardardo = new JuegoPaciente;
        $guardardo->pacientes_ide_pac = $request->pacientes_ide_pac;
        $guardardo->fonoaudiologos_ide_fon = $request->fonoaudiologos_ide_fon;
        $guardardo->juegos_ide_jue = $request->juegos_ide_jue;
        $guardardo->pun_jue = $request->pun_jue;
        $guardardo->save();
        return response(["data" => "guardado"]);
    }

    public function juego() {
        return view('index');
    }
}
