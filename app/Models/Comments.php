<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "comments";

    /**
     * Fillable table data
     *
     * @var array
     */
    protected $fillable = [
        'news_id',
        'user_id',
        'text',
        'plus',
        'minus',
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