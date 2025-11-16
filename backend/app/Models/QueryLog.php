<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueryLog extends Model
{
    use HasFactory;

    protected $fillable = ['sql', 'bindings', 'duration_ms', 'full_query'];
}
