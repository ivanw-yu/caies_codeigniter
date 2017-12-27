<div class = "box-form">
  <h1> Member Registration </h1>
  <p> Please fill out the required fields. </p>
  <form method = "post" action = "auth/register" name = "register" id = "registration-form">
    <div class = "form-group">
      <label for = "first-name"> First Name </label>
      <input id = "first-name" type = "text" name = "firstName" class = "form-control" required>
      <p id = "first-name-message"> &nbsp; </p>
    </div>
    <div class = "form-group">
      <label> Last Name </label>
      <input id = "last-name" type = "text" name = "lastName" class = "form-control" required>
      <p id = "last-name-message"> &nbsp; </p>
    </div>
    <div class = "form-group">
      <label> Username </label>
      <input id = "username" type = "text" name = "username" class = "form-control" required>
      <p id = "username-message"> &nbsp; </p>
    </div>
    <div class = "form-group">
      <label> Email </label>
      <input id = "email" type = "text" name = "email" class = "form-control" required>
      <p id = "email-message"> &nbsp; </p>
    </div>
    <div class = "form-group">
      <label> Password </label>
      <input id = "password" type = "password" name = "password" class = "form-control" required>
      <p id = "password-message"> &nbsp; </p>
    </div>
    <input id = "registration-submit" type = "submit" value = "Submit" class="btn btn-primary">
  </form>
</div>

