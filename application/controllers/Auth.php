<?php

  class Auth extends CI_Controller {

    public function __construct(){
      parent::__construct();
      $this->load->model("Auth_Model");
    }

    public function register(){

      // POST /register
      if($this->input->server('REQUEST_METHOD') == 'POST'){

        $member = array(
            "firstName" => $_POST["firstName"],
            "lastName" => $_POST["lastName"],
            "username" => $_POST["username"],
            "email" => $_POST["email"],
            "password" => $_POST["password"],
            "isAdmin" => false
        );

        $jsonResponse = [];

        if(!preg_match('/^[a-zA-Z]+$/', $member["firstName"]) ){
          $jsonResponse["firstNameMessage"] = "First name cannot contain digits or special characters";
        }

        if(!preg_match('/^[a-zA-Z]+$/', $member["lastName"]) ){
          $jsonResponse["lastNameMessage"] = "Last name cannot contain digits or special characters";
        }

        if(!preg_match('/^[(0-9)?a-zA-Z]{3,10}$/', $member["username"]) ){
          $jsonResponse["usernameMessage"] = "Username invalid. (Username must be 3-11 characters, excluding special characters)";
        }

        if(!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $member["email"])){
          $jsonResponse["emailMessage"] = "Email invalid";
        }

        if(!preg_match('/[#?!@$%^&*\-]/', $member["password"]) || !preg_match('/[a-z]/', $member["password"]) || !preg_match('/[0-9]/', $member["password"]) || strlen($member["password"]) < 5){
          $jsonResponse["passwordMessage"] = "Password must be atleast 5 characters long, containing atleast 1 lowercase alphabet, uppercase alphabet, digit and special character.";
        }

        //CONTINUE HERE!!!!!!!!!!!!!!!! EMAIL MATCH
        // if(!pregmatch(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, $member["username"]) ){
        //   $jsonResponse["usernameMessage"] = "Username invalid. (Username must be 3-11 characters, excluding special characters)";
        // }

        if(!empty($jsonResponse)){
          $jsonResponse["success"] = false;
          echo json_encode($jsonResponse);
        }




        $result = $this->Auth_Model->register($member);
        if($result['success'] == true){

          $_SESSION['username'] = $member["username"];
          $_SESSION['firstName'] = $member["firstName"];
          $_SESSION['lastName'] = $member["lastName"];
          $_SESSION['email'] = $member["email"];
          $_SESSION['isAdmin'] = $member["isAdmin"];

          // redirect("member/dashboard", "refresh");
          $result["url"] = base_url("dashboard");
          echo json_encode($result);
        }else{
          echo json_encode($result);
        }
      }else{

        // GET /register
        $this->load->view("templates/header");
        $this->load->view("pages/register");
        $this->load->view("templates/footer");
      }
    }

    public function login(){
      if($this->input->server('REQUEST_METHOD') == 'POST'){
        $member = array(
            "emailOrUsername" => $_POST["emailOrUsername"],
            "password" => $_POST["password"],
        );
        $jsonResponse = $this->Auth_Model->login($member);
        if($jsonResponse["success"]){
        	$jsonResponse["url"] = base_url("dashboard");
        }
        echo json_encode($jsonResponse);
        // if($jsonResponse["success"]){
        //   //redirect("/dashboard", "refresh");
        // }else{
        //   echo $jsonResponse;
        // }
      }else{
        $this->load->view("templates/header");
        $this->load->view("pages/login");
        $this->load->view("templates/footer");
      }
    }

    public function logout(){
      session_destroy();
      redirect("login", "refresh");
    }

    public function test(){
      $this->load->database();
      $result = $this->db->query("SELECT * from Member;");
      // $result = $this->db->simple_query("INSERT INTO Member(firstName, lastName, username, email, password, isAdmin) VALUES('qqq', 'qqq', 'qqq2', 'qqq2@mail.com', 'qqq', false)");
      // echo ($result ? 'true' : 'false');
      // while($row = $result->fetch_assoc()){
      //   echo $row['username'];
      // }
      //echo '' . $result;

      foreach($result->result() as $row){
        echo $this->db->escape(ucfirst(strtolower($row->username)));
      }
      //echo $result;
    }
  }

 ?>
