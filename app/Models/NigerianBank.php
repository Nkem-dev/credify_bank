<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NigerianBank extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    // laravel assumes id as primary key but I am overriding laravel's default id to code and preventing it from auto incrementing
    public $incrementing = false; //do not auto increment

    // tells laravel that the primary key is a string
    protected $keyType = 'string'; //primary key is a string

    protected $primaryKey = 'code'; //changes primary key column to code

    // Relationship; one bank has many transfers
    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'recipient_bank_code', 'code'); //foreign key in transfers table and primary key in nigerian banks table
    }

    // Get as array for dropdown
    public static function list(): array
    {
        // get name of bank(value) and code(key) and convert to plain PHP array
        return static::pluck('name', 'code')->toArray();
    }
    // pluck is a laravel query builder method that extracts specific columns from a database query result and return them as an array.

}
