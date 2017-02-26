<?php


namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Answer;
use App\Models\Comments;
use App\Models\News;
use App\Models\Tags;
use App\Models\User;

/**
 * Class NewsController - main class for news
 *
 * @package App\Controllers
 */
class NewsController extends BaseController
{

    /**
     *
     * Get news for reading
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @param array $args - data with news id
     *
     * @return mixed
     */

    public function getNews($request,$responce,$args)
    {
        $news = News::where("news.id","=",$args["id"])->first();
        $query = News::where("news.id","=",$args["id"])->join("tag_to_news","tag_to_news.news_id","=","news.id")
            ->join("tags","tags.id","=","tag_to_news.tag_id")->get();

        $tags= [];
       foreach ($query as $item)
       {
           $tags[] =[
               "id" => $item->tag_id,
               "name" => $item->tag_name,
           ];
       }

       $arr_news = [
                "id" => $news->id,
               "title" => $news->title,
               "img" => $news->img,
               "analitics" => $news->analitics,
               "context" => $news->context,
                "tags" => $tags,
           ];

        $query2 = User::join("comments","users.id","=","comments.user_id")->having("comments.news_id","=",$news->id)
            ->orderBy("comments.plus","DESC")->get();

        $comments = [];

        foreach ($query2 as $item) {

            $query3 = Comments::join("answer","answer.comment_id","=","comments.id")->
            join("users","users.id","=",'answer.user_id')->having("answer.comment_id","=",$item->id)->orderBy("answer.created","ASC")->get();
            $answer=[];
            foreach ($query3 as $q)
            {
                $answer[] = [
                  "answer_user" => $q->login,
                    "answer_text" => $q->answer,
                    "answer_date" => $q->created,
                ];
            }
            $comments[] = [
                "id" => $item->id,
                "news" => $item->news_id,
                "user" => $item->login,
                "text" => $item->text,
                "plus" => $item->plus,
                "minus" => $item->minus,
                "date" => $item->created,
                "answer" => $answer,
            ];
        }

        return $this->view->render($responce,"news.twig",["news"=>$arr_news,"comments" => $comments]);
    }

    /**
     *
     * Add comment to news
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @param array $args - data with news id
     *
     * @return mixed
     */
    public function AddComment($request,$responce,$args)
    {
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        Comments::create([
           "news_id" => $args["id"],
            "user_id" => Session::get("id"),
            "text" => $request->getParam("comment"),
            'created' => $date,
        ]);

        return $responce->withRedirect($this->router->pathFor("news", array("id" => $args["id"])));
    }
}