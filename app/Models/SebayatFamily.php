<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SebayatFamily extends Model
{
    use HasFactory;

    protected $table = 'sebayat_family';

    protected $fillable = [
        'user_id',
        'fathername',
        'fatherphoto',
        'mothername',
        'motherphoto',
        'marital',
        'spouse',
        'spousephoto',
    ];
}