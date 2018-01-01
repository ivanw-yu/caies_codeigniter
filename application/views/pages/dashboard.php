<?php if(isset($_SESSION['username'])){ ?>
<div class = "dashboard-page">
<ul class = "side-nav">
  <li>Welcome <?php echo $_SESSION['firstName'] . " " . $_SESSION['lastName']; ?></li>
  <li>Settings <span class = "glyphicon glyphicon-cog"></span></li>
  <li>Questions</li>
  <li>News</li>
</ul>
<div class = "dashboard-content">
  <div id = "dashboard-news">
    <h1> News </h1>
    <div class = "content-top">
      <!-- <button><a href = "news/create"><div class = "glyphicon glyphicon-plus"></div></a></button> -->
      <button class = "glyphicon glyphicon-plus" onclick = "window.location.href = 'news/create';"></button>
    </div>
    <div class = "scroll-content">
      <?php foreach($news as $news_item): ?>
        <div style = "position: relative">
          <div class = "scroll-entry">
            <div class = "scroll-entry-header">
              <h4>Title: <?php echo $news_item->title; ?> </h4>
            </div>
            <!-- <p> Contents: </p> -->
            <p class = "news-content-summary"> <?php echo $news_item->content; ?></p>
          </div>
          <a href="news/edit/<?php echo $news_item->id; ?>"><button class = "glyphicon glyphicon-pencil child-hover" style = "position: absolute; top: 5px; right: 45px;border: 1px solid black;border-radius: 2px;"></button></a>
          <button onclick = "deleteNews(<?php echo $news_item->id; ?>)" class = "glyphicon glyphicon-remove child-hover" style = "position: absolute; top: 5px; right: 5px;border: 1px solid black;border-radius: 2px;"></button>
        </div>
      <?php endforeach ?>
    </div>
  </div>
  <div id = "dashboard-questions">
    <div class = "content-top">
      <button><div class = "glyphicon glyphicon-plus"></div></button>
    </div>
    <div class = "scroll-content">
      <?php for($i = 0; $i< 4; $i++){ ?>
      <div class = "scroll-entry">
        News <?php echo $i; ?>
      </div>
    <?php } ?>
    </div>
  </div>
  <div id = "dashboard-images">
    <div class = "glyphicon gyphicon-plus"></div>
  </div>
</div>
</div>
<?php }else{ ?>
  <div>
    401 Unauthorized.
  </div>
<?php } ?>


<!-- CONTINUE HERE. DELETION -->
<div id = "delete-form">
  <button id = "deleteButton" class = "btn btn-primary">Yes</button>
  <button click = "hideDeleteForm()" id = "cancelButton" class = "btn btn-primary">Cancel</button>
</div>

<script>

  // function edit(id){
  //   $.ajax({
  //     url: "news/edit/" + id,
  //     method: "get",
  //     success: function(serverResponse){
  //
  //     }
  //   })
  // }

  function hideDeleteForm(){
    document.getElementById("delete-form").style.display = "none";
  }
  function deleteNews(id){
    var deleteForm = document.getElementById("delete-form");
    deleteForm.style.display = "block";
    // var button1 = document.createElement("button");
    // button1.value= "Yes";
    // button1.class = "btn btn-primary";
    // button1.style.display = "inline";
    // button1.addEventListener('click', function(){
    //   console.log("clicked");
    // });
    //
    // var button2 = document.createElement("button");
    // button2.value= "Yes";
    // button2.class = "btn btn-primary";
    // button2.style.display = "inline";
    // button2.addEventListener('click', function(){
    //   console.log("clicked 2");
    //   button1.removeEventListener('click', function(){});
    //   button2.removeEventListener('click', function(){});
    //   deleteForm.removeChild(button1);
    //   deleteForm.removeChild(button2);
    //   deleteForm.style.display = "none";
    // });
    $("#cancelButton").on('click', function(){
      // console.log("clicked 2");
      // button1.removeEventListener('click', function(){});
      // button2.removeEventListener('click', function(){});
      // deleteForm.removeChild(button1);
      // deleteForm.removeChild(button2);
      console.log("cancel");
      deleteForm.style.display = "none";
    });

    $("#deleteButton").on('click', function(){
      // console.log("clicked 2");
      // button1.removeEventListener('click', function(){});
      // button2.removeEventListener('click', function(){});
      // deleteForm.removeChild(button1);
      // deleteForm.removeChild(button2);
      // deleteForm.style.display = "none";
      $.ajax({
        url: 'news/delete/' + id,
        method: "delete",
        success: function(serverResponse){
          console.log(serverResponse);
        }
      })
    });



    deleteForm.innerHTML = "Are you sure you want to delete news " + id + "?" + deleteForm.innerHTML;
    // deleteForm.appendChild(button1);
    // deleteForm.appendChild(button2);


    //alert("are you sure you want to delete news " + id + "?");
  }
</script>
