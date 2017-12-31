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
      $category = $this->input->get('category');
      $isMember = isset($_SESSION['user']); //? "true" : "false";
      // $config['base_url'] = 'http://example.com/index.php/news/';
      $config['use_page_numbers'] = TRUE;
      // $data['news'] = $this->News_Model->getAll();
      $config['base_url'] = base_url("news");
      $config['total_rows'] = $this->News_Model->getTotalRows($category, $isMember);
      $config['per_page'] = 5;

      $config['page_query_string'] = TRUE;
      $config['reuse_query_string'] = TRUE; //allows other query string aside per_page.

      //so url goes like iexamplesite.com/news/3 ... will use 3 as pagination since it's the 2nd part of uri segment
      //$config['uri_segment'] = 2;

      //$startIndex = ($this->uri->segment(2)) ? $this->uri->segment(2) * $config['per_page'] - 1 : 0;
      $startIndex = $this->input->get('per_page') ? $this->input->get('per_page') * $config['per_page'] - $config['per_page'] : 0;
      // use page number instead of starting index.
      //$config['use_page_numbers'] = TRUE;


      $data['news'] = $this->News_Model->getNews($config['per_page'], $startIndex, $category, $isMember);

      // $config['full_tag_open'] = "<div class = 'pagination'>";
      // $config['full_tag_close'] = "</div>";
      $config['cur_tag_open'] = '<span class = "pagination-digit pagination-active">';
      $config['cur_tag_close'] = '</span>';
      $config['num_tag_open'] = '<span class = "pagination-digit">';
      $config['num_tag_close'] = '</span>';





      $this->pagination->initialize($config);
      // $data['full_tag_open'] = "<div class = 'pagination'>";
      // $data['full_tag_close'] = "</div>";

      $data['links'] = $this->pagination->create_links();


      $this->load->view("templates/header");
      $this->load->view("pages/news_listing", $data);
      // echo $this->pagination->create_links();
      $this->load->view("templates/footer");

    }

    public function view($id = NULL){
      if(!isset($id)){
        redirect("news", "refresh");
      }else{
        $result = $this->News_Model->getByNewsId($id);
        $this->load->view("templates/header");
        if($result->membersOnly && !isset($_SESSION['user'])){
          //$data['news'] = $result;
          $data['message'] = "This news is for members only, please log-in to continue.";
          $data['news_id'] = $id;
          $this->load->view("pages/login", $data);
        }else{
          //$data['message'] = "Please login to view the news.";
          $data['news'] = $result;
          // $data['message'] = "This news is for members only, please log-in to continue.";
          // $data['news_id'] = $id;
          //$data['url'] = base_url('news/view/' . $id);
          $this->load->view("pages/news_display", $data);
        }
        // echo $this->pagination->create_links();
        $this->load->view("templates/footer");
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
            "membersOnly" => isset($_POST['membersOnly']) ? $_POST['membersOnly'] : 'false',
            "category" => isset($_POST['category']) ? $_POST['category'] : 'others'
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
          "membersOnly" => isset($_POST['membersOnly']) ? $_POST['membersOnly'] : 'false',
          "category" => $_POST['category']
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
