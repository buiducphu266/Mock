<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../public/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../public/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

        <div class="input-group mb-3">
          <input type="email" id="username" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" id="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">

            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" onclick="submit()" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>

      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../../public/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
  let url = 'http://localhost/PHP-MVC-API/user/login';
  function setCookie(cname, cvalue, exseconds) {
    var d = new Date();
    d.setTime(d.getTime() + exseconds);
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
  function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) === ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) === 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  function submit(){
    let data = {
      "username" : $('#username').val(),
      "password" : $('#password').val(),
    }
    let option = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }
      fetch(url, option)
          .then(function (response){
            return response.json();
          })
          .then(function (response){
            if (response.success){
              setCookie("access_token", `${response.data.access_token}`, `${response.data.access_token_expiry_seconds}`);
              setCookie("user_name", `${response.data.user_name}`, `${response.data.access_token_expiry_seconds}`);
              setCookie("user_id", `${response.data.user_id}`, `${response.data.access_token_expiry_seconds}`);
              window.location = "?page=admin"
                // console.log(document.cookie)
            }
            else {
              alert(response.message)
            }
          })
  }
</script>
<!-- Bootstrap 4 -->
<script src="../../public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../public/dist/js/adminlte.min.js"></script>

</body>
</html>
