<?php
    
    require_once "models/connection.php";
    require_once "controllers/delete.controller.php";
    
    if(isset($_GET["id"]) && isset($_GET["nameId"])){
      
        $columns = array($_GET["nameId"]);
        
        # Validate that tables and columns exist before deleting a data
        # ======================================================
        if(empty(Connection::getColumnsData($table, $columns))){
            $json = array(
                'status' => 400,
                'result' => 'Error: Fields in the form do not mutch the DB Data'
            );
            echo json_encode($json, http_response_code($json['status']));
            
            $return = Connection::close($json);
            
            return;
        }

        if(isset($_GET["token"])){
            
            $tableToken =   $_GET["table"] ?? "users";
            $suffix =   $_GET["suffix"] ?? "user";
           
            # Passing the received token to the validation method
            $validate = Connection::tokenValidate($_GET["token"],$tableToken, $suffix);

            # If validate variable is true: prompt controller response to edit data in any table
            if($validate == "ok"){

                # request response from controller to delete data in any table
                $response = new DeleteController();
                $response->deleteData($table, $_GET["id"], $_GET["nameId"]);
                // $return = Connection::close();
            }

            # Error message when token has expired
            # ======================================================
            if($validate == "expired"){

                $json = array(
                    'status' => 303,
                    'result' => 'Error: Token has expired',
                );
                echo json_encode($json, http_response_code($json["status"]));
                // $return = Connection::close($json);
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
                // $return = Connection::close($json);
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
            // $return = Connection::close($json);
            return;
        }
    }

?>