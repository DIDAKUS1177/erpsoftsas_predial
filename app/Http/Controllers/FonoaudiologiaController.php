<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RemisionesCreateFormRequest;
use App\Http\Requests\RemisionesUpdateFormRequest;
use App\Http\Requests\HistoriasMedicasCreateFormRequest;
use App\Http\Requests\HistoriasMedicasUpdateFormRequest;

use Carbon\Carbon;

use App\Models\Opcion;
use App\Models\HistorialMedico;
use App\Models\Remision;

class FonoaudiologiaController extends Controller
{
    public function Data(){
        return session("juego");
    }

    public function dislex_game(Request $request, $id, $id_cita) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $ide_cita = base64_decode($id_cita);

        $cita = DB::table('citas')->join('pacientes', 'citas.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                                ->join('fonoaudiologos', 'citas.fonoaudiologos_ide_fon', '=', 'fonoaudiologos.ide_fon')
                                ->join('usuarios', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
                                ->select(DB::raw('citas.*, CONCAT(pacientes.nom_pac, \' \', pacientes.ape_pac) AS nom_pac, CONCAT(usuarios.nom_usu, \' \', usuarios.ape_usu) AS nom_usu, citas.*'))
                                ->where('citas.ide_cit', $ide_cita)
                                ->get()->first();

        $game=$cita->pacientes_ide_pac."&".$cita->fonoaudiologos_ide_fon."&".$cita->nom_pac;
        $request->session()->put('juego', $game);
        return redirect('/jueguito');

        //return view('fono.jugar', ['opcion' => $opcion,
        //                           'cita' => $cita]);
    }

    public function historial_medico(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();

        $numero = DB::table('historiales_medicos')
                    ->select(DB::raw('IFNULL(max(num_hme), 0) + 1 as numero'))
                    ->get();

        $pacientes = DB::table('pacientes')
                        ->select('ide_pac', 'nom_pac', 'ape_pac')
                        ->get();

        $fonoaudiologos = DB::table('fonoaudiologos')->join('usuarios', function ($join) {
                            $join->on('fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu');
                        })
                        ->select('usuarios.nom_usu', 'usuarios.ape_usu', 'fonoaudiologos.*')
                        ->get();

        $historias = DB::table('historiales_medicos')->join('pacientes', 'historiales_medicos.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                    ->join('fonoaudiologos', 'historiales_medicos.fonoaudiologos_ide_fon', '=', 'fonoaudiologos.ide_fon')
                    ->join('usuarios', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
                    ->select('historiales_medicos.*', 'pacientes.nom_pac', 'pacientes.ape_pac', 'usuarios.nom_usu', 'usuarios.ape_usu')->get();


        $dt = Carbon::now();

        return view('fono.historial', ['opcion' => $opcion,
                                       'pacientes' => $pacientes,
                                       'fonoaudiologos' => $fonoaudiologos,
                                       'historias' => $historias,
                                       'datenow' => $dt->toDateString(),
                                       'timenow' => $dt->toTimeString(),
                                       'numero' => $numero[0]]);
    }

    public function remision(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();

        $pacientes = DB::table('pacientes')
                        ->select('ide_pac', 'nom_pac', 'ape_pac')
                        ->get();

        $fonoaudiologos = DB::table('fonoaudiologos')->join('usuarios', function ($join) {
                            $join->on('fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu');
                        })
                        ->select('usuarios.nom_usu', 'usuarios.ape_usu', 'fonoaudiologos.*')
                        ->get();

        $remisiones = DB::table('remisiones')->join('pacientes', 'remisiones.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                    ->join('fonoaudiologos', 'remisiones.fonoaudiologos_ide_fon', '=', 'fonoaudiologos.ide_fon')
                    ->join('usuarios', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
                    ->select('remisiones.*', 'pacientes.nom_pac', 'pacientes.ape_pac', 'usuarios.nom_usu', 'usuarios.ape_usu')->get();


        $dt = Carbon::now();

        return view('fono.remision', ['opcion' => $opcion,
                                      'pacientes' => $pacientes,
                                      'fonoaudiologos' => $fonoaudiologos,
                                      'remisiones' => $remisiones]);
    }

    public function reporte_pacientes(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $pacientes = DB::table('pacientes')->get();

        return view('aten.reporte_pacientes', ['opcion' => $opcion,
                                               'pacientes' => $pacientes]);
    }

    public function citas_fonoaudiologo(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();

        if($request->session()->get('tipos_usuarios_ide_tip') == '1') {
            $todas_citas = DB::table('citas')->join('pacientes', 'citas.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                                    ->join('fonoaudiologos', 'citas.fonoaudiologos_ide_fon', '=', 'fonoaudiologos.ide_fon')
                                    ->join('usuarios', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
                                    ->select(DB::raw('citas.*, CONCAT(pacientes.nom_pac, \' \', pacientes.ape_pac) AS nom_pac, CONCAT(usuarios.nom_usu, \' \', usuarios.ape_usu) AS nom_usu, citas.*'))
                                    ->orderByRaw('STR_TO_DATE(CONCAT(citas.fec_cit, \' \', citas.hor_cit), \'%Y-%m-%d %H:%i:%s\') desc')
                                    ->get();
        }
        else {
            $todas_citas = DB::table('citas')->join('pacientes', 'citas.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                                    ->join('fonoaudiologos', 'citas.fonoaudiologos_ide_fon', '=', 'fonoaudiologos.ide_fon')
                                    ->join('usuarios', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
                                    ->select(DB::raw('citas.*, CONCAT(pacientes.nom_pac, \' \', pacientes.ape_pac) AS nom_pac, CONCAT(usuarios.nom_usu, \' \', usuarios.ape_usu) AS nom_usu, citas.*'))
                                    ->where('fonoaudiologos.usuarios_ide_usu', $request->session()->get('userid'))
                                    ->orderByRaw('STR_TO_DATE(CONCAT(citas.fec_cit, \' \', citas.hor_cit), \'%Y-%m-%d %H:%i:%s\') desc')
                                    ->get();
        }

        $dt = Carbon::now();

        $today = Carbon::today(); // 2017-04-01 00:00:00
        $today_str = str_replace('00:00:00', '08:00:00', $today->toDateTimeString());
        $today = Carbon::createFromFormat('Y-m-d H:i:s', $today_str);

        $today_end_str = str_replace('08:00:00', '17:00:00', $today->toDateTimeString());
        $today_end = Carbon::createFromFormat('Y-m-d H:i:s', $today_end_str);

        $allTimes = [];
        array_push($allTimes, $today->toTimeString()); //add the 08:00 time before looping
        while ($today < $today_end):
            $today->addMinutes(30); // add 0, 30, 60, etc...
            array_push($allTimes, $today->toTimeString()); // inserts the time into the array like 00:00:00, 00:30:00, etc.
        endwhile;

        //$request->session()->put('search', '1');

        return view('fono.citas_fonoaudiologo', ['opcion' => $opcion,
                                   'allTimes' => $allTimes,
                                   'todas_citas' => $todas_citas,
                                   'datenow' => $dt->toDateString(),
                                   'timenow' => $dt->toTimeString()]);
    }

    public function createhist(HistoriasMedicasCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'fec_hme'  => 'required',
        //     'num_hme'  => 'required',
        //     'mot_hme'  => 'required',
        //     'dif_hme'  => 'required',
        //     'ide_pac'  => 'required',
        //     'ide_fon'  => 'required'
        // ];

        // $customMessages = [
        //     'fec_hme.required' => 'El campo Fecha es obligatorio.',
        //     'num_hme.required' => 'El campo Número es obligatorio.',
        //     'mot_hme.required' => 'El campo Motivo es obligatorio.',
        //     'dif_hme.required' => 'El campo Dificultades es obligatorio.',
        //     'ide_pac.required' => 'El campo Paciente es obligatorio.',
        //     'ide_fon.required' => 'El campo Fonoaudiologo es obligatorio.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $historia = new HistorialMedico;

        $historia->fec_hme = $request->fec_hme;
        $historia->num_hme = $request->num_hme;
        $historia->mot_hme = $request->mot_hme;
        $historia->dif_hme = $request->dif_hme;

        $historia->pacientes_ide_pac = $request->ide_pac;
        $historia->fonoaudiologos_ide_fon = $request->ide_fon;

        $query = $historia->save();
        $tab_current = 'li-section-bar-1';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function updatehist(HistoriasMedicasUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'fec_hme_edit'  => 'required',
        //     'num_hme_edit'  => 'required',
        //     'mot_hme_edit'  => 'required',
        //     'dif_hme_edit'  => 'required',
        //     'pacientes_ide_pac_edit'  => 'required',
        //     'fonoaudiologos_ide_fon_edit'  => 'required'
        // ];

        // $customMessages = [
        //     'fec_hme_edit.required' => 'El campo Fecha es obligatorio.',
        //     'num_hme_edit.required' => 'El campo Número es obligatorio.',
        //     'mot_hme_edit.required' => 'El campo Motivo es obligatorio.',
        //     'dif_hme_edit.required' => 'El campo Dificultades es obligatorio.',
        //     'pacientes_ide_pac_edit.required' => 'El campo Paciente es obligatorio.',
        //     'fonoaudiologos_ide_fon_edit.required' => 'El campo Fonoaudiologo es obligatorio.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $historia = new HistorialMedico;

        $historia = HistorialMedico::find($request->ide_hme_edit);

        $historia->fec_hme = $request->fec_hme_edit;
        $historia->num_hme = $request->num_hme_edit;
        $historia->mot_hme = $request->mot_hme_edit;
        $historia->dif_hme = $request->dif_hme_edit;

        $historia->pacientes_ide_pac = $request->pacientes_ide_pac_edit;
        $historia->fonoaudiologos_ide_fon = $request->fonoaudiologos_ide_fon_edit;

        $query = $historia->save();
        $tab_current = 'li-section-bar-2';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se actualiz&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo actualizar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function createrem(RemisionesCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'mot_rem'  => 'required',
        //     'don_rem'  => 'required',
        //     'ide_pac'  => 'required',
        //     'ide_fon'  => 'required'
        // ];

        // $customMessages = [
        //     'mot_rem.required' => 'El campo Motivo es obligatorio.',
        //     'don_rem.required' => 'El campo Lugar de remisión es obligatorio.',
        //     'ide_pac.required' => 'El campo Paciente es obligatorio.',
        //     'ide_fon.required' => 'El campo Fonoaudiologo es obligatorio.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $remision = new Remision;

        $remision->mot_rem = $request->mot_rem;
        $remision->don_rem = $request->don_rem;

        $remision->pacientes_ide_pac = $request->ide_pac;
        $remision->fonoaudiologos_ide_fon = $request->ide_fon;

        $query = $remision->save();
        $tab_current = 'li-section-bar-1';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function updaterem(RemisionesUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'mot_rem_edit'  => 'required',
        //     'don_rem_edit'  => 'required',
        //     'pacientes_ide_pac_edit'  => 'required',
        //     'fonoaudiologos_ide_fon_edit'  => 'required'
        // ];

        // $customMessages = [
        //     'mot_rem.required' => 'El campo Motivo es obligatorio.',
        //     'don_rem.required' => 'El campo Lugar de remisión es obligatorio.',
        //     'pacientes_ide_pac_edit.required' => 'El campo Paciente es obligatorio.',
        //     'fonoaudiologos_ide_fon_edit.required' => 'El campo Fonoaudiologo es obligatorio.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $remision = new Remision;

        $remision = Remision::find($request->ide_rem_edit);

        $remision->mot_rem = $request->mot_rem_edit;
        $remision->don_rem = $request->don_rem_edit;

        $remision->pacientes_ide_pac = $request->pacientes_ide_pac_edit;
        $remision->fonoaudiologos_ide_fon = $request->fonoaudiologos_ide_fon_edit;

        $query = $remision->save();
        $tab_current = 'li-section-bar-2';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se actualiz&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo actualizar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function deleterem(Request $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $remision = new Remision;
        $remision = Remision::find($request->input_delete);
        $query = $remision->delete();

        $tab_current = 'li-section-bar-2';

        if($query) {
            return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }
}
