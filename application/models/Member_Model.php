<?php

  class Member_Model extends CI_Model{

    public function __construct(){
      parent::__construct();
      $this->load->database();
    }

    public function getById($id){
      return $this->db->query("SELECT * FROM `Member` WHERE `id` = " . $id . ";")->row();
    }

    public function getByUsername($username){
      return $this->db->query("SELECT * FROM `Member` WHERE `username` = " . $this->db->escape($username) . ";")->row();
    }

  }

 ?>
