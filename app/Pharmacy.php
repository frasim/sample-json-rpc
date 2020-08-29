<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Pharmacy extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'latitude', 'longitude',
    ];


    /**
     * Retrieve a list of the nearest pharmacies including their name, position and distance, sorted by distance
     * @param double $x latitude
     * @param double $y longitude
     * @param integer $r radius
     * @param integer $l limit
     * @return array
     */
    public static function near($x, $y, $r, $l)
    {
        $sql = <<<SQL
SELECT
    name AS name,
    ST_X(ST_AsText(geo, 2)) AS latitude,
    ST_Y(ST_AsText(geo, 2)) AS longitude,
    st_distance(geo, ST_MakePoint({$x},{$y})::geography) AS distance
FROM pharmacies
WHERE ST_DWithin(geo, ST_MakePoint({$x},{$y})::geography, {$r})
ORDER BY geo <-> ST_MakePoint({$x},{$y})::geography
SQL;
        if($l > -1)
            $sql .= " LIMIT {$l}";
        return DB::select($sql);
    }

}
