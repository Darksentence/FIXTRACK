<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Esta restricción compuesta se aplica en MySQL.
        // SQLite en memoria se utiliza para las pruebas de Laravel.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('
            ALTER TABLE equipments
            ADD UNIQUE INDEX equipments_id_client_id_unique
            (id, client_id)
        ');

        DB::statement('
            ALTER TABLE service_orders
            ADD CONSTRAINT service_orders_equipment_client_fk
            FOREIGN KEY (equipment_id, client_id)
            REFERENCES equipments (id, client_id)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT
        ');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('
            ALTER TABLE service_orders
            DROP FOREIGN KEY service_orders_equipment_client_fk
        ');

        DB::statement('
            ALTER TABLE equipments
            DROP INDEX equipments_id_client_id_unique
        ');
    }
};