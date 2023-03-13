<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVisitorsView extends Migration
{
    public function up()
    {
        DB::statement('
        CREATE OR REPLACE VIEW `visitors_view` AS
        SELECT v.id, v.url, v.method, v.created_at
        FROM shetabit_visits as v
        ');
    }

    public function down()
    {
        DB::statement('DROP VIEW visitors_view');
    }
}
