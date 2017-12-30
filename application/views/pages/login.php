<!-- if message set, this means the page is loaded due to trying to access pages accessible only to members/admin, so the user must be logged in-->

<div class = "box-form">
  <?php if(isset($message)) { echo "<div style = 'text-align: center'>" . $message . "</div>"; } ?>
  <h1> Login</h1>
  <p> Please enter your username/email and password. </p>
  <form method = "post" action = "server/member_login.php" id = "login-form">
    <div class = "form-group">
      <label> Username/Email </label>
      <input id = "email-or-username" type = "text" name = "emailOrUsername" class = "form-control">
    </div>
    <div class = "form-group">
      <label> Password </label>
      <input id = "password" type = "password" name = "password" class = "form-control">
    </div>
    <p id = "login-message">Incorrect username/email or password</p>
    <input type = "submit" value = "Submit" class="btn btn-primary">

  </form>
</div>

<script>

var loginMessage = document.getElementById("login-message");
$("#login-form").submit(function(event){

    console.log($('#login-form').serialize());
    $.ajax({
        url: 'auth/login',
        type: 'post',
        data: $('#login-form').serialize(),
        success: function(serverResponse) {
                    //$("#single-page").html(serverResponse);
                    console.log(serverResponse);
                    var jsonResponse = JSON.parse(serverResponse);
                    if(jsonResponse.success){
                      //window.location.pathname = "caies_codeigniter-3.1.6/dashboard";
                      var url = "<?php if(isset($news_id)){ echo base_url('news/view/' . $news_id); }else{ echo ''; } ?>"
                      window.location.href = url.length > 0 ? url : jsonResponse.url;

                    }else{
                      loginMessage.style.visibility = "visible";
                    }
                }
        });

    event.preventDefault();
});
</script>
