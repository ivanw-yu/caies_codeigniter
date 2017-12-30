<?php

  class Question_Model extends CI_Model{

    // $removeKeywords = ["what", "why", "how", "where",
    //                         "when", "a", "the", "that",
    //                         "there", "their", "they're",
    //                         "he", "she", "it", "it's", "its",
    //                         "but", "and", "or", "for", "so",
    //                         "the", "that", "have", "has", "had",
    //                         "we", "we've"];

    public function __construct(){
      parent::__construct();
      $this->load->database();
    }

    public function getByKeywords($keywords){

      $removeKeywords = ["what", "why", "how", "where",
                              "when", "a", "the", "that",
                              "there", "their", "they're",
                              "he", "she", "it", "it's", "its",
                              "but", "and", "or", "for", "so",
                              "the", "that", "have", "has", "had",
                              "we", "we've"];


      $keywordsArray = preg_split("/[\s,\.!\?]+/", $keywords);
      $size = count($keywordsArray);
      $result = [];
      $ids = [];
      $query = "SELECT * FROM `Question` WHERE ";
      $likes = [];
      if($size > 0){
        for( $i=0; $i<$size; $i++){
            if(in_array($keywordsArray[i], $removeKeywords)){
              continue;
            }else{
              array_push($likes, "`title` LIKE '%". $keywordsArray[i] . "%' OR `title` LIKE '%" . $keywordsArray[i] . "%'");
            }
        }
        // add the likes sql queries. Add GROUP BY to remove duplicates
        if(count($likes) > 0){
          $query = $query . join(" OR ", $likes) . " GROUP BY `id`;";
          $result = $this->db->query($query);
          return $result->result();
        }else{
          //return NULL if there's no results
          return NULL;
        }
      }
    }

    public function getAll(){
      return $this->db->query("SELECT * FROM `Question` ORDER BY `dateCreated`;")->result();
    }

    public function getByQuestionId($id){
      $query = $this->db->query("SELECT * FROM `Question` WHERE `id` = " . $id . ";");
      return $query->row();
    }

    // $question must have authorId, title and content.
    public function insertQuestion($question){
      $title;
      $content;
      $authorId;
      if(isset($question['title'])){
        $title = $this->db->escape($question['title']);
      }
      if(isset($question['content'])){
        $content = $this->db->escape($question['content']);
      }
      if(isset($question['authorId'])){
        $authorId = $question['authorId'];
      }
      if(!isset($title) || !isset($content) || !isset($authorId)){
        return false;
      }else{
        //$dateCreated = time();//date("Y-m-d");
        $success = $this->db->simple_query("INSERT INTO `Question`(authorId, title, content, dateCreated)
                                            VALUES(" . $authorId . ", "
                                                     . $title . ", "
                                                     . $content . ", "
                                                     . "NOW())");
        return $success;
      }
    }
  }

 ?>
