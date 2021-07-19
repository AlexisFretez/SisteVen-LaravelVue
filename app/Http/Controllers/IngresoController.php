<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DetalleIngreso;
use App\Ingreso;
class IngresoController extends Controller
{
    public function index(Request $request)
    {
        //Concion para la url
        if (!$request->ajax()) return redirect('/');
        //Funcion buscador
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            //Join une la tabla proveedor con la Tabla persona
            $ingresos = Ingreso::join('personas', 'ingresos.idproveedor', '=', 'personas.id')
            ->join('users', 'ingresos.idusuario', '=', 'users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado','personas.nombre', 'users.usuario')
            ->orderBy('ingresos.id', 'desc')->paginate(3);
        }
        else{
            $ingresos = Ingreso::join('personas', 'ingresos.idproveedor', '=', 'personas.id')
            ->join('users', 'ingresos.idusuario', '=', 'users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante','ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado','personas.nombre', 'users.usuario')
            ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('ingresos.id', 'desc')->paginate(3);
        }
        
        
        return [
            'pagination' => [
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page'     => $ingresos->perPage(),
                'last_page'    => $ingresos->lastPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }  

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            $mytime= Carbon::now('America/Asuncion');

            $ingreso = new Ingreso();
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = \Auth::user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $mytime->toDateString();
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = 'Registrado';
            $ingreso->save();


            $detalles = $request->data;//Array Detalles

            //Recoorro todos los Elementos
            foreach ($detalles  as $ep => $det) {
                //Objeto detalle
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();

            }

            DB::commit();
            

        } catch (Exception $e) {
            DB::commit();
        }


        
    }

    
    

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
    }
}