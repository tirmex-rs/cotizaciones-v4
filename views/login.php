<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bootstrap Login Form</title>
    <link rel="stylesheet" href="../resources/css/init.css">
    <link rel="icon" type="image/x-icon" href="/form-icon.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>

    <?php
include_once '../resources/php/conexion.php';
session_start();
0
$email =  $_POST['email'];
$password =  $_POST['password'];

$login =  "SELECT * FROM tbl_usuarios WHERE email_usuario = '$email' AND password_usuario = '$password'";
$resultado = mysqli_query($conexion, $login);
if (mysqli_num_rows($resultado) > 0) {
  // Si el usuario existe, redirigir a la página de inicio
  header('Location: ../index.php');
  exit;
  } else {
    // Si el usuario no existe, mostrar un mensaje de error
    echo '<div class="alert alert-danger" role="alert">';
    echo 'Usuario o contraseña incorrectos';
    echo '</div>';
    }



    ?>
  <section class="w-100 p-4 d-flex justify-content-center pb-4">
    <form>
      <!-- Email input -->
      <div data-mdb-input-init class="form-outline mb-4">
        <input type="email" id="form2Example1" class="form-control" />
        <label class="form-label" for="form2Example1">Email address</label>
      </div>
    
      <!-- Password input -->
      <div data-mdb-input-init class="form-outline mb-4">
        <input type="password" id="form2Example2" class="form-control" />
        <label class="form-label" for="form2Example2">Password</label>
      </div>
    
      <!-- 2 column grid layout for inline styling -->
      <div class="row mb-4">
        <div class="col d-flex justify-content-center">
          <!-- Checkbox -->
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="form2Example34" checked />
            <label class="form-check-label" for="form2Example34"> Remember me </label>
          </div>
        </div>
    
        <div class="col">
          <!-- Simple link -->
          <a href="#!">Forgot password?</a>
        </div>
      </div>
    
      <!-- Submit button -->
      <button data-mdb-ripple-init type="button" class="btn btn-primary btn-block mb-4">Sign in</button>
    
      <!-- Register buttons -->
      <div class="text-center">
        <p>Not a member? <a href="#!">Register</a></p>
        <p>or sign up with:</p>
        <button  data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
          <i class="fab fa-facebook-f"></i>
        </button>
    
        <button  data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
          <i class="fab fa-google"></i>
        </button>
    
        <button  data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
          <i class="fab fa-twitter"></i>
        </button>
    
        <button  data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
          <i class="fab fa-github"></i>
        </button>
      </div>
    </form>
  </section>
  </body>
</html>