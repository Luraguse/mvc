<?php
class Home extends Model {
    protected $attr_accessible = array("id", "name");
    protected $has_many = array("news", "videos");
    protected $has_one = array("main_pic");
    
    function __construct() {
        parent::__construct();
    }
}