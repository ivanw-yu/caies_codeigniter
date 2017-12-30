<?php

  class Auth_Model extends CI_Model{

    public function __construct(){
      parent::__construct();
      $this->load->database();
    }

    public function register($member){

      //echo $member['firstName'] . $member['lastName'] . $member['email'] . $member['username'] . $member['password'] . $member['isAdmin'];
      $jsonResponse = [];
      $query = $this->db->query("SELECT * FROM `Member` WHERE `email` = " . $this->db->escape(strtolower($member["email"]))
                                ." OR `username` = " . $this->db->escape(strtolower($member["username"])) . ";");

      $row = $query->row();
      if(isset($row)){

        if(strtolower($row->username) == strtolower($member['username'])){
          $jsonResponse["usernameExists"] = "Sorry, the username you have chosen already exists, please use a different username.";
        }
        if(strtolower($row->email) == strtolower($member['email'])){
          $jsonResponse["emailExists"] = "Sorry, that email is already taken. Please use a different email.";
        }
        // echo $member['firstName'] . $member['lastName'] . $member['email'] . $member['username'] . $member['password'] . $member['isAdmin'];
        $jsonResponse["success"] = false;
        return $jsonResponse;
      }


      $fn = $this->db->escape(ucfirst(strtolower($member["firstName"])));
      $ln = $this->db->escape(ucfirst(strtolower($member["lastName"])));
      $un = $this->db->escape(strtolower($member["username"]));
      $email = $this->db->escape(strtolower($member["email"]));

      $options = [
        'cost' => 12
      ];

      $password = password_hash($member["password"], PASSWORD_BCRYPT, $options);
      $success = $this->db->simple_query("INSERT INTO Member(firstName, lastName, username, email, password, isAdmin) VALUES(".$fn.",".$ln . ",". $un . ",". urldecode($email). ",'" . $password . "', false)");

      if($success){
        $jsonResponse["success"] = true;
        return $jsonResponse;
      }else{
        //echo $fn . " " . $ln . " " . $un . " " . $email . " " . $password . " " . $member['isAdmin'];
        $jsonResponse["success"] = false;
        return $jsonResponse;
      }

    }

    public function login($member){
      $jsonResponse = [];
      $usernameOrEmail = $this->db->escape(strtolower($member['emailOrUsername']));
      $query = $this->db->query("SELECT * FROM `Member` WHERE `username` = " . $usernameOrEmail . " OR `email` = " . $usernameOrEmail);
      $result = $query->row();
      if(isset($result)){
      	$dbpassword = $result->password;
      	$options = [
        	'cost' => 12
      	];
      	if(password_verify($member["password"], $dbpassword)){
        	$_SESSION['user'] = $result->id;
        	$_SESSION['username'] = $result->username;
        	$_SESSION['firstName'] = $result->firstName;
        	$_SESSION['lastName'] = $result->lastName;
        	$_SESSION['email'] = $result->email;

        	$jsonResponse["success"] = true;
        	//$jsonResponse["url"] = "http://localhost:8888/caies/";
      	}else{
        	$jsonResponse["success"] = false;
      	}
      	return $jsonResponse;
     }else{
     	$jsonResponse["success"] = false;
      return $jsonResponse;
     }

    //header("Content-Type: application/json");
        // echo json_encode($jsonResponse);

    }

  }

 ?>
