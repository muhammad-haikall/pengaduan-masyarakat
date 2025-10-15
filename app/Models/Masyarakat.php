<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masyarakat extends Model
{
    protected $table = 'masyarakat';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nik', 'nama', 'username', 'password', 'telp'];
    public $timestamps = false; // Pastikan ini ada
}