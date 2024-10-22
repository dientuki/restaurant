<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasUlids;

    protected $fillable = [
        'location',
        'table_number',
        'max_capacity',
    ];
}
