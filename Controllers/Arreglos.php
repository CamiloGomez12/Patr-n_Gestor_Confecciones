<?php
class Arreglos extends Controller{
    public function __construct(){
        session_start();

        parent::__construct();
    }

    public function index(){
        $this->views->getView($this, "index");
    } 
}


?>