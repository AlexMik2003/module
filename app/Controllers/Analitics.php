<?php


namespace App\Controllers;

use App\Models\News;

/**
 * Class Analitics - check status of news
 *
 * @package App\Controllers
 */
class Analitics extends BaseController
{
    /**
     *
     * Get all news with analitics
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return mixed
     */
    public function Analitics($request,$responce)
    {
        $news = News::where("analitics","=",1)->get();

        $arr = [];
       foreach ($news as $value)
       {
          $arr[] = [
            "title" => $value["title"],
              "id" => $value["id"],
          ];
       }

        return $this->view->render($responce,"analitics.twig",["an" => $arr]);
    }
}