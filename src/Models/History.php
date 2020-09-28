<?php


namespace Jakmall\Recruitment\Calculator\Models;


use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'histories';

    protected $fillable = ['command', 'description', 'result', 'output'];
}
