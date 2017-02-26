<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagNews extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "tag_to_news";

    /**
     * Fillable table data
     *
     * @var array
     */
    protected $fillable = [
        'tag_id',
        'news_id',
    ];

    /**
     * Timestamp
     *
     * @var bool
     */
    public $timestamps = false;
}