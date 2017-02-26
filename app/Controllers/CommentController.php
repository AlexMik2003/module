<?php


namespace App\Controllers;


use App\Helpers\Session;
use App\Models\Answer;
use App\Models\Comments;

/**
 * Class CommentController - news comments
 *
 * @package App\Controllers
 */
class CommentController extends BaseController
{
    /**
     *
     * Add plus mark to comment
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @param array $args - data with comment id
     *
     * @return mixed
     */
    public function Plus($request,$responce,$args)
    {

        $comments = Comments::where("id","=",$args["id"])->first();
        $num = ($comments->plus)+1;
        $comments->update([
           "plus" => $num,
        ]);

        return $responce->withRedirect($this->router->pathFor("news", array("id" => $args["news"])));

    }

    /**
     *
     * Add minus mark to comment
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @param array $args - data with comment id
     *
     * @return mixed
     */
    public function Minus($request,$responce,$args)
    {
        $comments = Comments::where("id","=",$args["id"])->first();
        $num = ($comments->minus)+1;
        $comments->update([
            "minus" => $num,
        ]);

        return $responce->withRedirect($this->router->pathFor("news", array("id" => $args["news"])));
    }

    /**
     *
     * Add answer to comment
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @param array $args - data with comment id
     *
     * @return mixed
     */
    public function Answer($request,$responce,$args)
    {
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        Answer::create([
            "comment_id" => $args["id"],
            "answer" => $request->getParam("ans"),
            "user_id" => Session::get("id"),
            "created" => $date,
            "updated" => $date,
        ]);

        return $responce->withRedirect($this->router->pathFor("news", array("id" => $args["news"])));
    }
}