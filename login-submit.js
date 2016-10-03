(function($){
  $(document).ready(function(){
    $("form#loginform #wp-submit").click(function(e){
      e.preventDefault();
      var username = $("input#user_login").val();
      var password = $("input#user_pass").val();
      $.ajax({
        url: "/wp-api-call.php",
        type: "POST",
        dataType: "json",
        data: { username: username, password: password },
        success: function(data){
          if (data.error) {
            if (data.error == "invalid_grant") {
              $("#feedback").html("Invalid credentials.");
            }
          }
          if (data.access_token) {
            if (data.access_token != null) {
              $("#feedback").html("Login Success.");
            }
          }
          if (data.status == 'error') {
            $("#feedback").html(data.message);
          }
          if (data.status == 'submit_login') {
            $("#feedback").html(data.message);
            $("form#loginform").submit();
          }
        }
      });
    });
  });
})(jQuery);