<script>


  var password = document.getElementById("password");
  var firstName = document.getElementById("first-name");
  var lastName = document.getElementById("last-name");
  var email = document.getElementById("email");
  var userName = document.getElementById("username");

  var passwordMessage = document.getElementById("password-message");
  var firstNameMessage = document.getElementById("first-name-message");
  var lastNameMessage = document.getElementById("last-name-message");
  var emailMessage = document.getElementById("email-message");
  var usernameMessage = document.getElementById("username-message");

  var submitButton = document.getElementById("registration-submit");


  //var passwordMessage = document.getElementById("");

  var emailMatch = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;



  function validateEmail(email){
    return emailMatch.test(email);
  }

  function validateName(name){
    return /^[a-zA-Z]{2,15}$/.test(name);
  }

  function validateUsername(username){
    return /^[(0-9)?a-zA-Z]{3,10}$/.test(username) && !/[\)\(#?!@$%^&*\-+_=\{\}\[\]:;"'><\,\.\/`~\?]/.test(username);
  }

  function validatePassword(password){
    return /[A-Z]/.test(password) && /[a-z]/.test(password) && /[0-9]/.test(password) && /[#?!@$%^&*\-]/.test(password) && password.length >= 5;
  }

  function validateRegistrationForm(firstName, lastName, username, email, password){
    if(validateEmail(email) && validateUsername(username) && validateName(firstName) && validateName(lastName)
        && validatePassword(password)){
          // submitButton.disabled = false;
          return true;
        }else{
          return false;
        }
  }
  //var usernameMatch = /[]/
  $(document).ready(function(){
    $("#email").on('input', function(event){

      if(emailMatch.test(email.value)){
        emailMessage.innerHTML = "Email valid";
        emailMessage.style.color = "green";
        if(validateRegistrationForm(firstName.value, lastName.value, username.value, email.value, password.value)){
          submitButton.disabled = false;
          console.log("ENABLED - EM");
        }
      }else{
        submitButton.disabled = true;
        emailMessage.innerHTML = "Email invalid";
        emailMessage.style.color = "red";
        // emailMessage.classList.toggle("error");
        //$("#email-message").toggleClass("error");
      }
    });

    $("#first-name").on('input',function(event){

      if(validateName(firstName.value)){
        firstNameMessage.innerHTML = "Name valid";
        firstNameMessage.style.color = "green";
        if(validateRegistrationForm(firstName.value, lastName.value, username.value, email.value, password.value)){
          submitButton.disabled = false;
          console.log("ENABLED - EM");
        }
      }else{
        submitButton.disabled = true;
        firstNameMessage.innerHTML = "Name invalid";
        firstNameMessage.style.color = "red";
      }
    });

    $("#last-name").on('input',function(event){
      //lastNameMessage.style.visibility = "visible";
      if(validateName(lastName.value)){
        lastNameMessage.innerHTML = "Name valid";
        lastNameMessage.style.color = "green";
        if(validateRegistrationForm(firstName.value, lastName.value, username.value, email.value, password.value)){
          submitButton.disabled = false;
          console.log("ENABLED - EM");
        }
      }else{
        submitButton.disabled = true;
        lastNameMessage.innerHTML = "Name invalid";
        lastNameMessage.style.color = "red";
      }
    });

    $("#username").on('input',function(event){

      if(validateUsername(username.value)){
        usernameMessage.innerHTML = "Username valid";
        usernameMessage.style.color = "green";
        if(validateRegistrationForm(firstName.value, lastName.value, username.value, email.value, password.value)){
          submitButton.disabled = false;
          console.log("ENABLED - EM");
        }
      }else{
        submitButton.disabled = true;
        usernameMessage.innerHTML = "Username invalid. (Username must be 3-11 characters, excluding special characters)";
        usernameMessage.style.color = "red";
      }
    });

    $("#password").on('input',function(event){

      if(validatePassword(password.value)){
        passwordMessage.innerHTML = "Password valid";
        passwordMessage.style.color = "green";
        if(validateRegistrationForm(firstName.value, lastName.value, username.value, email.value, password.value)){
          submitButton.disabled = false;
          console.log("ENABLED - EM");
        }
      }else{
        submitButton.disabled = true;
        passwordMessage.innerHTML = "Password invalid. (Password must be atleast 5 characters, including atleast 1 uppercase alphabet, lowercase alphabet, number, and special character)";
        passwordMessage.style.color = "red";
      }
    });

    // $("#registration-form").change(function(event){
    //   if(validateEmail(email) && validateUsername(username) && validateName(firstName) && validateName(lastName)
    //       && validatePassword(password)){
    //         submitButton.disabled = false;
    //       }
    // });

    $("#registration-form").submit(function(event){

        console.log($('#registration-form').serialize());
        $.ajax({
            url: 'auth/register',
            type: 'post',
            data: $('#registration-form').serialize(),
            success: function(serverResponse) {
                        //$("#single-page").html(serverResponse);
                        console.log(serverResponse);
                        var res = JSON.parse(serverResponse);
                        console.log(res);
                        if(res.success){
                          window.location.href = res.url;
                        }else{
                          if(res.usernameExists){
                            usernameMessage.style.color = "red";
                            usernameMessage.innerHTML = res.usernameExists;
                          }
                          if(res.emailExists){
                            emailMessage.style.color = "red";
                            emailMessage.innerHTML = res.emailExists;
                          }
                          if(res.error){
                            // var message = document.createElement("div");
                            // message.innerHTML = res.error;
                            // message.style.height = "40px";
                            // message.style.width = "90%";
                            // message.style.position = "fixed";
                            // message.style.top = "40px";
                            // message.style.left = "5%";
                            // message.style.color = "red";
                            // document.body.appendChild(message);
                          }
                        }
                        //window.location.href = serverResponse;
                    }
            });

        event.preventDefault();
    });
  });

  function sendData(){
    var fn = document.getElementById("fn").value;
    var ln = document.getElementById("ln").value;
    var un = document.getElementById("un").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    // $("#registration-form").submit(function(event){
    //     event.preventDefault();
    //     $.ajax({
    //         url: 'server/member_registration_db.php',
    //         type: 'post',
    //         dataType: 'json',
    //         data: $('#registration-form').serialize(),
    //         success: function(serverResponse) {
    //                     $("#single-page").text(serverResponse);
    //                 }
    //     });
    // }

    // var xmlhttp = new XMLHttpRequest();
    // xmlhttp.onreadystatechange = function() {
    //     if (this.readyState == 4 && this.status == 200) {
    //         document.getElementById("single-page").innerHTML = this.responseText;
    //     }
    // };
    // xmlhttp.open("POST", "server/member_registration_db.php?firstName=" + fn + "&lastName=" +ln + "&username=" + un + "&email=" + email +"&password=" + password, true);
    // xmlhttp.send();
  }
</script>
