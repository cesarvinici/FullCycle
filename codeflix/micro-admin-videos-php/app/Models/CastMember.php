<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = ["id", "name", "type"];

    protected $casts = [
        "id" => "string",
        "name" => "string",
        "type" => "int",
        "deleted_at" => "datetime",
        "updated_at" => "datetime"
    ];

}
