<?php

    require_once "connection.php";
    require_once "get.model.php";

    class DeleteModel{
        # Delete request to dynamically delete data
        # ==============================================
        static public function deleteData($table, $id, $nameId){
            
            # Validate that the id to which the update will be made does exist
            $respose =  GetModel::getDataFilter($table, $nameId, $nameId, $id, null, null, null, null);
            
            if(empty($respose)){
            
                return null;
            }

            # Eliminacion del registros
            $sql = "DELETE FROM $table WHERE $nameId = :$nameId";

            # prepare the statement. $link is to be able to return the last inserted id
            $link = Connection::connect();
            $stmt = $link->prepare($sql);

            # Bind the parameters
            $stmt->bindParam(":".$nameId, $id, PDO::PARAM_STR);                  
             
            #===============================================
            # if everything goes well execute the statement
             if($stmt->execute()){

                $respose = array(
                    "Comment" => "Data Delete Successfully"
                );
                return $respose;

            }else{
                # If there is an error
                return $link->errorInfo();

           }
        }
    }

?>