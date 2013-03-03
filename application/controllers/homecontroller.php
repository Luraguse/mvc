<?php
class HomeController extends Controller {
    
    function beforeAction(){}
    function index() {
        // var_dump($this->_sanitized);
        $news = $this->Buscar();
        $news->all("news");
        $news->returnFields("content, created_at");
        $news->orderBy("created_at");
        $newsResults = $news->getResults();
        $this->set("news", $newsResults);
    }
    function afterAction(){}
}