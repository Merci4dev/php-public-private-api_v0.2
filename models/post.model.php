<?php
    require_once "models/connection.php";

    class PostModel{

        # Post request to create data dynamically
        # ==============================================
        static public function postData($table, $data){
            
            $columns = "";
            $params = "";
            
            foreach ($data as $key => $value) {
                $columns .=$key.",";
                $params .= ":".$key.",";
            }
            
            # Delete the last , from the query string
            $columns = substr($columns, 0, -1);
            $params = substr($params, 0, -1);
      
            # Creation of the sql statement dynamically
            #===============================================
            $sql = "INSERT INTO $table ($columns) VALUES ($params)";
            
            # prepare the statement. $link is to be able to return the last inserted id
            $link = Connection::connect();
            $stmt = $link->prepare($sql);
            
            # bind parameters
            #===============================================
            foreach ($data as $key => $value) {
                $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);                  
            }
                        
            # if all goes well execute the statement
            #===============================================
            if($stmt->execute()){

                $respose = array(
                    "LastId" => $link->lastInsertId(),
                    "Comment" => "The proccess was Successfully"
                );
                return $respose;

            }else{
                # If there is an error
                return $link->errorInfo();
            }
        }
    }
    
?>