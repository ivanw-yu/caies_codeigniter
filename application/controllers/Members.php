<?php

  class Members extends CI_Controller{

    public function __construct(){
      parent::__construct();
      $this->load->model("News_Model");
    }

    public function index(){

    }

    public function view($id){

    }

    public function edit($id){

    }

    public function delete($id){

    }

    public function dashboard(){

      $data['news'] = $this->News_Model->getByAuthorId($_SESSION['user']);
      $this->load->view("templates/header");
      $this->load->view("pages/dashboard", $data);
      $this->load->view("templates/footer");
    }

  }


 ?>
