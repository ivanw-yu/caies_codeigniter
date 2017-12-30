<?php if(isset($_SESSION['successMessage'])){ ?>
          <div id = "successMessage" class = "success"><?php echo $_SESSION['successMessage']; ?> </div>
          <script>
            function removeMessage(){
              $("#successMessage").fadeOut();
              //document.getElementById('successMessage').style.display = "none";
            }
            setTimeout(removeMessage, 2000);
          </script>
<?php }else if(isset($_SESSION['errorMessage'])){ ?>
          <div id = "errorMessage" class = "error"><?php echo $_SESSION['errorMessage']; ?></div>
          <script>
            function removeMessage(){
              $("#errorMessage").fadeOut();
              //document.getElementById('errorMessage').style.display = "none";
            }
            setTimeout(removeMessage, 2000);
          </script>
<?php } ?>
<div class = "question-page">
  <div class = "question-layout">
    <div class = "question-layout-header">
      <?php if(isset($_SESSION['user']) && $_SESSION['user'] == $question->authorId){ ?>
        <button id = "editButton" class = "btn btn-primary glyphicon glyphicon-pencil" style = "float: right"></button>
      <?php } ?>
      <h1> <?php echo $question->title; ?></h1>
    </div>
      <div class = "question-layout-content">
        <?php echo $question->content; ?>
        <p class = "author-date-section"> By <?php if(isset($member)){
                       echo ucfirst($member->firstName) . " " . ucfirst($member->lastName);
                     } else {
                       echo "Anonymous";
                     }
                ?>
                |
              <?php if(isset($question->dateEdited)){
                      echo "Updated " . $question->dateEdited;
                    }else{
                      echo $question->dateCreated;
                    } ?>
        </p>
      </div>

<div style = "box-sizing: border-box; padding-left: 25px; width: 100%;">
  <div style = "width: 40%; border-bottom: 1px solid black; position:relative; left: 5%;margin-top: 25px"> <?php echo count($replies) > 0 ? count($replies) : ''; ?> Answers </div>
</div>
  </div>
    <?php foreach($replies as $reply): ?>

      <div class = "reply-layout">
        <p><?php echo $reply->content; ?> </p>
        <p class = "author-date-section"> By <?php echo $reply->firstName . " " . $reply->lastName . " | " . $reply->dateCreated; ?> </p>
        <?php if(true || (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])){ ?>
                  <span><button class = "btn btn-sm btn-primary" style = "float: right" >Set As Correct Answer</button></span>
        <?php } ?>
      </div>
    <?php endforeach; ?>
  </div>
  <div style = "height: 300px; background-color: #f2f2f2; "></div>
  <div class = "reply-box">
    <form id = "reply-form" method = "post" action = "<?php echo base_url('replies/create'); ?>">
      <textarea name = "content" id = "content" rows = "3"></textarea>
      <!-- <br> -->
      <!--<input type = "hidden" name = "authorId" value = "<?php //echo $_SESSION['user'] ?>">-->
      <input type = "hidden" name = "questionId" value = "<?php echo $question->id; ?>">
      <input type= "submit" id = "replyButton" class = "btn btn-primary" value = "Reply">
    </form>
  </div>


<script>

  // $("#reply-form").on('submit', function(event){
  //   // event.preventDefault();
  //   $.ajax({
  //     url: "replies/create",
  //     type: "post",
  //     data: $("#reply-form").serialize(),
  //     success: function(serverResponse){
  //                 console.log($("#reply-form").serialize());
  //                 console.log(serverResponse);
  //       // var jsonResponse = JSON.parse(serverResponse);
  //       // if(jsonResponse.success){
  //       //   window
  //       // }
  //     }
  //   })
  // })
</script>
