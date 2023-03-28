<?php

    require_once "models/connection.php";
    require_once "controllers/get.controller.php";

    # convert the url params into a variable to allow to make request to to the DB. Convert The path values into an array. The indexs will be created through the /
    #==================================================
    $routesToArray = explode( '/', $_SERVER[ 'REQUEST_URI' ]);
    
    # Cleaning the path values to remove empty values. Returns just the valud after the first /
    $routesToArray = array_filter( $routesToArray);

    # condition when there are not request to the api. This determine if the path values are empty, or there are more path values after / manin domain 
    #==================================================
    if(count($routesToArray) == 0 ){
        
        # if the path values are empty then return home page
        require "views/home.php";
        return;
    }
    
    # Handle the request mehtods. Detect wich kind of request method we are ussing 
    #==================================================
    $request_mathod = $_SERVER['REQUEST_METHOD'];
    if(count($routesToArray) == 1 && isset( $request_mathod )){
        
        # Defining the table variable to be used in oll the request
        # Generate an array with the information that the table variable is capturing. Here we separate in index what comes to the left side or the right side of the url having as reference the ? sign
        $table = explode("?", $routesToArray[1])[0]; 

    
        # Implementate the api key to private the especific endpoints. We pass the header authorization to all the entpoints that we want to privatize. If the Authorization header property does not come in the request or if it is different from what I have stored in the environment variable, you cannot continue to operate with the api.
        #================================================
        if(!isset(getallheaders()["Authorization"]) || getallheaders()["Authorization"] != Connection::private_api_key()){

            # Validation for the public access table.
            if(in_array($table, Connection::publicAccess()) == 0){
                
                $json = array(
                    'status' => 400,
                    'result' => 'You are not authorized to access',
                );

                echo json_encode($json, http_response_code($json["status"]));
                return;

            }else{

                # Give public access to the courses table.
                #================================================
                $response = new GetController();
                $response -> getData($table, "*", null, null, null, null);
                return;
            }
            
        }
        
        
        # GET Method . All get endpoint are free for every user.
        #================================================
        if($request_mathod == "GET"){
                
            include "services/get.php";
            }
            
            
        # POST Method
        #================================================
        if($request_mathod == "POST"){
            
            include "services/post.php";
        }

        
        /*PUT Method */
        #================================================
        if($request_mathod == "PUT"){
            include "services/put.php";
        }
        
        
        /*DELETE Method */
        #================================================
        if($request_mathod == "DELETE"){
            
            include "services/delete.php";
        }
    }

?>
