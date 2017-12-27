<?php

  class Home extends CI_Controller{

    public function index(){
      //$data['title'] =
      //$this->load->database();
      //$row = $this->db->query("SELECT * FROM Member;")->row();
      //if(isset($row)){
	//      $this->load->view('templates/header');
	  //    $this->load->view('pages/home');
	    //  $this->load->view('templates/footer');
	//}else{
		$this->load->view('templates/header');
	      $this->load->view('pages/home');
	      $this->load->view('templates/footer');
	//}
    }

  }


 ?>
