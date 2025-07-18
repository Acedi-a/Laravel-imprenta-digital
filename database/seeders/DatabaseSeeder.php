<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsuariosSeeder::class,
            ProductosSeeder::class,
            DireccionesSeeder::class,
            CotizacionesSeeder::class,
            PedidosSeeder::class,
            OpcionesProductoSeeder::class,
            CotizacionOpcionesSeeder::class,
            PagosSeeder::class,
            EnviosSeeder::class,
            HistorialEstadosSeeder::class,
            NotificacionesSeeder::class,
            ArchivosSeeder::class,
        ]);
    }
}
