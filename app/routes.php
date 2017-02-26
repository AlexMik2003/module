<?php

$app->get("/", "page:index")->setName("main");

$app->get("/category/{id}/", "category:getCategory")->setName("category");
$app->get("/news/{id}", "news:getNews")->setName("news");
$app->post("/news/{id}", "news:AddComment");

$app->get("/search", "search:getSearch")->setName("search");
$app->post("/search", "search:searchList");
$app->get("/search/query", "search:Find");

$app->get("/tags/{id}", "tags:getTags")->setName("tags");

$app->get("/signin","authorized:getSignin")->setName("signin");
$app->post("/signin", "authorized:Authorization");
$app->get("/signout", "authorized:getSignOut")->setName("signout");

$app->get("/analitics", "analitics:Analitics")->setName("analitics");

$app->get("/comment/plus/{id}/{news}", "comment:Plus")->setName("comment.plus");
$app->get("/comment/minus/{id}/{news}", "comment:Minus")->setName("comment.minus");
$app->post("/comment/answer/{id}/{news}", "comment:Answer")->setName("comment.answer");

$app->get("/user/{id}", "user:getUserComments")->setName("user");

$app->get("/admin", "admin:getAdmin")->setName("admin");
$app->get("/admin/json", "admin:newsAll");
$app->post("/admin/action", "admin:Action")->setName("admin.action");
$app->get("/admin/catnew", "admin:adminCategory")->setName("admin.catnew");
$app->post("/admin/catnew", "admin:CreateCategory");
$app->get("/admin/addnews", "admin:adminNews")->setName("admin.addnews");
$app->post("/admin/addnews", "admin:CreateNews");
$app->get("/admin/editnews", "admin:editNews")->setName("admin.editnews");
$app->post("/admin/editnews", "admin:getEditNews");