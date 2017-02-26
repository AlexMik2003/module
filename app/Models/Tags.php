<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "tags";

    /**
     * Fillable table data
     *
     * @var array
     */
    protected $fillable = [
        'tag_name',
    ];

    /**
     * Timestamp
     *
     * @var bool
     */
    public $timestamps = false;

    public function news()
    {
        return $this->belongsToMany(News::class,'tags_to_news','tag_id','news_id');
    }
}