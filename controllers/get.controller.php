<?php

    require_once "models/get.model.php";
    require_once "models/connection.php";

    class GetController{
        # Method to convert the requests response into json format. Here we build the structure of the json we will recive from the get.php file request.
        #===================================================
        public function fncResponse($response){

            if(!empty($response)){
                $json = array(
                    'status' => 200,
                    'total' => count($response),
                    'results' => $response
                );
                
            }else{
                $json = array(
                    'status' => 404,
                    'results' => 'Not Found'
                );
            }
            echo json_encode($json, http_response_code($json['status']));
        }

        # This method recieve the table from the get.php file and send it to the model. Retour all the data
        #===================================================
        static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt){
            # instance from the GetModel class and access to the getData method
            $response = GetModel::getData($table, $select, $orderBy, $orderMode,$startAt, $endAt);
            // echo '<pre>'; print_r($response ); echo '</pre>';
            // return;

        
            
            # controller response
            $return = new GetController();
            
            try {
                $return->fncResponse($response);

                $return = Connection::close($return);
                
            } catch (PDOException $e) {

                echo "Cont-getData: Error: " . $e->getMessage();
            }
        }
       
        # Methos which handle GET request to filter the data
        #===================================================
        static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode,$startAt, $endAt){
            try {
                
                # Intancies from the GetModel class and access to the getData method
                $response = GetModel::getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode,$startAt, $endAt);
                // echo '<pre>'; print_r($response ); echo '</pre>';
                // return;

                # controller response
                $return = new GetController();
                $return->fncResponse($response);
                $return = Connection::close($return);

            } catch (PDOException $e) {
                echo "getDataFilter: Error: " . $e->getMessage();
            }
        }
        
        # Peticiones GET sin filtro entre tabal ralacionadas
        #===================================================
        static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt){
            try {
                # intancies from the GetModel class and access to the getData method
                $response = GetModel::getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt);

                # controller response
                $return = new GetController();
                $return->fncResponse($response);
                $return = Connection::close($return);

            } catch (PDOException $e) {
                echo "getRelData: Error: " . $e->getMessage();
            }
        }

        # Peticiones GET con filtro entre tabal ralacionadas
        #===================================================
        static public function getRelDataFilter($rel, $type, $select,  $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){
            try {
                # intancies from the GetModel class and access to the getData method
                $response = GetModel::getRelDataFilter($rel, $type, $select,  $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);

                # controller response
                $return = new GetController();
                $return->fncResponse($response);
              $return = Connection::close($return);

            } catch (PDOException $e) {
                echo "getRelDataFilter: Error: " . $e->getMessage();
            }
        }

        #Peticiones GET para el buscador sin ralaciones
        #===================================================
        static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
            try {
                # intancies from the GetModel class and access to the getData method
                $response = GetModel::getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode , $startAt, $endAt);

                # controller response
                $return = new GetController();
                $return->fncResponse($response);
              $return = Connection::close($return);

            } catch (PDOException $e) {
                echo "getDataSearch: Error: " . $e->getMessage();
            }
        }

        # Peticiones GET del buscador con filtro entre tabal ralacionadas
        #===================================================
        static public function getRelDataSearch($rel, $type, $select,  $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
            try {
                # intancies from the GetModel class and access to the getData method
                $response = GetModel::getRelDataSearch($rel, $type, $select,  $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt);

                # controller response
                $return = new GetController();
                $return->fncResponse($response);
              $return = Connection::close($return);

            } catch (PDOException $e) {
                echo "getRelDataSearch: Error: " . $e->getMessage();
            }
        }

        # Peticiones GET para secleccion de rangos
        #===================================================
        static public function getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt,  $filterTo, $inTo){
            try {
                # intancies from the GetModel class and access to the getData method
                # $response = GetModel::getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);
                $response = GetModel::getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);

                # controller response
                $return = new GetController();
                $return->fncResponse($response);
              $return = Connection::close($return);

            } catch (PDOException $e) {
                echo "getDataRange: Error: " . $e->getMessage();
            }
        }

        # Peticiones GET para secleccion de rangos entre relaciones
        #===================================================
        static public function getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo){
            try {
                # intancies from the GetModel class and access to the getData method
                $response = GetModel::getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);

                # controller response
                $return = new GetController();
                $return->fncResponse($response);
              $return = Connection::close($return);

            } catch (PDOException $e) {
                echo "getRelDataRange: Error: " . $e->getMessage();
            }
        }
    }
    
?>