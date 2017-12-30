<?php

  class Replies extends CI_Controller{

    // public function index(){
    //
    // }
    //
    // public function view($id){
    //
    // }

    public function __construct(){
      parent::__construct();
      $this->load->model("Reply_Model");
      $this->load->library('session');
    }

    public function edit($id){

    }

    public function delete($id){

    }

    public function create(){
      if(!isset($_SESSION['user'])){
        return json_encode(["error" => "401 Unauthorized. No member id specified."]);
      }

      if(!isset($_POST['content']) || !isset($_POST['questionId'])){
        return json_encode(["error" => "Error: incomplete form."]);
      }
      $reply = array(
        "authorId" => $_SESSION['user'],
        "questionId" => $_POST['questionId'],
        "content" => $_POST['content']
      );

      $success = $this->Reply_Model->insert($reply);

      if($success){

        $_SESSION["successMessage"] = "Reply successfully posted!";
        $this->session->mark_as_flash('successMessage');
        redirect("questions/view/" . $_POST['questionId'], "refresh");
        //$jsonResponse["url"] = base_url("questions/view/" . $_POST['questionId']);
        // return json_encode($jsonResponse);
      }else{
        $_SESSION["errorMessage"] = "Error: Reply cannot be posted at this moment. Please try again later.";
        $this->session->mark_as_flash('errorMessage');
        //return json_encode($jsonResponse);
        redirect("questions/view/" . $_POST['questionId'], "refresh");
      }

    }

  }


 ?>
