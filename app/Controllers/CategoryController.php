<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\News;

/**
 * Class CategoryController  - news category
 *
 * @package App\Controllers
 */
class CategoryController extends  BaseController
{
    /**
     *
     * Get category
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @param array $args - data with id of category
     *
     * @return mixed
     */
    public function getCategory($request,$responce,$args)
    {

        $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
        $limit     = 5; // Number of posts on one page
        $skip      = ($page - 1) * $limit;
        $query = News::where("cat_id","=",$args["id"]);
        $cat = Category::where("id","=",$args["id"])->first();

        $count = $query->count();
        $news = $query->orderBy("created","DESC")->skip($skip)->take($limit)->get();

        return $this->view->render($responce,"category.twig",[
            "cat" => $cat->category_name,
            "news" => $news,
            'pagination'    => [
            'needed'        => $count > $limit,
            'count'         => $count,
            'page'          => $page,
            'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
            'limit'         => $limit,
                ],
        ]);

    }

}