<?php

  class Reply_Model extends CI_Model{

    public function __construct(){
      parent::__construct();
      $this->load->database();
    }

    public function insert($reply){

      if(!isset($reply['authorId']) || !isset($reply['content']) || !isset($reply['questionId'])){
        return false;
      }else{

        $content = $this->db->escape($reply['content']);
        $authorId = $reply['authorId'];
        $questionId = $reply['questionId'];
        //$dateCreated = date("Y-m-d G:i:s");

        $success = $this->db->simple_query("INSERT INTO `Reply` (authorId, questionId, content, dateCreated)
                                            VALUES(" . $authorId . ", " . $questionId . ", " . $content . ", NOW());");
        return $success;
      }

    }

    public function updateByReplyId($reply){
      if(!isset($reply['id'])){
        return false;
      }else{
        if(!isset($reply['content'])){
          return false;
        }else{
          $id = $reply['id'];
          $content = $this->db->escape($reply['content']);
          $success = $this->db->simple_query("UPDATE `Reply`
                                              SET `content` = " . $content .
                                              " WHERE `id` = " . $id . ";");
          return $success;
        }
      }
    }

    public function getByQuestionId($id){
      if(!isset($id)){
        return [];
      }else{
        return $this->db->query("SELECT * FROM Reply R, Member M WHERE R.authorId = M.id AND `questionId` = " . $id . " GROUP BY R.id ORDER BY dateCreated;")->result();
      }
    }
  }
 ?>
