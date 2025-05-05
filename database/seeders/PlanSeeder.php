<?php
 
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\DetailPlan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plan = Plan::updateOrCreate(
            ['name' => 'Pro'],
            [                   
                'price' => 0.02,
                'duration_days' => 30
            ]
        );

        $detailsPlan = [
            'Gestión de compras',
            'Gestión de ventas',
            'Gestión de usuarios',
            'Gestión de pedidos',
            'Gestión de reportes',
        ];

        foreach ($detailsPlan as $detail) {
            DetailPlan::firstOrCreate([
                'description' => $detail,
                'plan_id' => $plan->id, //ID plan Pro     
            ]);
        }
    }
}
