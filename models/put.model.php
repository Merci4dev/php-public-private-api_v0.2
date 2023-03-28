<?php
    require_once "connection.php";
    require_once "get.model.php";

    class PutModel{
        # Put request to dynamically edit data
        # ==============================================
        static public function putData($table, $data, $id, $nameId){
            
            # Validate that the id to which the update will be made does exist
            # ==============================================
            $respose =  GetModel::getDataFilter($table, $nameId, $nameId, $id, null, null, null, null);
          
            // echo '<pre>'; print_r($respose); echo '</pre>';
            if(empty($respose)){
                return null;

            }

            # update records
            # ==============================================
            $set = "";

            foreach ($data as $key => $value) {
                $set .= $key." = :".$key.",";
            }
            $set = substr($set, 0, -1);

            $sql = "UPDATE $table SET $set WHERE $nameId = :$nameId";

            # prepare the statement. $link is to be able to return the last inserted id
            $link = Connection::connect();
            $stmt = $link->prepare($sql);

            # bind the parameters
            #===============================================
            foreach ($data as $key => $value) {
                $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);                  
            }

            $stmt->bindParam(":".$nameId, $id, PDO::PARAM_STR);                  
            
            # if everything goes well execute the statement
             if($stmt->execute()){

                $respose = array(
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