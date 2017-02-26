<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "answer";

    /**
     * Fillable table data
     *
     * @var array
     */
    protected $fillable = [
        'comment_id',
        'answer',
        'user_id',
        'created',
        'updated',
    ];

    /**
     * Timestamp
     *
     * @var bool
     */
    public $timestamps = false;
}