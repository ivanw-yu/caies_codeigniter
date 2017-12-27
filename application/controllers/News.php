<?php

  class News extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model("News_Model");
        $this->load->database();
        $this->load->helper("url");
    }

    public function index(){
      $this->load->library('pagination');

      // $config['base_url'] = 'http://example.com/index.php/news/';
      $config['use_page_numbers'] = TRUE;
      // $data['news'] = $this->News_Model->getAll();
      $config['base_url'] = base_url("news");
      $config['total_rows'] = $this->News_Model->getTotalRows();
      $config['per_page'] = 5;

      //so url goes like iexamplesite.com/news/3 ... will use 3 as pagination since it's the 2nd part of uri segment
      $config['uri_segment'] = 2;

      $startIndex = ($this->uri->segment(2)) ? $this->uri->segment(2) * $config['per_page'] - 1 : 0;
      // use page number instead of starting index.
      $config['use_page_numbers'] = TRUE;

      $data['news'] = $this->News_Model->getNews($config['per_page'], $startIndex);

      $data['links'] = $this->pagination->initialize($config);
      echo $this->pagination->create_links();

      $this->load->view("templates/header");
      $this->load->view("pages/news_listing", $data);
      $this->load->view("templates/footer");

    }

    public function view($id = NULL){
      if(!isset($id)){

      }else{

      }
    }

    public function create(){
      if($this->input->server('REQUEST_METHOD') == 'POST' && isset($_SESSION["username"])){
        $query = $this->db->query("SELECT id FROM `Member` WHERE `username` = " . $this->db->escape($_SESSION["username"]) . ";");
        $row = $query->row();
        if(isset($row)){
          $id = $row->id;

          $jsonResponse = [];
          $news = array(
            "authorId" => $id,
            "title" => $_POST['title'],
            "content" => $_POST['content'],
            "type" => isset($_POST['type']) ? $_POST['type'] : 'Event',
            "membersOnly" => isset($_POST['membersOnly']) ? $_POST['membersOnly'] : 'false'
          );
          $success = $this->News_Model->insert($news);
          $jsonResponse["success"] = $success;
          $jsonResponse["url"] = base_url("dashboard");
          echo json_encode($jsonResponse);
        }else{
          $jsonResponse["success"] = false;
          echo json_encode($jsonResponse);
        }
      }else{
        // GET /news/create
        $this->load->view("templates/header");
        $this->load->view("pages/news_create");
        $this->load->view("templates/footer");
      }
    }

    public function edit($id){
      if($this->input->server('REQUEST_METHOD') == 'POST' && isset($_SESSION["username"])){
        $news = array(
          "id" => $id,
          "title" => $_POST['title'],
          "content" => $_POST['content'],
          "type" => isset($_POST['type']) ? $_POST['type'] : 'Event',
          "membersOnly" => isset($_POST['membersOnly']) ? $_POST['membersOnly'] : 'false'
        );

        $jsonResponse = [];
        $jsonResponse['success'] = $this->News_Model->updateNewsById($news);
        if($jsonResponse['success']){
          $jsonResponse['url'] = base_url("dashboard");
          echo json_encode($jsonResponse);
        }else{
          $jsonResponse['error'] = "Error occurred, please try again later";
          echo json_encode($jsonResponse);
        }
      }else{
        $data['news'] = $this->News_Model->getByNewsId($id);
        $this->load->view("templates/header");
        $this->load->view("pages/news_create", $data);
        $this->load->view("templates/footer");
      }
    }

    public function delete($id){

    }

  }

 ?>
