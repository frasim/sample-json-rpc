<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePharmaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = [];
        $sql[0] = <<<SQL
create table pharmacies
(
    id bigserial not null
        constraint pharmacies_pkey primary key,
    geo geography not null,
    name varchar(255) not null,
    created_at  timestamp,
    updated_at  timestamp,
    deleted_at  timestamp
);
SQL;
        $sql[1] = "CREATE INDEX ON pharmacies USING gist(geo);";
        DB::statement($sql[0]);
        DB::statement($sql[1]);

        /*
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            // $table->addColumn('geography', 'geo');
            $table->string('name', 255);
            // $table->double('latitude');
            // $table->double('longitude');
            $table->timestamps();
            $table->softDeletes();
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacies');
    }
}
