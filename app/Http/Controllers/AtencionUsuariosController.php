<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PacientesCreateFormRequest;
use App\Http\Requests\PacientesUpdateFormRequest;
use App\Http\Requests\AcudientesCreateFormRequest;
use App\Http\Requests\AcudientesUpdateFormRequest;
use App\Http\Requests\CitasCreateFormRequest;
use App\Http\Requests\CitasUpdateFormRequest;
use App\Http\Requests\AcudientesPacientesCreateFormRequest;
use App\Http\Requests\AcudientesPacientesUpdateFormRequest;

use Carbon\Carbon;

use App\Models\Opcion;
use App\Models\Paciente;
use App\Models\Acudiente;
use App\Models\AcudientePaciente;
use App\Models\Cita;

use PDF;

class AtencionUsuariosController extends Controller
{
    public function error_request() {
        return redirect('/');
    }

    public function registro_pacientes(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $pacientes = DB::table('pacientes')->get(); //paginate(5);
        //$pacientes->appends($request->all());
        $acudientes = DB::table('acudientes')->get();
        $tipos_acudientes = DB::table('tipos_acudientes')->get();

        $acudientes_pacientes = DB::table('pacientes_has_acudientes')
                                   ->join('pacientes', 'pacientes_has_acudientes.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                                   ->join('acudientes', 'pacientes_has_acudientes.acudientes_ide_acu', '=', 'acudientes.ide_acu')
                                   ->join('tipos_acudientes', 'pacientes_has_acudientes.tipos_acudientes_ide_tac', '=', 'tipos_acudientes.ide_tac')
                                   ->select('pacientes_has_acudientes.*', 'pacientes.nom_pac', 'pacientes.ape_pac', 'acudientes.nom_acu', 'acudientes.ape_acu', 'tipos_acudientes.nom_tac')
                                   ->get();

        $tab_current = 'li-section-bar-1';

        return view('aten.registro_pacientes', ['opcion' => $opcion,
                                                'pacientes' => $pacientes,
                                                'acudientes' => $acudientes,
                                                'tipos_acudientes' => $tipos_acudientes,
                                                'acudientes_pacientes' => $acudientes_pacientes,
                                                'tab_current' => $tab_current]);
    }

    public function reporte_pacientes(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $pacientes = DB::table('pacientes')->get(); //paginate(5);
        //$pacientes->appends($request->all());

        return view('aten.reporte_pacientes', ['opcion' => $opcion,
                                                'pacientes' => $pacientes]);
    }

    public function createpac(PacientesCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'nom_pac'  => 'required',
        //     'ape_pac'  => 'required',
        //     'tid_pac'  => 'required',
        //     'ide_pac'  => 'required|unique:pacientes',
        //     'sex_pac'  => 'required',
        //     'fec_pac'  => 'required',
        //     //'ide_acu'  => 'required',
        //     //'ide_tac'  => 'required',
        //     //'pre_paa'  => 'required'
        // ];

        // $customMessages = [
        //     'nom_pac.required' => 'El campo Nombres es obligatorio.',
        //     'ape_pac.required' => 'El campo Apellidos es obligatorio.',
        //     'tid_pac.required' => 'El campo Tipos identificación es obligatorio.',
        //     'ide_pac.required' => 'El campo Identificación es obligatorio.',
        //     'sex_pac.required' => 'El campo Género es obligatorio.',
        //     'fec_pac.required' => 'El campo Fecha nacimiento es obligatorio.',
        //     //'ide_acu.required' => 'El campo Acudiente es obligatorio.',
        //     //'ide_tac.required' => 'El campo Tipo acudiente es obligatorio.',
        //     //'pre_paa.required' => 'El campo Conocimiento es obligatorio.',
        //     'ide_pac.unique'   => 'La identificación ya existe.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $paciente = new Paciente;

        $paciente->ide_pac = $request->ide_pac;
        $paciente->tid_pac = $request->tid_pac;
        $paciente->nom_pac = $request->nom_pac;
        $paciente->ape_pac = $request->ape_pac;
        $paciente->sex_pac = $request->sex_pac;

        $date_variable = $request->fec_pac;
        $paciente->fec_pac = $date_variable;

        if($request->has('gra_pac')) {
            $paciente->gra_pac = $request->gra_pac;
        }
        else {
            $paciente->gra_pac = '';
        }

        $query = $paciente->save();
        $tab_current = 'li-section-bar-1';

        if($query) {

            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);

            // $acudiente_paciente = new AcudientePaciente;

            // $acudiente_paciente->pre_paa = $request->pre_paa;
            // $acudiente_paciente->pacientes_ide_pac = $request->ide_pac;
            // $acudiente_paciente->acudientes_ide_acu = $request->ide_acu;
            // $acudiente_paciente->tipos_acudientes_ide_tac = $request->ide_tac;

            // $query = $acudiente_paciente->save();

            // if($query) {
            //     return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            // }
            // else {
            //     return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            // }
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function updatepac(PacientesUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'nom_pac_edit'  => 'required',
        //     'ape_pac_edit'  => 'required',
        //     'tid_pac_edit'  => 'required',
        //     'ide_pac_edit'  => 'required',
        //     'sex_pac_edit'  => 'required',
        //     'fec_pac_edit'  => 'required',
        //     // 'acudientes_ide_acu_edit'  => 'required'
        // ];

        // $customMessages = [
        //     'nom_pac_edit.required' => 'El campo Nombres es obligatorio.',
        //     'ape_pac_edit.required' => 'El campo Apellidos es obligatorio.',
        //     'tid_pac_edit.required' => 'El campo Tipos identificación es obligatorio.',
        //     'ide_pac_edit.required' => 'El campo Identificación es obligatorio.',
        //     'sex_pac_edit.required' => 'El campo Género es obligatorio.',
        //     'fec_pac_edit.required' => 'El campo Fecha nacimiento es obligatorio.',
        //     // 'acudientes_ide_acu_edit.required' => 'El campo Acudiente es obligatorio.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $paciente = new Paciente;

        $paciente = Paciente::find($request->ide_pac_edit);

        $paciente->tid_pac = $request->tid_pac_edit;
        $paciente->nom_pac = $request->nom_pac_edit;
        $paciente->ape_pac = $request->ape_pac_edit;
        $paciente->sex_pac = $request->sex_pac_edit;

        $date_variable = $request->fec_pac_edit;
        $paciente->fec_pac = $date_variable;
        // $paciente->acudientes_ide_acu = $request->acudientes_ide_acu_edit;

        if($request->has('gra_pac_edit')) {
            $paciente->gra_pac = $request->gra_pac_edit;
        }
        else {
            $paciente->gra_pac = '';
        }

        $query = $paciente->save();
        $tab_current = 'li-section-bar-2';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se actualiz&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo actualizar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function deletepac(Request $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $countPacientes = DB::table('pacientes')
                            ->leftJoin('historiales_medicos', 'historiales_medicos.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                            ->leftJoin('remisiones', 'remisiones.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                            ->leftJoin('pacientes_has_acudientes', 'pacientes_has_acudientes.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                            ->leftJoin('citas', 'citas.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                            ->select(DB::raw('count(*) as pacientes_count'))
                            ->where('pacientes.ide_pac', $request->input_delete)
                            ->where(function($query) {
                                $query->orWhereNotNull('historiales_medicos.pacientes_ide_pac')
                                      ->orWhereNotNull('remisiones.pacientes_ide_pac')
                                      ->orWhereNotNull('pacientes_has_acudientes.pacientes_ide_pac')
                                      ->orWhereNotNull('citas.pacientes_ide_pac');
                            })->first();

        $tab_current = 'li-section-bar-2';
        $paciente = new Paciente;
        $paciente = Paciente::find($request->input_delete);

        if($countPacientes->pacientes_count == 0) {
            $query = $paciente->delete();

            if($query) {
                return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        }
        else {
            return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. El paciente <b>' . $paciente->nom_pac . ' ' . $paciente->ape_pac . '</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
        }
    }

    public function registro_acudientes(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $acudientes = DB::table('acudientes')->get(); //paginate(5);
        //$acudientes->appends($request->all());
        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('search', '1');

        return view('aten.registro_acudientes', ['opcion' => $opcion,
                                                'acudientes' => $acudientes,
                                                'tab_current' => $tab_current]);
    }

    public function createacu(AcudientesCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'nom_acu'  => 'required',
        //     'ape_acu'  => 'required',
        //     'tid_acu'  => 'required',
        //     'ide_acu'  => 'required|unique:acudientes',
        //     'tel_acu'  => 'required'
        // ];

        // $customMessages = [
        //     'nom_acu.required' => 'El campo Nombres es obligatorio.',
        //     'ape_acu.required' => 'El campo Apellidos es obligatorio.',
        //     'tid_acu.required' => 'El campo Tipos identificación es obligatorio.',
        //     'ide_acu.required' => 'El campo Identificación es obligatorio.',
        //     'tel_acu.required' => 'El campo Teléfono es obligatorio.',
        //     'ide_acu.unique'   => 'La identificación ya existe.'
        // ];

        // $this->validate($request, $rules, $customMessages);

        $acudiente = new Acudiente;

        $acudiente->ide_acu = $request->ide_acu;
        $acudiente->tid_acu = $request->tid_acu;
        $acudiente->nom_acu = $request->nom_acu;
        $acudiente->ape_acu = $request->ape_acu;
        $acudiente->tel_acu = $request->tel_acu;
        if($request->has('dir_acu')) {
            $acudiente->dir_acu = $request->dir_acu;
        }
        else {
            $acudiente->dir_acu = '';
        }

        $query = $acudiente->save();
        $tab_current = 'li-section-bar-1';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function updateacu(AcudientesUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'nom_acu_edit'  => 'required',
        //     'ape_acu_edit'  => 'required',
        //     'tid_acu_edit'  => 'required',
        //     'ide_acu_edit'  => 'required',
        //     'tel_acu_edit'  => 'required'
        // ];

        // $customMessages = [
        //     'nom_acu_edit.required' => 'El campo Nombres es obligatorio.',
        //     'ape_acu_edit.required' => 'El campo Apellidos es obligatorio.',
        //     'tid_acu_edit.required' => 'El campo Tipos identificación es obligatorio.',
        //     'ide_acu_edit.required' => 'El campo Identificación es obligatorio.',
        //     'tel_acu_edit.required' => 'El campo Teléfono es obligatorio.'
        // ];

        // $this->validate($request, $rules, $customMessages);

        $acudiente = new Acudiente;

        $acudiente = Acudiente::find($request->ide_acu_edit);

        $acudiente->tid_acu = $request->tid_acu_edit;
        $acudiente->nom_acu = $request->nom_acu_edit;
        $acudiente->ape_acu = $request->ape_acu_edit;
        $acudiente->tel_acu = $request->tel_acu_edit;
        if($request->has('dir_acu_edit')) {
            $acudiente->dir_acu = $request->dir_acu_edit;
        }
        else {
            $acudiente->dir_acu = '';
        }

        $query = $acudiente->save();
        $tab_current = 'li-section-bar-2';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se actualiz&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo actualizar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function deleteacu(Request $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $countAcudientes = DB::table('acudientes')
                            ->leftJoin('pacientes_has_acudientes', 'pacientes_has_acudientes.acudientes_ide_acu', '=', 'acudientes.ide_acu')
                            ->select(DB::raw('count(*) as acudientes_count'))
                            ->where('acudientes.ide_acu', $request->input_delete)
                            ->where(function($query) {
                                $query->orWhereNotNull('pacientes_has_acudientes.acudientes_ide_acu');
                            })->first();

        $tab_current = 'li-section-bar-2';
        $acudiente = new Acudiente;
        $acudiente = Acudiente::find($request->input_delete);

        if($countAcudientes->acudientes_count == 0) {
            $query = $acudiente->delete();
            if($query) {
                return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        }
        else {
            return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. El acudiente <b>' . $acudiente->nom_acu . ' ' . $acudiente->ape_acu . '</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
        }
    }

    public function citas(Request $request, $id) {
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

        $citas = DB::table('citas')->join('pacientes', 'citas.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                                   ->join('fonoaudiologos', 'citas.fonoaudiologos_ide_fon', '=', 'fonoaudiologos.ide_fon')
                                   ->join('usuarios', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
                                   ->select('citas.*', 'pacientes.nom_pac', 'pacientes.ape_pac', 'usuarios.nom_usu', 'usuarios.ape_usu')
                                   ->where('citas.est_cit', 'P')->get();

        $todas_citas = DB::table('citas')->join('pacientes', 'citas.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                                   ->join('fonoaudiologos', 'citas.fonoaudiologos_ide_fon', '=', 'fonoaudiologos.ide_fon')
                                   ->join('usuarios', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
                                   ->select(DB::raw('citas.*, CONCAT(pacientes.nom_pac, \' \', pacientes.ape_pac) AS nom_pac, CONCAT(usuarios.nom_usu, \' \', usuarios.ape_usu) AS nom_usu, citas.*'))
                                   ->orderBy('citas.fec_cit', 'asc')
                                   ->get();

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

        $request->session()->put('search', '1');

        return view('aten.citas', ['opcion' => $opcion,
                                   'pacientes' => $pacientes,
                                   'fonoaudiologos' => $fonoaudiologos,
                                   'citas' => $citas,
                                   'allTimes' => $allTimes,
                                   'todas_citas' => $todas_citas,
                                   'datenow' => $dt->toDateString(),
                                   'timenow' => $dt->toTimeString()]);
    }

    public function citas_paciente(Request $request, $id, $paciente) {
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

        $citas = DB::table('citas')->join('pacientes', 'citas.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                                   ->join('fonoaudiologos', 'citas.fonoaudiologos_ide_fon', '=', 'fonoaudiologos.ide_fon')
                                   ->join('usuarios', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
                                   ->select(DB::raw('citas.*, CONCAT(pacientes.nom_pac, \' \', pacientes.ape_pac) AS nom_pac, usuarios.nom_usu, usuarios.ape_usu'))
                                   ->where('citas.pacientes_ide_pac', $paciente)
                                   //->where('citas.est_cit', 'P') //Pendientes
                                   ->orderBy('citas.fec_cit', 'asc')
                                   ->get();

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

        return view('aten.citas_paciente', ['opcion' => $opcion,
                                            'pacientes' => $pacientes,
                                            'fonoaudiologos' => $fonoaudiologos,
                                            'citas' => $citas,
                                            'allTimes' => $allTimes,
                                            'datenow' => $dt->toDateString(),
                                            'timenow' => $dt->toTimeString()]);
    }

    public function createcit(CitasCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'fec_cit'  => 'required',
        //     'hor_cit'  => 'required',
        //     'fer_cit'  => 'required',
        //     'hrc_cit'  => 'required',
        //     'ide_pac'  => 'required',
        //     'ide_fon'  => 'required'
        // ];

        // $customMessages = [
        //     'fec_cit.required' => 'El campo Fecha cita es obligatorio.',
        //     'hor_cit.required' => 'El campo Hora cita es obligatorio.',
        //     'fer_cit.required' => 'El campo Fecha registro es obligatorio.',
        //     'hrc_cit.required' => 'El campo Hora registro es obligatorio.',
        //     'ide_pac.required' => 'El campo Paciente es obligatorio.',
        //     'ide_fon.required' => 'El campo Fonoaudiologo es obligatorio.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $cita = new Cita;

        $cita->fec_cit = $request->fec_cit;
        $cita->hor_cit = $request->hor_cit;
        $cita->fer_cit = $request->fer_cit;
        $cita->hrc_cit = $request->hrc_cit;

        $cita->pacientes_ide_pac = $request->ide_pac;
        $cita->fonoaudiologos_ide_fon = $request->ide_fon;

        $query = $cita->save();

        if($query) {
            return back()->with('success', 'La informaci&oacute;n se guard&oacute; satisfactoriamente.');
        }
        else {
            return back()->with('fail', 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.');
        }
    }

    public function updatecit(CitasUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'fec_cit_edit'  => 'required',
        //     'hor_cit_edit'  => 'required',
        //     'fer_cit_edit'  => 'required',
        //     'hrc_cit_edit'  => 'required',
        //     'est_cit_edit'  => 'required',
        //     'pacientes_ide_pac_edit'  => 'required',
        //     'fonoaudiologos_ide_fon_edit'  => 'required'
        // ];

        // $customMessages = [
        //     'fec_cit_edit.required' => 'El campo Fecha cita es obligatorio.',
        //     'hor_cit_edit.required' => 'El campo Hora cita es obligatorio.',
        //     'fer_cit_edit.required' => 'El campo Fecha registro es obligatorio.',
        //     'hrc_cit_edit.required' => 'El campo Hora registro es obligatorio.',
        //     'est_cit_edit.required' => 'El campo Estado es obligatorio.',
        //     'pacientes_ide_pac_edit.required' => 'El campo Paciente es obligatorio.',
        //     'fonoaudiologos_ide_fon_edit.required' => 'El campo Fonoaudiologo es obligatorio.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $cita = new Cita;

        $cita = Cita::find($request->ide_cit_edit);

        $cita->fec_cit = $request->fec_cit_edit;
        $cita->hor_cit = $request->hor_cit_edit;
        $cita->fer_cit = $request->fer_cit_edit;
        $cita->hrc_cit = $request->hrc_cit_edit;
        $cita->est_cit = $request->est_cit_edit;

        $cita->pacientes_ide_pac = $request->pacientes_ide_pac_edit;
        $cita->fonoaudiologos_ide_fon = $request->fonoaudiologos_ide_fon_edit;

        $query = $cita->save();
        $tab_current = 'li-section-bar-2';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se actualiz&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo actualizar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function acudientes_paciente(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $acudientes = DB::table('acudientes')->get();
        $tipos_acudientes = DB::table('tipos_acudientes')->get();
        $pacientes = DB::table('pacientes')->get();

        $acudientes_pacientes = DB::table('pacientes_has_acudientes')->join('pacientes', 'pacientes_has_acudientes.pacientes_ide_pac', '=', 'pacientes.ide_pac')
                                   ->join('acudientes', 'pacientes_has_acudientes.acudientes_ide_acu', '=', 'acudientes.ide_acu')
                                   ->join('tipos_acudientes', 'pacientes_has_acudientes.tipos_acudientes_ide_tac', '=', 'tipos_acudientes.ide_tac')
                                   ->select('pacientes_has_acudientes.*', 'pacientes.nom_pac', 'pacientes.ape_pac', 'acudientes.nom_acu', 'acudientes.ape_acu', 'tipos_acudientes.nom_tac')
                                   ->get();

        $tab_current = 'li-section-bar-1';

        return view('aten.registro_acudientes_paciente', ['opcion' => $opcion,
                                                          'acudientes' => $acudientes,
                                                          'tipos_acudientes' => $tipos_acudientes,
                                                          'pacientes' => $pacientes,
                                                          'acudientes_pacientes' => $acudientes_pacientes,
                                                          'tab_current' => $tab_current]);
    }

    public function createasoc(AcudientesPacientesCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'ide_pac'  => 'required',
        //     'ide_acu'  => 'required',
        //     'ide_tac'  => 'required',
        //     'pre_paa'  => 'required'
        // ];

        // $customMessages = [
        //     'ide_pac.required' => 'El campo Paciente es obligatorio.',
        //     'ide_acu.required' => 'El campo Acudiente es obligatorio.',
        //     'ide_tac.required' => 'El campo Tipo acudiente es obligatorio.',
        //     'pre_paa.required' => 'El campo Conocimiento es obligatorio.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $acudiente_paciente = new AcudientePaciente;

        $acudiente_paciente->pre_paa = $request->pre_paa;
        $acudiente_paciente->pacientes_ide_pac = $request->ide_pac;
        $acudiente_paciente->acudientes_ide_acu = $request->ide_acu;
        $acudiente_paciente->tipos_acudientes_ide_tac = $request->ide_tac;

        $query = $acudiente_paciente->save();
        $tab_current = 'li-section-bar-3';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function updateasoc(AcudientesPacientesUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        // $rules = [
        //     'pacientes_ide_pac_edit'  => 'required',
        //     'acudientes_ide_acu_edit'  => 'required',
        //     'tipos_acudientes_ide_tac_edit'  => 'required',
        //     'pre_paa_edit'  => 'required'
        // ];

        // $customMessages = [
        //     'pacientes_ide_pac_edit.required' => 'El campo Paciente es obligatorio.',
        //     'acudientes_ide_acu_edit.required' => 'El campo Acudiente es obligatorio.',
        //     'tipos_acudientes_ide_tac_edit.required' => 'El campo Tipo acudiente es obligatorio.',
        //     'pre_paa_edit.required' => 'El campo Conocimiento es obligatorio.',
        // ];

        // $this->validate($request, $rules, $customMessages);

        $acudiente_paciente = new AcudientePaciente;

        $acudiente_paciente = AcudientePaciente::find($request->ide_paa_edit);

        $acudiente_paciente->pre_paa = $request->pre_paa_edit;
        $acudiente_paciente->pacientes_ide_pac = $request->pacientes_ide_pac_edit;
        $acudiente_paciente->acudientes_ide_acu = $request->acudientes_ide_acu_edit;
        $acudiente_paciente->tipos_acudientes_ide_tac = $request->tipos_acudientes_ide_tac_edit;

        $query = $acudiente_paciente->save();
        $tab_current = 'li-section-bar-4';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function deleteasoc(Request $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $acudiente_paciente = new AcudientePaciente;
        $acudiente_paciente = AcudientePaciente::find($request->input_delete);
        $query = $acudiente_paciente->delete();

        $tab_current = 'li-section-bar-4';

        if($query) {
            return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    public function generatePDF(Request $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $pacientes = DB::table('pacientes')
                    ->get();

        $dt = Carbon::now();

        $data = [
            'title' => 'Pacientes',
            'fecha' => $dt->toDateString(),
            'hora' => $dt->isoFormat('h:mm:ss a'),
            'pacientes' => $pacientes
        ];

        $pdf = PDF::loadView('aten.pacientesPDF', $data);
        return $pdf->download('reporte_pacientes' . $dt->toDateString() . '_' . str_replace(':', '-', $dt->toTimeString()) . '.pdf');
    }

    public function generatePDFByIdPaciente(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $pacientes = DB::table('pacientes')
                    ->select('pacientes.*','juegos_pacientes.*','juegos.*','juegos_pacientes.pun_jue as p')
                    ->join('juegos_pacientes','pacientes_ide_pac','=','pacientes.ide_pac')
                    ->join('juegos','juegos.ide_jue','=','juegos_pacientes.juegos_ide_jue')
                    ->where('pacientes.ide_pac', $id)
                    ->get();

        $promedio = 0;
        for ($i = 0; $i < count($pacientes); $i++) {
            $promedio = $promedio + $pacientes[$i]->p;
        }

        $promedio = $promedio / 4;
        $dig = self::rubrica($promedio);

        $dt = Carbon::now();

        $data = [
            'title' => 'Pacientes',
            'fecha' => $dt->toDateString(),
            'hora' => $dt->isoFormat('h:mm:ss a'),
            'promedio'=>$promedio,
            'observacion'=>$dig,
            'pacientes' => $pacientes
        ];

        $pdf = PDF::loadView('aten.pacientesPDF', $data);
        return $pdf->download('reporte_pacientes' . $dt->toDateString() . '_' . str_replace(':', '-', $dt->toTimeString()) . '.pdf');
    }

    public static function rubrica($a){
        if($a==5)return "Nulo";
        if($a>=4 && $a<=4.9)return "Bajo";
        if($a>=2 && $a<=3.9)return "Medio";
        if($a>=0 && $a<=1.9)return "Alto";
    }

    public function getAvailableHoursInclusive(Request $request) {

        $data = $request->date;
        $hora = $request->hour;

        $citas = DB::table('citas')
                ->whereRaw('STR_TO_DATE(\'' . $data . '\', \'%Y-%m-%d\') = citas.fec_cit')
                ->get();

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

        foreach($citas as $cita) {
            if($cita->hor_cit == $hora)
                continue;
            $key = array_search($cita->hor_cit, $allTimes);
            if($key) {
                array_splice($allTimes, $key, 1);
            }
        }

        return response()->json([
            'data' => $allTimes
        ]);
    }

    public function getAvailableHours(Request $request) {

        $data = $request->date;

        $citas = DB::table('citas')
                ->whereRaw('STR_TO_DATE(\'' . $data . '\', \'%Y-%m-%d\') = citas.fec_cit')
                ->get();

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

        foreach($citas as $cita) {
            $key = array_search($cita->hor_cit, $allTimes);
            if($key) {
                array_splice($allTimes, $key, 1);
            }
        }

        return response()->json([
            'data' => $allTimes
        ]);
    }
}
