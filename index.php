<?php
    
    # ====================================================
    # Error handler from the browser
    ini_set("display_errors", 1);
    # To create the error file on this project
    ini_set("log_errors", 1);
    # Path where to save the error file
    ini_set("error_log", "/home/merci4dev/Documents/MyCode/Private/php-code/idea-trade/php_error.log");
    
    #==================================================
    # Requiring the routes.controllers.php file and make an instances of his class to  execute the index method. This convert the routes.php as the main path
    require "controllers/routes.controllers.php";

    $index = new RoutesController();
    $index->index();
    
?>