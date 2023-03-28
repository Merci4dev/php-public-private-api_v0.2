<?php 

    # Class to create the methods to connect the file routes.php  as main paht
    #==================================================
        class RoutesController{
            
            # Using the file routes.php
            #==================================================   
            public function index() {
                include "routes/routes.php";
            }
        }
    
?>

