<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ConceptosPredioCreateFormRequest;
use App\Http\Requests\ConceptosPredioUpdateFormRequest;
use App\Models\ConceptoPredio;
use App\Models\Opcion;

class ConceptosPredioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }
        $opcion = Opcion::where('id','=', base64_decode($id))->first();
        $conceptos_predio = DB::table('conceptos_predio')->get(); //paginate(5);
        //$acudientes->appends($request->all());
        $tab_current = 'li-section-bar-1';
        if ($request->has('page')) {
            $tab_current = 'li-section-bar-2';
        }

        //$request->session()->put('search', '1');

        return view('conceptospredio.create', ['opcion' => $opcion,
                                             'conceptos_predio' => $conceptos_predio,
                                             'tab_current' => $tab_current]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ConceptosPredioCreateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConceptosPredioCreateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $rules = [
            'codigo' => 'unique_with:conceptos_predio,anio',
            'prioridad' => 'unique_with:conceptos_predio,anio',
        ];

        $validator = Validator::make($request->all(), $rules, $messages = [
            'codigo.unique_with' => 'La combinación de año y código ya existe.',
            'prioridad.unique_with' => 'La combinación de año y prioridad ya existe.',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)
                         ->withInput();
        }

        $concepto_predio = new ConceptoPredio();
        $concepto_predio->anio = $request->anio;
        $concepto_predio->meses_amnistia = $request->meses_amnistia;
        $concepto_predio->codigo = $request->codigo;
        $concepto_predio->nombre = $request->nombre;
        $concepto_predio->formula = $request->formula;
        $concepto_predio->aplica_interes = $request->filled('aplica_interes') ? $request->aplica_interes : 0;
        $concepto_predio->prioridad = $request->prioridad;
        $concepto_predio->minimo_urbano = $request->minimo_urbano;
        $concepto_predio->minimo_rural = $request->minimo_rural;
        $concepto_predio->capital = $request->filled('capital') ? $request->capital : 0;
        $concepto_predio->interes = $request->filled('interes') ? $request->interes : 0;
        $query = $concepto_predio->save();
        $tab_current = 'li-section-bar-1';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se guard&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo guardar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ConceptosPredioUpdateFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ConceptosPredioUpdateFormRequest $request) {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $concepto_predio = new ConceptoPredio();
        $concepto_predio = ConceptoPredio::find($request->id_edit);
        $concepto_predio->anio = $request->anio_edit;
        $concepto_predio->meses_amnistia = $request->meses_amnistia_edit;
        $concepto_predio->codigo = $request->codigo_edit;
        $concepto_predio->nombre = $request->nombre_edit;
        $concepto_predio->formula = $request->formula_edit;
        $concepto_predio->aplica_interes = $request->filled('aplica_interes_edit') ? $request->aplica_interes_edit : 0;
        $concepto_predio->prioridad = $request->prioridad_edit;
        $concepto_predio->minimo_urbano = $request->minimo_urbano_edit;
        $concepto_predio->minimo_rural = $request->minimo_rural_edit;
        $concepto_predio->capital = $request->filled('capital_edit') ? $request->capital_edit : 0;
        $concepto_predio->interes = $request->filled('interes_edit') ? $request->interes_edit : 0;
        $query = $concepto_predio->save();
        $tab_current = 'li-section-bar-2';

        if($query) {
            return back()->with(['success' => 'La informaci&oacute;n se actualiz&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
        }
        else {
            return back()->with(['fail' => 'No se pudo actualizar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!$request->session()->exists('userid')) {
            return redirect('/');
        }

        $countConceptosPredio = DB::table('conceptos_predio')
                            ->select(DB::raw('count(*) as cm_count')) // verificar si ya esta relacionada con otras tablas
                            ->where('id', $request->input_delete)->first();

        $tab_current = 'li-section-bar-2';
        $concepto_predio = new ConceptoPredio();
        $concepto_predio = ConceptoPredio::find($request->input_delete);

        //if($countConceptosPredio->cm_count == 0) {
            $query = $concepto_predio->delete();
            if($query) {
                return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
            }
            else {
                return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
            }
        // }
        // else {
        //     return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. La clase de mutaci&oacute;n <b>' . $concepto_predio->nombre . ' (' . $concepto_predio->codigo . ')</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     if (!$request->session()->exists('userid')) {
    //         return redirect('/');
    //     }

    //     $countConceptosPredio = DB::table('usuarios')
    //                         ->leftJoin('fonoaudiologos', 'fonoaudiologos.usuarios_ide_usu', '=', 'usuarios.ide_usu')
    //                         ->select(DB::raw('count(*) as usuarios_count'))
    //                         ->where('usuarios.ide_usu', $request->input_delete)
    //                         ->where(function($query) {
    //                             $query->orWhereNotNull('fonoaudiologos.usuarios_ide_usu');
    //                         })->first();

    //     $tab_current = 'li-section-bar-2';
    //     $usuario = new Usuario;
    //     $usuario = Usuario::find($request->input_delete);

    //     if($countConceptosPredio->usuarios_count == 0) {
    //         $query = $usuario->delete();
    //         if($query) {
    //             return back()->with(['success' => 'El registro se elimin&oacute; satisfactoriamente.', 'tab_current' => $tab_current]);
    //         }
    //         else {
    //             return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. Intente nuevamente.', 'tab_current' => $tab_current]);
    //         }
    //     }
    //     else {
    //         return back()->with(['fail' => 'No se pudo eliminar la informaci&oacute;n. El usuario <b>' . $usuario->nom_usu . ' ' . $usuario->ape_usu . '</b> ya posee informaci&oacute;n asociada.', 'tab_current' => $tab_current]);
    //     }
    // }
}
