<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "category";

    /**
     * Fillable table data
     *
     * @var array
     */
    protected $fillable = [
        'category_name',
    ];

    /**
     * Timestamp
     *
     * @var bool
     */
    public $timestamps = false;

    public function news()
    {
        return $this->hasOne(News::class);
    }

}