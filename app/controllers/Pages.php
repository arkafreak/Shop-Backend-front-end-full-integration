<?php
class Pages extends Controller{
    //private $productModel;
    public function __construct()
    {
    }
    
    public function index(){
        $data=[
            'title' => 'Welcome'
        ];
        $this->view('pages/index', $data);
    }


    public function about(){
        $this->view('pages/about');
    }

    public function options(){
        $this->view('choose/options');
    }
}