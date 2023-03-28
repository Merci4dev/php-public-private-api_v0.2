<?php

    require_once "models/put.model.php";
    require_once "models/connection.php";
    
    class PutController {
        # put request to edit data
        # ==============================================
        static public function putData($table, $data, $id, $nameId){
            $response = PutModel::putData($table, $data, $id, $nameId);
            
            $return = new PutController();
            $return->fncResponse($response);
          $return = Connection::close($return);
        }

        # Controller response
        # ==============================================
        static public function fncResponse($response){
            
            if(!empty($response)){
                $json = array(
                    'status' => 200,
                    'result' => $response
                );

            }else{
                $json = array(
                    'status' => 404,
                    'result' => 'Not Found',
                    'method' => 'Put'
                );
            }
            echo json_encode($json, http_response_code($json["status"]));
        }
    }
    
?>