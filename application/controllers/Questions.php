<?php

  class Questions extends CI_Controller{

    public function __construct(){
      parent::__construct();
      $this->load->model("Question_Model");
      $this->load->model("Member_Model");
      $this->load->model("Reply_Model");
    }
    public function index(){
      // $this->load->library('pagination');
      //
      // // $config['base_url'] = 'http://example.com/index.php/news/';
      // $config['use_page_numbers'] = TRUE;
      // // $data['news'] = $this->News_Model->getAll();
      // $config['base_url'] = base_url("questions");
      // $config['total_rows'] = $this->News_Model->getTotalRows();
      // $config['per_page'] = 5;
      //
      // //so url goes like iexamplesite.com/news/3 ... will use 3 as pagination since it's the 2nd part of uri segment
      // $config['uri_segment'] = 2;
      //
      // $startIndex = ($this->uri->segment(2)) ? $this->uri->segment(2) * $config['per_page'] - 1 : 0;
      // // use page number instead of starting index.
      // $config['use_page_numbers'] = TRUE;
      // $config['enable_query_strings'] = TRUE;
      // $config['reuse_query_string'] = TRUE;
      //
      // $data['news'] = $this->News_Model->getNews($config['per_page'], $startIndex);
      //
      // $config['cur_tag_open'] = '<span class = "pagination-digit pagination-active">';
      // $config['cur_tag_close'] = '</span>';
      // $config['num_tag_open'] = '<span class = "pagination-digit">';
      // $config['num_tag_close'] = '</span>';
      //
      //
      //
      // $this->pagination->initialize($config);
      //
      //
      // $data['links'] = $this->pagination->create_links();

      $data['questions'] = $this->Question_Model->getAll();

      $this->load->view("templates/header");
      $this->load->view("pages/questions_listing", $data);
      // echo $this->pagination->create_links();
      $this->load->view("templates/footer");
    }

    public function view($id){
      if(!isset($id)){
        redirect("questions", "refresh");
      }else{
        $result = $this->Question_Model->getByQuestionId($id);

        if(isset($result)){
          $member = $this->Member_Model->getById($result->authorId);
          // $data['member'];
          if(isset($member)){
            $data['member'] = $member;
          }
          $data['question'] = $result;

          $data['replies'] = $this->Reply_Model->getByQuestionId($id);

          $this->load->view("templates/header");
          // if($result->membersOnly && !isset($_SESSION['user'])){
          //   //$data['news'] = $result;
          //   $data['message'] = "This news is for members only, please log-in to continue.";
          //   $data['news_id'] = $id;
          //   $this->load->view("pages/login", $data);
          // }else{
            //$data['message'] = "Please login to view the news.";
            // $data['message'] = "This news is for members only, please log-in to continue.";
            // $data['news_id'] = $id;
            //$data['url'] = base_url('news/view/' . $id);
          $this->load->view("pages/questions_display", $data);
          //}
          // echo $this->pagination->create_links();
          $this->load->view("templates/footer");
        }else{
          // do something if $id isn't returning any question
        }
      }
    }

    public function edit($id){

    }

    public function create(){
      if($this->input->server('REQUEST_METHOD') == 'POST'){
        if(isset($_SESSION["username"])){
          $question = array(
            "authorId" => $_SESSION['user'],
            "title" => $_POST["title"],
            "content" => $_POST["content"]
          );
          $success = $this->Question_Model->insertQuestion($question);
          $jsonResponse = [];
          if($success){
            $jsonResponse["success"] = true;
            $jsonResponse["message"] = "Question posted successfully!";
            $jsonResponse["url"] = base_url("questions");
            echo json_encode($jsonResponse);
          }else{
            $jsonResponse["success"] = false;
            $jsonResponse["message"] = "There was an error posting your question. Please try again later or inform CAIES admin.";
            echo json_encode($jsonResponse);
          }
        }else{
          echo "401 Unauthorized";
        }
      }else{
        if(isset($_SESSION["username"])){
          $this->load->view("templates/header");
          $this->load->view("pages/questions_create");
          $this->load->view("templates/footer");
        }else{
          echo "401 Unauthorized";
        }
      }
    }

    public function delete($id){

    }

  }


 ?>
