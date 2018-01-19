<?php

namespace App\Models\SkyScannerModel;

use Illuminate\Database\Eloquent\Model;

class AccessCode extends Model
{
    protected $table='access_code';
    protected $primaryKey='id';
    public $timestamps=false;
}
