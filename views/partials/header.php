<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My php app</title>
    
    <!-- Bootstrap Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="./statics/css/main-styles.css">   
    
    <!-- Bootstrap Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- jQuery CDN -->
    <!-- <script defer src="https://code.jquery.com/jquery-3.6.3.slim.min.js" integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" defer></script>
    <script defer src="./statics/js/main.js"></script>


    <!-- js script -->
    <!-- Identificando la ruta del js script para que lolo carge en el archivo index -->
    <!-- A las rutas se le pueden psar query. Aqui validamos que si se le pasa un query a la ruta que se elimine -->

    <!-- 1 Guardando la URI donde estamos. parse_url le pasamos una url y esta nos extrae los ditintos componente que esta tiene (REQUEST_URI). PHP_URL_PATH , esta funcoin extrae solo el path-->
    <?php $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); ?>
      
  
    <!-- 2 validacion de la uri a cargar para que un escript se ejecute o  no en determinada ruta   -->
    <?php
      // if ($uri == "/php_course/Projects/MyApp/" || $uri == "php_course/Projects/MyApp/index.php") :
      if ($uri == "/localhost/php_course/Projects/MyApp/contacts.php" || $uri == "/opt/lampp/htdocs/php_course/Projects/MyApp/home.php") :
    ?>
      <script defer src="../statics/js/welcome.js"></script>
    <?php endif ?>
    

</head>
<body>
    <!-- Including the Navbar -->
    <?php include "navbar.php" ?>
    
  <!-- Main Start Section -->
  <main>

    <!-- Adding the flash messages-->
    <?php if (isset($_SESSION['flash'])) : ?>
      <div class="container-expand alert-container slide-in-top">
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
          <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
          </symbol>
        </svg>
        
          <div class=" alert alert-success d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
              <use xlink:href="#check-circle-fill" />
            </svg>
              <?= $_SESSION['flash']['message'] ?>
          </div>
      </div>
    
      <!-- Despues que sale el message lo borramos  -->
      <?php unset($_SESSION["flash"]) ?>
      <?php 
        // sleep(1);
        header('location:contacts.php')
      ?>
    <?php endif ?>


  <!-- Error Message alert -->
  <!-- <?php if($error): ?>
    <div class="alert alert-danger d-flex slide-in-top" role="alert">
        <!-- <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg> -->
        <?= $error ?>
    </div>
<?php endif ?> -->








