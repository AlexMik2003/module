<?php

namespace App\Controllers;


use App\Models\Tags;
use App\Models\News;

/**
 * Class SearchController - search news
 *
 * @package App\Controllers
 */
class SearchController extends BaseController
{
    /**
     *Get search page
     *
     * Request $request
     *
     * Responce $responce
     *
     * @return mixed
     */
    public function getSearch($request,$responce)    {

        return $this->view->render($responce,"search.twig");
    }

    /**
     *Get all search variants
     *
     * Request $request
     *
     * Responce $responce
     *
     * @return mixed
     */
    public function searchList($request,$responce)
    {
        $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
        $limit     = 5; // Number of posts on one page
        $skip      = ($page - 1) * $limit;

        $tag = Tags::where("tag_name","like","%".$request->getParam("search")."%")->first();
        $news_id = $this->container["db"]->table("tag_to_news")->select("news_id")->where("tag_id","=",$tag->id)->get();

        $arr = [];
        foreach ($news_id as $id)
        {

            $query = News::where("id","=",$id->news_id);
            $count = $query->count();
            $news = $query->orderBy("created","DESC")->skip($skip)->take($limit)->get();

            foreach ($news as $value)
            {
                $arr[] = [
                    "id" =>$id->news_id,
                    "title" => $value->title,

                ];
            }
        }

        return $this->view->render($responce,"search.twig",[
            "tag" => $tag->tag_name,
            "tags"=> $arr,
            'pagination'    => [
                'needed'        => $count > $limit,
                'count'         => $count,
                'page'          => $page,
                'lastpage'      => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
                'limit'         => $limit,
            ],
        ]);
    }

    /**
     *Autocompkete tag
     *
     * Request $request
     *
     * Responce $responce
     *
     * @return JSON
     */
    public function Find($request,$responce)
    {
        $search = $request->getParam("term");
        $tags = Tags::where("tag_name",'like', '%'. $search.'%');
        $data = $tags->orderBy("tag_name")->get();

        return json_encode($data);
    }
}