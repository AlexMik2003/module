<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "news";

    /**
     * Fillable table data
     *
     * @var array
     */
    protected $fillable = [
        'cat_id',
        'title',
        'context',
        'img',
        'created',
        'updated',
        'analitics',

    ];

    /**
     * Timestamp
     *
     * @var bool
     */
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class,'tags_to_news','tag_id','news_id');
    }
}