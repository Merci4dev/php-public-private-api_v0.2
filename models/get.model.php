<?php

    require_once "connection.php";
   
    # Handle the sql get sentence to retrive all info from the table. Recive the table ($table represents the database table name). Request without filters
    class GetModel{
        # Methos which handle all get request
        #===================================================
        static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt){

            # Validate the database table and columns exists. This can be used a the beginning of every method
            $selectArray = explode(",", $select);
            if(empty(Connection::getColumnsData($table, $selectArray))){
                
                return null;
            } 
            
            # query sql get sentence. The $select variable make possible to use the * for all the data or just any columns
            $sql = "SELECT $select FROM $table";
           
            # Condition for the order of the results in the search but does not limit the results
            if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
            }

            # For when we are ordering the data and also limiting
            if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt ";
            }

            # For when we are limiting queries but not ordering them
            if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $table LIMIT $startAt, $endAt ";
            }

            # prepare the statement
            $stmt = Connection::connect()->prepare($sql);

            # Error handler session
            #===================================================
            try {
                # execute the statement
                $stmt->execute();

                // retorna la respuestas sin el numero de indices 
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
                

            } catch (PDOException $e) {
                return null;
            }
        }

        # Methos which handle GET request to filter the data
        # Handle the sql get sentence. recive the table . Request with filters
        #===================================================
        static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){
        
            # Divede all coma finded values from in de linkTo selectvariable. linkToArray represent the column names
            $linkToArray = explode(",", $linkTo);
            $selectArray = explode(",", $select);
            
            # With the array_push method we insert the linktToArray indices inside selectToArray dynamically
            foreach ($linkToArray as $key => $value) {
                array_push($selectArray, $value);
            }

            # to remove duplicate indexes if they exist
            $selectArray = array_unique($selectArray);
         
            # Validation of existence of the table and columns
            # Validate the database table and columns exists. This can be used a the beginning of every method
            #===================================================
            if(empty(Connection::getColumnsData($table, $selectArray))){
                
                return null;
            } 

            $linkToArray = explode(",", $linkTo);
            $equalToArray = explode(",", $equalTo);
            // echo '<pre>'; print_r($linkToArray); echo '</pre>';
            $linkToText = "";
            
            # if the linkToArray variable have more htan 1 index we loop through it values
            if(count($linkToArray) > 1){
                foreach($linkToArray as $key => $value){
                    
                    # if the key variblae i biger  than 0 we take the values tartin at the index 2
                    if($key > 0){
                        $linkToText .= "AND " .$value." = :".$value." ";
                    }
                }
            }

            # Filter multiple parameters dinamicment
            $sql = "SELECT $select  FROM $table WHERE $linkTo = :$linkTo";

            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";

            # No sorting and no filtering data
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";

            # Condition for the order of the results in the search but does not limit the results
            if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
            }

            # For when we are ordering the data and also limiting
            if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt ";
            }

            # For when we are limiting queries but not ordering them
            if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $table LIMIT $startAt, $endAt ";
            }

            
            # Condicionla para el oden de los resusltados en la busqueda pero  no limita los resultados 
            if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
                
            }
            
            #Condicionla para el oden de los resusltados en la busqueda sin limitaciones 
            if($orderBy != null && $orderMode != null  ){
                $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
            }              

            $stmt = Connection::connect()->prepare($sql);
          

            # binding the parameters
            foreach ($linkToArray as $key => $value) {
                $stmt->bindParam(":".$value, $equalToArray[$key], PDO::PARAM_STR);
            }
            
            try {
            # execute the statement
            $stmt->execute();

            # retorna la respuestas sin el numero de indices 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                return null;
            }
        }

        # Unfiltered GET requests between related tables
        #==================================================
        static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt){
           
            # Con esta loginca posemos hacer relaciones dinamicamente entre multiples tablas
           
                # Column existence validation
                $relArray = explode(",", $rel);
                $typeArray = explode(",", $type);
                $innerJoinText = "";

                # if comme more than one table
                if(count($relArray) > 1){
                    # With this foreach we establish the relationship between the tables dynamically
                    foreach($relArray as $key => $value){

                        #===================================================
                        # Validate the database table exists. This can be used a the beginning of every method
                        # If the table variable does not come as parms then the validation is done in the foreach

                        $tableValidator = Connection::getColumnsData($value,["*"]);
                        if(empty($tableValidator)){
                            return null;
                        } 

                        if($key > 0){
                            $innerJoinText .= "INNER JOIN ".$value." ON " .$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0] ." = ". $value.".id_".$typeArray[$key]." ";
                        }
                    }

                    # ===================================================
                    # No sorting and no limiting data request
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText";
                    
                    # ===================================================
                    # Order the results in the search but does not limit the results
                    if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                        $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode";
                    }

                    # Ordering the data and also limiting the results
                    # ===================================================
                    if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                        $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt ";
                    }

                    # For when we are limiting queries but not ordering them
                    # ===================================================
                    if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                        $sql = "SELECT $select FROM $relArray[0] $innerJoinText LIMIT $startAt, $endAt ";
                    }

                    # prepare the statement
                    $stmt = Connection::connect()->prepare($sql);

                    # validate error atteached to the session
                    try {
                        # execute the statement
                        $stmt->execute();

                    } catch (PDOException $e) {
                        return null;

                    }

                    # return the result  which will be stored into teh response variable
                    # retorna la respueste con el numero de indices y las columnas
                     return $stmt->fetchAll();

                    # retorna la respuestas sin el numero de indices 
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                }else{
                    return null;
                }    
           
        }

        # Filter GET requests between related tables
        #===================================================
        static public function getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){
           
            # loginca to dynamically make relationships between multiple tables
            #===================================================
            # Filters organizations
            $linkToArray = explode(",", $linkTo);
            $equalsToArray = explode(",", $equalTo);
            $linkToText = "";
               
               if(count($linkToArray) > 1){
                   foreach($linkToArray as $key => $value){

                        if($key > 0){
                           $linkToText .= "AND " .$value." = :".$value." ";
                        }
                    }
                }
                
                #===================================================
                #  Relations organizations
                $relArray = explode(",", $rel);
                $typeArray = explode(",", $type);
                $innerJoinText = "";

                if(count($relArray) > 1){
                    foreach($relArray as $key => $value){
                        # Validacion de existencia de la tabla y columnas en la DB.
                        # Si la variable table no viene como parms entonce la validacion se hace en el foreach
                        #===================================================
                        $validate = Connection::getColumnsData($value,["*"]);
                        if(empty($validate)){
                            return null;
                        }

                        if($key > 0){
                            $innerJoinText .= "INNER JOIN ".$value." ON " .$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0] ." = ". $value.".id_".$typeArray[$key]." ";
                        }
                    }

                # No sorting and no limiting data 
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";
                
                # Conditional for the order of the results in the search but does not limit the results
                if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText  WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
                   
                }

                # ordering the data and also limiting
                if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText  WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt ";
                }

                # limiting queries but not ordering them
                if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText  WHERE $linkToArray[0] = :$linkToArray[0] $linkToText LIMIT $startAt, $endAt ";
                }

                # prepare the statement
                $stmt = Connection::connect()->prepare($sql);

                # binding the parameters
                foreach ($linkToArray as $key => $value) {
                    $stmt->bindParam(":".$value, $equalsToArray[$key], PDO::PARAM_STR);
                }
                # El trycatch tambien se puee ejecutar aqui al momento de la ejecucion del codiogo y no tieneque englobar todo el codigo
                #===========================================
                try {
                # execute the statement
                     $stmt->execute();

                } catch (PDOException $e) {
                    # se puede retornar el error de exepcion con mas informacion del error
                    echo "RelDataFilter: Error: " . $e->getMessage();

                }

                # returns the answers without the number of indices
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            }else{
                return null;
            }    
           
        }

        # GET requests for unrelated finder
        #===================================================
        static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
            # to remove duplicate indexes if they exist
            $linkToArray = explode(",", $linkTo);
            $selectArray = explode(",", $select);
            
            foreach ($linkToArray as $key => $value) {
                array_push($selectArray, $value);
            }

            #===================================================
            # Validation of existence of the table and columns in the DB
            if(empty(Connection::getColumnsData($table, $selectArray ))){

                return null;
            }

            $searchArray = explode(",", $search);
            $linkToText = "";

            if(count($linkToArray) > 1){
                
                foreach($linkToArray as $key => $value){
                    if($key > 0){
                        $linkToText .= "AND " .$value." = :".$value." ";
                    }
                }
            }

                # No sorting and no filtering data
                $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ";
                
                # for the order of the results in the search but does not limit the results
                if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                    $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText  ORDER BY $orderBy $orderMode";
                    
                }

                # Ordering the data and also limiting
                if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                    $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText  ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt ";
                }

                # limiting queries but not ordering them
                if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                    $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText  LIMIT $startAt, $endAt ";
                }

                # prepare the statement
                $stmt = Connection::connect()->prepare($sql);

                # Enalzando los parametros
                foreach ($linkToArray as $key => $value) {
                    if($key > 0){
                        $stmt->bindParam(":".$value, $searchArray[$key], PDO::PARAM_STR);
                    }
                }

                try {
                # execute the statement
                    $stmt->execute();

                    # returns the answers without the number of indices 
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                echo "DataSearch: Error: " . $e->getMessage();
            }
        }

        # Filter search GET requests between related tables
        #===================================================
        static public function getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){
           
            # loginca posemos hacer relaciones dinamicamente entre multiples tablas
               # Organization of filters
               $linkToArray = explode(",", $linkTo);
                $searchArray = explode(",", $search);
                $linkToText = "";

                if(count($linkToArray) > 1){
                    
                    foreach($linkToArray as $key => $value){

                        if($key > 0){
                            $linkToText .= "AND " .$value." = :".$value." ";
                        }
                    }
                }
                
                #===================================================
                # Organization of relations
                $relArray = explode(",", $rel);
                $typeArray = explode(",", $type);
                $innerJoinText = "";

                if(count($relArray) > 1){
                    foreach($relArray as $key => $value){

                        #===================================================
                        # Validation of existence of the table and columns in the DB.
                        if(empty(Connection::getColumnsData($value,["*"]))){
                            return null;
                        }

                        if($key > 0){
                            $innerJoinText .= "INNER JOIN ".$value." ON " .$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0] ." = ". $value.".id_".$typeArray[$key]." ";
                        }
                    }

                # For searches with relationships. No sorting and no limiting data
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText";
                
                # for the order of the results in the search but does not limit the results
                if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
                }

                # ordering the data and also limiting
                if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt ";
                }

                # limiting queries but not ordering them
                if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText LIMIT $startAt, $endAt ";
                }

                # prepare the statement
                $stmt = Connection::connect()->prepare($sql);

                # binding the parameters
                foreach ($linkToArray as $key => $value) {
                    if($key > 0){
                        $stmt->bindParam(":".$value, $searchArray[$key], PDO::PARAM_STR);
                    }
                }
            
                }else{
                    return null;
                }    

            try {
                # execute the statement
                $stmt->execute();

                # returns the answers without the number of indices
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                echo "RelDataFilter: Error: " . $e->getMessage();
            }
        }

        # GET requests for selection of ranges without ordering and without data limits
        #===================================================
        static public function getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo){
          
            # to remove duplicate indexes if they exist
            $linkToArray = explode(",", $linkTo);
            
            # In the case that there is no filter in the search
            if($filterTo != null){
                $filterToArray = explode(",", $filterTo);
            }else{
                # If the filter is null we generate an empty array
                $filterToArray = array();
            }

            $selectArray = explode(",", $select);
            
            foreach ($linkToArray as $key => $value) {
                array_push($selectArray, $value);
            }

            foreach ($filterToArray as $key => $value) {
                array_push($selectArray, $value);
            }

            $selectArray = array_unique($selectArray);

            # Validation of existence of the table and columns in the DB
            #===================================================
            if(empty(Connection::getColumnsData($table, $selectArray ))){

                return null;
            }

            $filter = "";
            if($filterTo != null && $inTo != null){
                $filter = 'AND '.$filterTo.' IN ('.$inTo.')';
            }

            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ";
            
            # for the order of the results in the search but does not limit the results
            if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter  ORDER BY $orderBy $orderMode";
            }

            # when we are ordering the data and also limiting
            if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter  ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt ";
            }

            # when we are limiting the queries but not ordering them
            if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter   LIMIT $startAt, $endAt ";
            }

            # prepare the statement
            $stmt = Connection::connect()->prepare($sql);

            try {

                # execute the statement
                $stmt->execute();

                # returns the answers without the number of indices
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
               

            } catch (PDOException $e) {
                echo "M-getDataRange: Error: " . $e->getMessage();
            }
        }

        # GET requests for range selection with relations
        #===================================================
        static public function getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo){
          
            $filter = "";
            if($filterTo != null && $inTo != null){
                $filter = 'AND '.$filterTo.' IN ('.$inTo.')';
            }

            $relArray = explode(",", $rel);
            $typeArray = explode(",", $type);
            $innerJoinText = "";

            if(count($relArray) > 1){
                # With this foreach we establish the relationship between the tables dynamically
                foreach($relArray as $key => $value){

                    # Validation of existence of the table and columns in the DB
                    #===================================================
                    if(empty(Connection::getColumnsData($value,["*"]))){
                        return null;
                    }

                    if($key > 0){
                        $innerJoinText .= "INNER JOIN ".$value." ON " .$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0]." = ".$value.".id_".$typeArray[$key]." ";
                    }
                }

                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ";
                
                # for the order of the results in the search but does not limit the results
                if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter  ORDER BY $orderBy $orderMode";
                
                }

                # when we are ordering the data and also limiting
                if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText  WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter  ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt ";
                }

                # limiting queries but not ordering them
                if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                    $sql = "SELECT $select FROM $relArray[0] $innerJoinText  WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter   LIMIT $startAt, $endAt ";
                }

                # prepare the statement
                $stmt = Connection::connect()->prepare($sql);
                
            }else{
                return null;
            }

            try {
                # execute the statement
                $stmt->execute();

                # returns the answers without the number of indices
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                
                echo "getRelDataRange: Error: " . $e->getMessage();
            }
        }
    }
    
?>