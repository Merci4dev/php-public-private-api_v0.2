<?php

    require_once "models/connection.php";
    require_once "controllers/post.controller.php";

    if(isset($_POST)){

        # Detecting the columns. Para determimar que coinciden con las columnas de la db 
        # Separate the properties that come into the post variable in an array
        # para separar los nombres de las propiedades del arreglo
        # Capturing the properties of the post array when it is sent. array_keys() separates the names of the array's properties
        $columns = array();
           
        foreach (array_keys($_POST) as $key => $value) {
            array_push($columns, $value);
        }
        
        # Validate that tables and columns exist in the database
        # ======================================================
        if(empty(Connection::getColumnsData($table, $columns))){
            $json = array(
                'status' => 400,
                'result' => 'Error: Fields in the form do not mutch the DB Data'
            );

            echo json_encode($json, http_response_code($json['status']));
            return;
        }

            
        # Post controller instance to create a data in eny table
        $response = new PostController();

        # Sing up. Handling post requests for user registration
        # ======================================================
        if(isset($_GET["signup"]) && $_GET["signup"] == true){

            # if the suffix variable comes in the url and if we do not leave the user suffix by default
            $suffix = $_GET["suffix"] ?? "user";
            
            # Request response from the controller passing the table the post data and the suffix
            $response->postSignup($table, $_POST, $suffix);
        }

        # Sign In. Handling post requests for user login
        # ======================================================
        else if(isset($_GET["signin"]) && $_GET["signin"] == true){

            # If the suffix variable comes in the url and if we do not leave the user suffix by default
            $suffix = $_GET["suffix"] ?? "user";
            
            # Pass the received token to the validation method
            $response->postSignin($table, $_POST, $suffix);
        }
        else{

            # [x] post request for authorized users
            # ======================================================
            if(isset($_GET["token"])){
                
                // $tableToken =   $_GET["table"] ?? "users";
                // $suffix =   $_GET["suffix"] ?? "user";
                $tableToken =   $_GET["table"] ?? null;
                $suffix =   $_GET["suffix"] ??  null;
                
                # Passing the received token to the validation method
                $validate = Connection::tokenValidate($_GET["token"],$tableToken, $suffix);
                // echo '<pre>'; print_r( $validate); echo '</pre>';
                // return;

                # If validate variable is true: prompt controller response to edit data in any table
                # ======================================================
                if($validate == "ok"){
                    $response->postData($table, $_POST);
                    
                }

                # Error message when token has expired
                # ======================================================
                if($validate == "expired"){

                    $json = array(
                        'status' => 303,
                        'result' => 'Error: Token has expired',
                    );
                    echo json_encode($json, http_response_code($json["status"]));
                    return;
                }

                # Error message when the token does not match in the DB
                # ======================================================
                if($validate == "not-auth"){

                    $json = array(
                        'status' => 400,
                        'result' => 'Error: User not authorized',
                    );
                    echo json_encode($json, http_response_code($json["status"]));
                    return;
                }
            }else{

                # Error message when token is requested to perform the action
                # ======================================================
                $json = array(
                    'status' => 400,
                    'result' => 'Error: Authorization reqquied',
                );
                echo json_encode($json, http_response_code($json["status"]));
                return;
            }
        }
    }

?>

