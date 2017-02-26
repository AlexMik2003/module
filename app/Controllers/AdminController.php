<?php

namespace App\Controllers;


use App\Models\Category;
use App\Models\News;
use App\Models\TagNews;
use App\Models\Tags;
use \Respect\Validation\Validator as valid;
use Slim\Router;

/**
 * Class AdminController  - administrate the site
 *
 * @package App\Controllers
 */
class AdminController extends BaseController
{
    /**
     *
     * Admin main page
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return mixed
     */
    public function getAdmin($request,$responce)
    {
        return $this->view->render($responce,"admin.twig");
    }

    /**
     *
     * Get all news
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return JSON
     */
    public function newsAll($request,$responce)
    {
        $orderby = $request->getParam('order')[0]["column"];
        $sort['col'] = $request->getParam('columns')[$orderby]['data'];
        $sort['dir'] = $request->getParam('order')[0]["dir"];

        $search = $request->getParam('search')['value'];

        $query = News::join("category","news.cat_id","=","category.id")->select("news.title","news.created","category.category_name","news.id")
            ->where(function ($q) use ($search){
                $q->where('news.title', 'like', '%'. $search.'%')->
                orWhere('news.created', 'like', '%'. date($search) .'%')->
                orWhere('category.category_name', 'like', '%'. $search .'%');
            });


        $output['recordsTotal'] = $query->count();


        $output['data'] = $query->orderBy($sort["col"],$sort["dir"])->skip($request->getParam('start'))->take($request->getParam('length',10))->get();

        $output['recordsFiltered'] = $output['recordsTotal'];

        $output['draw'] = intval($request->getParam('draw'));


        $json = json_encode($output);

        echo $json;

    }

    /**
     *
     * Check user action and call a method
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return mixed
     */
    public function Action($request,$responce)
    {
        switch ($request->getParam("action"))
        {
            case "add_cat":
                return $responce->withRedirect($this->router->pathFor("admin.catnew"));
                break;
            case "add_news":
                return $responce->withRedirect($this->router->pathFor("admin.addnews"));
                break;
           case "del_news":
                $this->newsDelete($request->getParam("id"));
                return $responce->withRedirect($this->router->pathFor("admin"));
                break;
            case "edit_news":
                if(empty($request->getParam("id"))) {
                    return $responce->withRedirect($this->router->pathFor("admin"));
                }
                else
                {
                    $this->editNews($request, $responce);
                }
                break;
        }
    }

    /**
     *
     * Page for adding new category
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return mixed
     */
    public function adminCategory($request,$responce)
    {
        return $this->view->render($responce,"catnew.twig");
    }

    /**
     *
     * Create new category and add it to DB
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return mixed
     */
    public function CreateCategory($request,$responce)
    {
        $validation = $this->validator->validate($request,[
            'category' => valid::notEmpty(),
        ]);

        if($validation->failed())
        {
            return $responce->withRedirect($this->router->pathFor("admin.catnew"));
        }

        Category::create(["category_name" => $request->getParam("category")]);
        return $responce->withRedirect($this->router->pathFor("admin"));

    }

    /**
     *
     * Page for adding new news
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return mixed
     */
    public function adminNews($request,$responce)
    {
        $query = Category::all();
        $cat= [];
        foreach ($query as $q)
        {
            $cat[] = [
              "id" => $q->id,
                "name"=>$q->category_name,
            ];
        }
        return $this->view->render($responce,"addnews.twig",["all_cat"=>$cat]);
    }

    /**
     *
     * Create new news and add it to DB
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return mixed
     */
    public function CreateNews($request,$responce)
    {

        $validation = $this->validator->validate($request,[
            'title' => valid::notEmpty(),
            'text' => valid::notEmpty(),
        ]);

        if($validation->failed())
        {
            return $responce->withRedirect($this->router->pathFor("admin.addnews"));
        }

         $analitics = !empty($request->getParam("analitics"))
             ? $request->getParam("analitics")
                 : 0;


       $files = $request->getUploadedFiles();
        if(!empty($files["img"]))
        {
            $img = $files["img"];
        }

        else $img = null;

        if ($img->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $img->getClientFilename();
            $img->moveTo(ROOT_PATH."/public/image/".$uploadFileName);
        }

        $news = News::create([
            "cat_id" => $request->getParam("cat"),
           "title"=> $request->getParam("title"),
            "context"=>$request->getParam("text"),
            "img" => "/image/".$uploadFileName,
            "analitics" => $analitics,

        ]);

       /* $tags = $request->getParam("tags");
        $tags = preg_split("|[^\\d\\w]+|is", $tags);*/


        return $responce->withRedirect($this->router->pathFor("admin"));

    }

    /**
     * Delete news
     *
     * @param array $id - News id's
     */
    public function newsDelete($id)
    {
        foreach ($id as $key => $value)
        {
            News::find($value)->delete();

        }
    }

    /**
     *
     * Page for edit news
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return mixed
     */
    public function editNews($request, $responce)
    {
        $query_cat = Category::all();
        $cat= [];
        foreach ($query_cat as $q)
        {
            $cat[] = [
                "id" => $q->id,
                "name"=>$q->category_name,
            ];
        }
        $query = News::join("category","category.id","=","news.cat_id")->where("news.id","=",$request->getParam("id"))->get();
        $news = [];
        foreach ($query as $q)
        {
            $news =[
                "news_id" => $request->getParam("id"),
              "title" => $q->title,
                "text" => $q->context,
                "analitics"=>$q->analitics,
                "cat_id" => $q->cat_id
            ];
        }


        return $this->view->render($responce,"editnews.twig",["edit_news" => $news,"edit_cat" => $cat]);
    }

    /**
     * Edit news
     *
     * Request $request
     *
     * Reaponce $responce
     *
     * @return mixed
     */
    public function getEditNews($request,$responce)
    {

        $validation = $this->validator->validate($request,[
            'title' => valid::notEmpty(),
            'text' => valid::notEmpty(),
        ]);

        if($validation->failed())
        {
            return $responce->withRedirect($this->router->pathFor("admin.editnews"));
        }

        $analitics = !empty($request->getParam("analitics"))
            ? $request->getParam("analitics")
            : 0;


        $files = $request->getUploadedFiles();
        if(!empty($files["img"]))
        {
            $img = $files["img"];

        }
        else{
            $img = false;
        }

        if ($img->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $img->getClientFilename();
            //$img->moveTo(ROOT_PATH."/public/image/".$uploadFileName);
        }

        if(empty($uploadFileName))
        {
            $image = News::where("id","=",$request->getParam("news_id"))->first()->img;
        }
        else $image = "/image/".$uploadFileName;


        News::where("id","=",$request->getParam("news_id"))->update([
            "cat_id" => $request->getParam("cat"),
            "title"=> $request->getParam("title"),
            "context"=>$request->getParam("text"),
            "img" => $image,
            "analitics" => $analitics,

        ]);

        return $responce->withRedirect($this->router->pathFor("admin"));
    }

}