<?php

namespace App\Controllers;


use App\Models\News;
use App\Models\Tags;

/**
 * Class TagController - tags for news
 *
 * @package App\Controllers
 */
class TagController extends BaseController
{
    /**
     *
     *Get tags for news
     *
     * Request $request
     *
     * Responce $responce
     *
     * @return mixed

     * @param $args - data with news id
     *
     * @return mixed
     */
    public function getTags($request,$responce,$args)
    {
        $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
        $limit     = 5; // Number of posts on one page
        $skip      = ($page - 1) * $limit;

        $news_id = $this->container["db"]->table("tag_to_news")->select("news_id")->where("tag_id","=",$args["id"])->get();
        $tag_id = Tags::find($args["id"]);


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


        return $this->view->render($responce,"tag.twig",[
            "tag" => $tag_id->tag_name,
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

}