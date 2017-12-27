<h1> News <?php if(isset($news)){ echo "Editing"; }else{ echo "Posting"; } ?> </h1>
<p> Please fill out the require fields in order to publish this news. </p>
<div class = "news-form-box">
  <form id = "news-create-form" method = "post">
    <div class = "form-group">
    <label for= "title">Title: </label>
    <input type = "text" name  ="title" class = "form-control" value = "<?php if(isset($news)){echo $news->title; } ?>">
    </div>
    <div class = "form-group">
    <label for="content"> News Content </label>
    <textarea name = "content" class = "form-control" rows="5"> <?php if(isset($news)){echo $news->content; } ?></textarea>
    </div>
    <label for = "membersOnly">Members Only</label>
    <input id = "membersOnly" name = "membersOnly" type = "checkbox">
    <br>
    <?php if(!isset($news)){ ?>
      <input id = "submitButton" type = "submit" value = "Submit" class = "btn btn-primary">
    <?php } ?>
  </form>

  <?php if(isset($news)){ ?>
    <button click = "saveEdit(<?php echo $news->id; ?>)" id = "saveEditButton" class = "btn btn-primary">Save</button>
  <?php } ?>

  <p id = "message"></p>
</div>
<br><br><br>
<script>

  $("#saveEditButton").on('click', function(event){
  // function saveEdit(id){
    console.log($('#news-create-form').serialize());
    $.ajax({
        url: 'news/edit/' + "<?php echo $news->id; ?>",
        type: 'post',
        data: $('#news-create-form').serialize(),
        success: function(serverResponse){

          console.log(serverResponse);
          var jsonResponse = JSON.parse(serverResponse);
          // $("#message").innerHTML = jsonResponse.success;
          if(jsonResponse.success){
            window.location.href = jsonResponse.url;
          }else{
             $("#message").innerHTML = "Error occurred, please try again later.";
          }
        }
      });
    //}
   });

  $("#news-create-form").on('submit', function(event){

    console.log($('#news-create-form').serialize());
    console.log($("#membersOnly").val());
    var membersOnly
    if($("#membersOnly").val() == "on"){
      $("#membersOnly").val(true);
      console.log($("#membersOnly").val());
    }else{
      $("#membersOnly").val(false);
      console.log($("#membersOnly").val());
    }
    $.ajax({
        url: 'news/create',
        type: 'post',
        data: $('#news-create-form').serialize(),
        success: function(serverResponse){
          console.log(serverResponse);
          var jsonResponse = JSON.parse(serverResponse);
          // $("#message").innerHTML = jsonResponse.success;
          if(jsonResponse.success){
            window.location.href = jsonResponse.url;
          }else{
             $("#message").innerHTML = "Error occurred, please try again later.";
          }
        }
      });
      event.preventDefault();
  });

</script>
