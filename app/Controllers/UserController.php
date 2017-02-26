<?php


namespace App\Controllers;


use App\Models\Comments;

/**
 * Class UserController - class about users
 *
 * @package App\Controllers
 */
class UserController extends BaseController
{
    /**
     *
     *Get user comments
     *
     * Request $request
     *
     * Responce $responce
     *
     * @return mixed
     *
     * @param $args - data with news id
     *
     * @return mixed
     */
    public function getUserComments($request, $responce,$args)
    {
        $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
        $limit     = 5; // Number of posts on one page
        $skip      = ($page - 1) * $limit;

        $query = Comments::where("user_id","=",$args["id"]);
        $count = $query->count();
        $comments = $query->orderBy("created","DESC")->skip($skip)->take($limit)->get();
        $arr = [];
        foreach ($comments as $value)
        {
            $arr[]  = [
                "text" => $value["text"],
                "date" => $value["created"],
            ];
        }

        return $this->view->render($responce,"users.twig",["comm" => $arr,
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