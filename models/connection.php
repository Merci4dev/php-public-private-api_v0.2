<?php

    # Setting the  enviroment variable
    require __DIR__.'/../vendor/autoload.php';
    use Dotenv\Dotenv;
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    require_once "get.model.php";
  
    # Handle the database connection
    #============================================
    class Connection {

        private static $counter = 0;

        # Database Info
        # ==========================================
        static public function infoDatabase(){
            $infoDB = array (
                "host" => $_ENV['DB_HOST'],
                "database" => $_ENV['DB_NAME'],
                "user" => $_ENV['DB_USER'],
                "password" => $_ENV['DB_PASS'],
            );

            return $infoDB;
        }

        # Database connection 
        #============================================
        static public function connect(){
            self::$counter++;

            try {
                $link = new PDO(
                    "mysql:host=localhost;dbname=" . Connection::infoDatabase()["database"],
                    Connection::infoDatabase()["user"],
                    Connection::infoDatabase()["password"]
                );
                $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

                $link->exec("set names utf8");
                
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return null;
            }
            
            # Set the database connection counter
            Connection::getRequestCount();

            return $link;
        }

        # This method returns the total number of times a connection to the database has been established.
        #============================================
        static public function getRequestCount() {
            
            echo '<pre>'; print_r("DB Conn Counter => : ".self::$counter); echo '</pre>';
            return self::$counter;
        }
        
        # Metho  to close the database connection
        #============================================
        static public function close($connection){

            print_r("<br /> <br />"."DB Closed");
            $connection = null;
        }

        # Validation of the existence of a table in the database. This method returns all the columns that belong to a table
        #============================================
        static public function getColumnsData($table, $columns){

            # Trae la DB
            $database = Connection::infoDatabase()["database"];

            # Retorna una connecion a la DB y una seleccion de todas la columnas que formen parte de la tabla que se les pase como parametro de la base de datos al las que nos estamos conectando
            $validate = Connection::connect()->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")->fetchAll(PDO::FETCH_OBJ);

            # Si $validate viene vacio es porque la tabla no existe
            if(empty($validate)){
                return null;

            }else {

                # Solicitud de columns globales(para el * en indice 0)
                if($columns[0] == "*"){
                    # Elimina el aterizco
                    array_shift($columns);
                }

                # Pero si no vienen vacio, validara los nombres O existencias de las comlumnas
                $sum = 0;
                
                foreach ($validate as $key => $value) {
                    # Incrementando los numero de indeices que trae columna
                    $sum += in_array($value->item, $columns);
                }

                return $sum == count($columns) ? $validate : null;
                
            }
        }

        # Generate  the token autentication
        #============================================
        static public function jwt($id, $email){

            # Data that we will pass to the token to create it
            $time = time();
            
            $token = array(
                "iat" => $time,                 # token time
                "exp" => $time + (60*60*24),    # date token (one day)
                "data" => [                     # date expiration  token (one day)
                "id" => $id,
                "email" => $email
                ]  
            );
            
            return $token;
        }
    
        # Methods to validate the authentication token
        #============================================
        static public function tokenValidate($token, $table, $suffix){
            // echo '<pre>'; print_r($token); echo '</pre>';
            // return;

            # We bring the user to which the authentication token belongs
            $user = GetModel::getDataFilter($table, "token_exp_".$suffix, "token_".$suffix, $token, null, null, null, null);
           
            if(!empty($user)){
                
                # If the information exists, validate that the token has not expired
                $time = time();

                if($user[0]["token_exp_".$suffix] > $time){
                    return "ok";

                }else{
                    # If the token does not match the token of user
                    return "expired";
                }

            }else{
                return "not-auth";
            }
        }

        # API KEY. Implementate the api key to private the especific endpoints
        #============================================
        static public function private_api_key(){
         
            return  $_ENV['API_KEY'];

        }
        
        # Set the table and endpoint which could be accessed without Api Key permission
        #============================================
        static public function publicAccess(){
            
            # Setting the public table access
            $tables = ["courses", "users"];

            return $tables;
        }
    }
    
?>
