<?php


namespace App\Controllers;


use App\Models\Category;
use App\Models\Comments;
use App\Models\News;
use App\Models\User;

/**
 * Class PageController - main site page
 *
 * @package App\Controllers
 */
class PageController extends  BaseController
{
    /**
     * Get all nessesery data for main page
     *
     * Request $request
     *
     * Responce $responce
     *
     * @return mixed
     */
    public function index($request,$responce)
    {
        $top_blog_query = Comments::raw("COUNT(comments.id","users.login","users.id")->join("users","comments.user_id","=","users.id")
            ->groupBy("comments.user_id")->skip(0)->take(5)->get();

        $top_blogger = [];
        foreach ($top_blog_query as $query)
        {
            $top_blogger[] = [
                "id" => $query->id,
              "name" => $query->login,
            ];
        }

        $top_news_query = Comments::raw("COUNT(comments.id","news.title","news.id")->join("news","comments.news_id","=","news.id")
            ->groupBy("comments.news_id")->skip(0)->take(3)->get();

       $top_news = [];
        foreach ($top_news_query as $query)
        {
            $top_news[] =[
              "id" => $query->id,
                "title"=> $query->title,
            ];
        }

        $cat_query = Category::orderBy("category_name")->get();

        $cat_news = [];
       foreach ($cat_query as $query)
       {
           $news_query = News::where("cat_id","=",$query->id)->orderBy("created","DESC")->take(5)->get();
           $news = [];
           foreach ($news_query as $value)
           {
               $news[] =[
                 "news_id" => $value->id,
                   "news_title"=> $value->title,
                   "news_date" => $value->created,
               ];
           }
           $cat_news[] =[
             "cat_id" => $query->id,
               "cat_name" => $query->category_name,
               "news" => $news,
           ];
       }
        return $this->view->render($responce,"main.twig",["top_blogger" => $top_blogger,"top_news" => $top_news, "cat_news" => $cat_news]);
    }

    /**
     * Show slider
     *
     * @return array - four latest news
     */
    public function slider()
    {
        $query = News::orderBy("created","DESC")->take(4)->get();
        $slider=[];
        foreach ($query as $key => $value)
        {
            $slider[] = [
                "id" => $value["id"],
                "title" => $value["title"],
                "date" => $value["created"],
                "text" => $value["context"],
                "img" => $value["img"],
            ];
        }
      return $slider;

    }
}