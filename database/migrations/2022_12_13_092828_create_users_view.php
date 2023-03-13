<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUsersView extends Migration
{
    public function up()
    {
        DB::statement('
        CREATE OR REPLACE VIEW `users_view` AS
        SELECT u.id, u.name, u.email, mr.role_id, r.name as type
        FROM users as u
        LEFT JOIN model_has_roles as mr ON mr.model_id = u.id
        LEFT JOIN roles as r ON r.id = mr.role_id
        WHERE u.deleted_at IS NULL
        ');
    }

    public function down()
    {
        DB::statement('DROP VIEW users_view');
    }
}
