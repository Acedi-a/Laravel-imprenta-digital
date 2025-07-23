<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Cotizacion;
use App\Models\Pedido;
use App\Models\Pago;
use App\Models\Envio;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas rápidas
        $stats = [
            'productos'     => Producto::count(),
            'usuarios'      => Usuario::count(),
            'cotizaciones'  => Cotizacion::count(),
            'pedidos'       => Pedido::count(),
        ];

        // Estado de pedidos para gráfico
        $pedidosEstados = Pedido::selectRaw('estado, count(*) as total')
                                ->groupBy('estado')
                                ->pluck('total', 'estado')
                                ->toArray();

        // Ingresos por mes (últimos 6)
        $ingresos = Pago::where('estado', 'aprobado')
                            ->where('fecha_pago', '>=', Carbon::now()->subMonths(6))
                            ->selectRaw('TO_CHAR(fecha_pago, \'YYYY-MM\') as mes, sum(monto) as total')
                            ->groupBy('mes')
                            ->orderBy('mes')
                            ->pluck('total', 'mes')
                            ->toArray();


        // Tablas recientes
        $ultimasCotizaciones = Cotizacion::with('usuario')
                                         ->orderBy('id','desc')
                                         ->limit(5)->get();

        $ultimosPagos = Pago::with('pedido')
                            ->orderBy('fecha_pago','desc')
                            ->limit(5)->get();

        return view('Admin.Dashboard.index', compact(
            'stats','pedidosEstados','ingresos','ultimasCotizaciones','ultimosPagos'
        ));
    }

    public function pdf(Request $request)
    {
        $tabla = $request->get('tabla','all');

        $data = match ($tabla) {
            'productos'     => ['titulo'=>'Productos','items'=>Producto::all()],
            'cotizaciones'  => ['titulo'=>'Cotizaciones','items'=>Cotizacion::with('usuario','producto')->get()],
            'pedidos'       => ['titulo'=>'Pedidos','items'=>Pedido::with('cotizacion')->get()],
            'pagos'         => ['titulo'=>'Pagos','items'=>Pago::with('pedido')->get()],
            'envios'        => ['titulo'=>'Envíos','items'=>Envio::with('pedido','direccion')->get()],
            default         => [
                'titulo'   => 'Dashboard Completo',
                'stats'    => [
                    'productos'    => Producto::count(),
                    'usuarios'     => Usuario::count(),
                    'cotizaciones' => Cotizacion::count(),
                    'pedidos'      => Pedido::count(),
                    'pagos'        => Pago::count(),
                    'envios'       => Envio::count(),
                ],
                'ingresosTotales' => Pago::where('estado','aprobado')->sum('monto'),
            ],
        };

        $pdf = Pdf::loadView('Admin.Dashboard.pdf', $data);
        return $pdf->download('dashboard_'.$tabla.'_'.now()->format('Y-m-d').'.pdf');
    }
}