<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountingAccount;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 6; // Cambia esto según el ID de tu usuario

        // Cuentas padre
        $cuentasPadre = [
            ['code' => '1', 'name' => 'Activo', 'type' => 'activo'],
            ['code' => '2', 'name' => 'Pasivo', 'type' => 'pasivo'],
            ['code' => '3', 'name' => 'Patrimonio', 'type' => 'patrimonio'],
            ['code' => '4', 'name' => 'Ingresos', 'type' => 'ingreso'],
            ['code' => '5', 'name' => 'Gastos', 'type' => 'gasto'],
        ];

        $padres = [];

        foreach ($cuentasPadre as $cuenta) {
            $account = AccountingAccount::firstOrNew(['code' => $cuenta['code']]);
            $account->name = $cuenta['name'];
            $account->type = $cuenta['type'];
            $account->is_parent = true;
            $account->parent_account_id = null;
            $account->user_id = $userId;
            $account->save();

            $padres[$cuenta['code']] = $account;
        }

        // Subcuentas con jerarquía
      $subcuentas = [
            // Activo
            ['code' => '1.1', 'name' => 'Activo Corriente', 'type' => 'activo', 'parent' => '1', 'initial_balance' => 0.00],
            ['code' => '1.1.01', 'name' => 'Caja', 'type' => 'activo', 'parent' => '1.1', 'initial_balance' => 10000.00],
            ['code' => '1.1.02', 'name' => 'Banco', 'type' => 'activo', 'parent' => '1.1', 'initial_balance' => 10000.00],
            ['code' => '1.1.03', 'name' => 'Clientes', 'type' => 'activo', 'parent' => '1.1', 'initial_balance' => 0.00], // <--- NUEVA CUENTA

            // Pasivo
            ['code' => '2.1', 'name' => 'Pasivo Corriente', 'type' => 'pasivo', 'parent' => '2', 'initial_balance' => 0.00],
            ['code' => '2.1.01', 'name' => 'Proveedores', 'type' => 'pasivo', 'parent' => '2.1', 'initial_balance' => 0.00],

            // Patrimonio
            ['code' => '3.1', 'name' => 'Capital Social', 'type' => 'patrimonio', 'parent' => '3', 'initial_balance' => 0.00],

            // Ingresos
            ['code' => '4.1', 'name' => 'Ventas', 'type' => 'ingreso', 'parent' => '4', 'initial_balance' => 0.00],
            ['code' => '4.1.01', 'name' => 'Ventas de productos', 'type' => 'ingreso', 'parent' => '4.1', 'initial_balance' => 0.00],

            // Gastos
            ['code' => '5.1', 'name' => 'Gastos Operativos', 'type' => 'gasto', 'parent' => '5', 'initial_balance' => 0.00],
            ['code' => '5.1.01', 'name' => 'Sueldos y salarios', 'type' => 'gasto', 'parent' => '5.1', 'initial_balance' => 0.00],
            ['code' => '5.1.02', 'name' => 'Compra de productos', 'type' => 'gasto', 'parent' => '5.1', 'initial_balance' => 0.00],
        ];

        $cuentas = $padres;

        foreach ($subcuentas as $cuenta) {
            // Crear grupo padre si aún no existe
            if (!isset($cuentas[$cuenta['parent']])) {
                $parentType = $cuenta['type'];
                $parentCodeFirstDigit = substr($cuenta['parent'], 0, 1);
                $parentParentId = $padres[$parentCodeFirstDigit]->id ?? null;

                $cuentas[$cuenta['parent']] = AccountingAccount::firstOrNew(['code' => $cuenta['parent']]);
                $cuentas[$cuenta['parent']]->name = 'Grupo ' . $cuenta['parent'];
                $cuentas[$cuenta['parent']]->type = $parentType;
                $cuentas[$cuenta['parent']]->is_parent = true;
                $cuentas[$cuenta['parent']]->parent_account_id = $parentParentId;
                $cuentas[$cuenta['parent']]->user_id = $userId;
                $cuentas[$cuenta['parent']]->save();
            }

            $account = AccountingAccount::firstOrNew(['code' => $cuenta['code']]);
            $account->name = $cuenta['name'];
            $account->type = $cuenta['type'];
            $account->is_parent = false;
            $account->parent_account_id = $cuentas[$cuenta['parent']]->id;
            $account->user_id = $userId;
            if (isset($cuenta['initial_balance'])) {
                $account->initial_balance = $cuenta['initial_balance'];
            }
            $account->save();
        }
    }
}
