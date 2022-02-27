<?php

namespace App\Models;
use App\Http\Controllers\TodolistController;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DateTime;
use DateTimeZone;

class Todolist extends Model
{
    use HasFactory;
    protected $fillable = ['task', 'deadline', 'timezone'];
}
