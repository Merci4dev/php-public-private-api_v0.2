<?php

   require_once "models/connection.php";
   require_once "controllers/put.controller.php";
   
   if(isset($_GET["id"]) && isset($_GET["nameId"])){
      
        # Capture the information sent in the form
        $data = array();
        parse_str(file_get_contents('php://input'), $data);
        
        # Separate properties in an array
        $columns = array();
        
        # Capturing the properties of the post array when it is sent. array_keys() separates the names of the array's properties
        foreach (array_keys($data) as $key => $value) {

        array_push($columns, $value);
        }

        #Validate that the column exists into the db. Avoid the repetition of columns and absence
        array_push($columns, $_GET["nameId"]);
        $columns = array_unique($columns);
        
        # Validate that tables and columns exist
        # ======================================================
        if(empty(Connection::getColumnsData($table, $columns))){
            $json = array(
            'status' => 400,
            'result' => 'Error: Fields in the form do not mutch the DB Data'
            );
            echo json_encode($json, http_response_code($json['status']));
            return;
        }

        # Validate put request for authorized user 
        # ======================================================
        if(isset($_GET["token"])){
                
            $tableToken =   $_GET["table"] ?? "users";
            $suffix =   $_GET["suffix"] ?? "user";

            # Passing the received token to the validation method
            $validate = Connection::tokenValidate($_GET["token"],$tableToken, $suffix);

            # If validate variable is true: prompt controller response to edit data in any table
            # ======================================================
            if($validate == "ok"){
        
                #  solocitar repuesta del controlador par editar datos en cualquier tabla
                $response = new PutController();
                $response->putData($table, $data, $_GET["id"], $_GET["nameId"]);
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
            # # Error message when token is requested to perform the action
            # ======================================================
           $json = array(
               'status' => 400,
               'result' => 'Error: Authorization reqquied',
           );
           echo json_encode($json, http_response_code($json["status"]));
           return;
        }
 
   }

?>