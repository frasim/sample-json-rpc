<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportPharmacies extends Migration
{
    protected $_jsonFile = __DIR__ . "/../data/pharmacies.geojson";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = $this->getData();
        foreach ($data as $d) {
            $this->addPharmacy($d);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('pharmacies')->truncate();
    }

    /**
     * @return mixed
     */
    protected function getData()
    {
        throw_unless(file_exists($this->_jsonFile), new Exception("File {$this->_jsonFile} not found!"));
        $json = json_decode(file_get_contents($this->_jsonFile), true);
        return $json['features'];
    }

    /**
     * @param array $d
     */
    protected function addPharmacy($d)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $sql = <<<SQL
INSERT INTO pharmacies(geo, name, created_at, updated_at) VALUES (ST_MakePoint(?,?), ?, ?, ?)
SQL;
        $params = [
            floatval($d['geometry']['coordinates'][1]),
            floatval($d['geometry']['coordinates'][0]),
            strval(trim($d['properties']['Descrizione'])),
            $now,
            $now
        ];
        DB::statement($sql, $params);
    }
}
