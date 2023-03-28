<?php

    require_once "models/delete.model.php";
    require_once "models/connection.php";
    
    class DeleteController {
        # Delete request to delete data
        #======================================
        static public function deleteData($table, $id, $nameId){
            $response = DeleteModel::deleteData($table, $id, $nameId);
            // echo '<pre>'; print_r( $response ); echo '</pre>';
            // return;
            
            $return = new DeleteController();
            $return->fncResponse($response);
            $return = Connection::close($return);
        }

        # controller response
        #======================================
        static public function fncResponse($response){
            
            if(!empty($response)){
                $json = array(
                    'status' => 200,
                    'result' => $response,
                );

            }else{
                $json = array(
                    'status' => 404,
                    'result' => 'Not Found',
                    'method' => 'Delete'
                );
            }
            echo json_encode($json, http_response_code($json["status"]));
        }
    }
    
?>