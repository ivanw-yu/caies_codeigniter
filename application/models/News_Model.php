<?php
class News_Model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->load->database();
  }

// id	authorId	title	content	dateCreated	dateEdited	type

  public function insert($news){
    $title = $this->db->escape($news['title']);
    $content = $this->db->escape($news['content']);
    //$dateCreated = time();//date("Y-m-d");
    // $type = $this->db->escape($news['type']);
    $category = $this->db->escape($news['category']);
    $success = $this->db->simple_query("INSERT INTO News(authorId, title, content, dateCreated, membersOnly, category) VALUES (" . $news['authorId'] . ", " . $title . ", " . $content . ", NOW() , " . $news['membersOnly'] . ", " . $category . ")");
    return $success;
  }

  public function getAll(){
    $query = $this->db->query("SELECT * FROM `News` ORDER BY dateCreated ASC, dateEdited ASC;");
    return $query->result();
  }

  public function getByAuthorId($authorId){
    $query = $this->db->query("SELECT * FROM `News` WHERE `authorId` = " . $authorId . ";");
    return $query->result();
  }

  public function getByNewsId($id){
    $query = $this->db->query("SELECT * FROM `News` WHERE `id` = " . $id . ";");
    return $query->row();
  }

  public function getNews($limit = 5, $startIndex = 0, $category = 'all', $isMember = false){
    //$startIndex = $startIndex == 0 ? 0 : $startIndex-1;
    if(!isset($category) || $category == 'all'){
      if($isMember){
        return $this->db->query("SELECT * FROM `News` ORDER BY dateCreated ASC, dateEdited ASC LIMIT " . $limit . " OFFSET " . $startIndex . " ;")->result();
      }else{
        return $this->db->query("SELECT * FROM `News` WHERE `membersOnly` = false ORDER BY dateCreated ASC, dateEdited ASC LIMIT " . $limit . " OFFSET " . $startIndex . " ;")->result();
      }
    }else{
      if($isMember){
        return $this->db->query("SELECT * FROM `News` WHERE `category` = " . $this->db->escape($category) . " ORDER BY dateCreated ASC, dateEdited ASC LIMIT " . $limit . " OFFSET " . $startIndex . " ;")->result();
      }else{
        return $this->db->query("SELECT * FROM `News` WHERE `category` = " . $this->db->escape($category) . " AND `membersOnly` = false ORDER BY dateCreated ASC, dateEdited ASC LIMIT " . $limit . " OFFSET " . $startIndex . " ;")->result();
      }
    }
    //$result = $this->db->query("SELECT * FROM `News` ORDER BY dateCreated ASC, dateEdited ASC LIMIT " . $limit . " OFFSET " . $startIndex . " ;");
    //return $result->result();
  }

  public function getTotalRows($category = 'all', $isMember = false){
    if(!isset($category) || $category == 'all'){
      if($isMember){
        return $this->db->query("SELECT * FROM `News`;")->num_rows();
      }else{
        return $this->db->query("SELECT * FROM `News` WHERE `membersOnly` = false;")->num_rows();
      }
    }else{
      if($isMember){
        return $this->db->query("SELECT * FROM `News` WHERE `category` = " . $this->db->escape($category) . " ;")->num_rows();
      }else{
        return $this->db->query("SELECT * FROM `News` WHERE `category` = " . $this->db->escape($category) . " AND `membersOnly` = false;")->num_rows();
      }
    }

  }


  public function updateNewsById($news){
    $title = $this->db->escape($news['title']);
    $content = $this->db->escape($news['content']);
    $category = $this->db->escape($news['category']);
    $success = $this->db->simple_query("UPDATE `News`
                                        SET `title` = " . $title . ", "
                                         . "`content` = " . $content . ", "
                                         . "`membersOnly` = " . $news['membersOnly'] . ", "
                                         . "`category` = " . $category . ", "
                                         . "`dateEdited` = NOW() "
                                         . " WHERE `id` = " . $news['id'] . ";");
   return $success;
  }
  // public function register($member){
  //
  //   //echo $member['firstName'] . $member['lastName'] . $member['email'] . $member['username'] . $member['password'] . $member['isAdmin'];
  //   $jsonResponse = [];
  //   $query = $this->db->query("SELECT * FROM `Member` WHERE `email` = " . $this->db->escape(strtolower($member["email"]))
  //                             ." OR `username` = " . $this->db->escape(strtolower($member["username"])) . ";");
  //
  //   $row = $query->row();
  //   if(isset($row)){
  //
  //     if(strtolower($row->username) == strtolower($member['username'])){
  //       $jsonResponse["usernameExists"] = "Sorry, the username you have chosen already exists, please use a different username.";
  //     }
  //     if(strtolower($row->email) == strtolower($member['email'])){
  //       $jsonResponse["emailExists"] = "Sorry, that email is already taken. Please use a different email.";
  //     }
  //     // echo $member['firstName'] . $member['lastName'] . $member['email'] . $member['username'] . $member['password'] . $member['isAdmin'];
  //     $jsonResponse["success"] = false;
  //     return $jsonResponse;
  //   }
  //
  //
  //   $fn = $this->db->escape(ucfirst(strtolower($member["firstName"])));
  //   $ln = $this->db->escape(ucfirst(strtolower($member["lastName"])));
  //   $un = $this->db->escape(strtolower($member["username"]));
  //   $email = $this->db->escape(strtolower($member["email"]));
  //
  //   $options = [
  //     'cost' => 12
  //   ];
  //
  //   $password = password_hash($member["password"], PASSWORD_BCRYPT, $options);
  //   $success = $this->db->simple_query("INSERT INTO Member(firstName, lastName, username, email, password, isAdmin) VALUES(".$fn.",".$ln . ",". $un . ",". urldecode($email). ",'" . $password . "', false)");
  //
  //   if($success){
  //     $jsonResponse["success"] = true;
  //     return $jsonResponse;
  //   }else{
  //     //echo $fn . " " . $ln . " " . $un . " " . $email . " " . $password . " " . $member['isAdmin'];
  //     $jsonResponse["success"] = false;
  //     return $jsonResponse;
  //   }
  //
  // }
  //
  // public function login($member){
  //   $jsonResponse = [];
  //   $usernameOrEmail = $this->db->escape(strtolower($member['emailOrUsername']));
  //   $query = $this->db->query("SELECT * FROM `Member` WHERE `username` = " . $usernameOrEmail . " OR `email` = " . $usernameOrEmail);
  //   $result = $query->row();
  //   if(isset($result)){
  //     $dbpassword = $result->password;
  //     $options = [
  //       'cost' => 12
  //     ];
  //     if(password_verify($member["password"], $dbpassword)){
  //       $_SESSION['user'] = $result->id;
  //       $_SESSION['username'] = $result->username;
  //       $_SESSION['firstName'] = $result->firstName;
  //       $_SESSION['lastName'] = $result->lastName;
  //       $_SESSION['email'] = $result->email;
  //
  //       $jsonResponse["success"] = true;
  //       //$jsonResponse["url"] = "http://localhost:8888/caies/";
  //     }else{
  //       $jsonResponse["success"] = false;
  //     }
  //     return $jsonResponse;
  //  }else{
  //   $jsonResponse["success"] = false;
  //  }
  // //header("Content-Type: application/json");
  //     // echo json_encode($jsonResponse);
  //
  // }

}

?>
