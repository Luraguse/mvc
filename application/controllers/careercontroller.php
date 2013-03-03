<?php
class CareerController extends Controller {
    
    function beforeAction(){
    }
    function index() {
       
    }
    
    function show($string) {
        $career = $this->Buscar();
        $career->where($string, "name");
        $career->returnFields("name, description, content, id_gallery");
        $career->hasOne("image","link");
        $careerResults = $career->getResults();
        if(count($careerResults) <= 0) {
            header("location: ".BASE_PATH."/career/show/host");
        }
        $this->set("career", $careerResults);

        $id_gallery = ($careerResults['id_gallery'] > 0)?$careerResults['id_gallery']:"1";
        $gallery = $this->Buscar();
        $gallery->setTable("image");
        $gallery->where($id_gallery, "id_gallery");
        $gallery->returnFields("link, description");
        $galleryResults = $gallery->getResults();
        $this->set("gallery",$galleryResults);
    }
    function afterAction(){
    }
}