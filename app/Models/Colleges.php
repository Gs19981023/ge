<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Colleges extends Model
{
    
    protected $table = 'colleges';
    protected $fillable = ['collegeid', 'name','desc', 'tel','tags','address'];






}
