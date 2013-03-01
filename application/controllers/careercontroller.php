<?php
class CareerController extends Controller {
    
    function beforeAction(){
    }
    function index() {
       
    }
    
    function show($string = "host") {
        /*$career = $this->buscar(
                "SELECT career.name, career.description, career.content, career.id_gallery, image.link 
                    FROM `career` 
                    LEFT JOIN image 
                    ON career.id_image = image.id 
                    WHERE career.name = '".$string."'"
                );
         * 
         */
        $career = $this->Buscar();
        $career->where($string, "name");
        $career->returnFields("name, description, content, id_gallery");
        $career->hasOne("image","link");
        $careerResults = $career->getResults();
        $this->set("career", $careerResults);

        $id_gallery = ($careerResults['id_gallery'] > 0)?$careerResults['id_gallery']:1;
        $gallery = $this->Buscar();
        $gallery->setTable("image");
        $gallery->where($id_gallery, "id_gallery");
        $gallery->returnFields("link, description");
        $galleryResults = $gallery->getResults();
        $this->set("gallery",$galleryResults);
        /*
        
        $gallery = $this->buscar(
                "SELECT image.link, image.description 
                    FROM `image` 
                    WHERE image.id_gallery = '".$id_gallery."'"
                );
        $this->set("career", $career);
        $this->set("gallery", $gallery);
         * 
         */
    }
    function afterAction(){
    }
}