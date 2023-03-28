<?php

    declare(strict_types=1);
    require "vendor/autoload.php";
    use Firebase\JWT\JWT;
    use Firebase\JWT\JWT\key;

    require_once "models/get.model.php";
    require_once "models/post.model.php";
    require_once "models/connection.php";
    require "models/put.model.php";
    
    class PostController {
        # Post request to create all kind of data 
        # ==============================================
        static public function postData($table, $data){
            $response = PostModel::postData($table, $data);
            // echo '<pre>'; print_r( $response ); echo '</pre>';
            // return;

            $return = new PostController();
            $return->fncResponse($response, null, null);
          $return = Connection::close($return);
        }

        # Post request to signup the user
        # [x] Validar aqui lo que viene del cliente 
        // $validar = $_POST['tittle_course'] ;
        # ==============================================
        static public function postSignup($table, $data, $suffix){
        
            # Permite el registro del usuario solo cuando viene un password en el formulario
            # Aqui hacemos las validaciones para la contraseña
            if(isset($data["password_".$suffix]) && $data["password_".$suffix] != null && isset($data["confirm_password_".$suffix]) && $data["confirm_password_".$suffix] != null  ){
                
                # If everything with the passwords is fine, we proceed to encrypt the passwords
                $crypt = crypt($data["password_".$suffix], '$2a$07$usesomesillystringforsmxz$');
                $crypt2 = crypt($data["confirm_password_".$suffix], '$2a$07$usesomesillystringforsmxz$');
                
                # Once the passwd is encrypted, we save the passwd in data again
                $data["password_".$suffix] = $crypt;
                $data["confirm_password_".$suffix] = $crypt2;
                
                $response = PostModel::postData($table, $data);
                $return = new PostController();
                $return->fncResponse($response, null, $suffix);
                $return = Connection::close($return);
                
            }else{
                # For user registration from external apps
                # Upload the info to the db and bring the table and the data
                #======================================
                $response = PostModel::postData($table, $data);
                
                # If it comes with a property called Comment and its response, then we generate the token and update the DB
                if(isset($response["Comment"]) && $response["Comment"] == "The proccess was Successfully"){

                    # Validate that the user you want to log in exists in the DB
                    # ==============================================
                    $response =  GetModel::getDataFilter($table, "*", "email_".$suffix, $data["email_".$suffix], null, null, null, null);
                    // echo '<pre>'; print_r($response ); echo '</pre>';
                    // return;

                    if(!empty($response)){

                        # If everything goes well, we create the security token
                        $token = Connection::jwt($response[0]["id_".$suffix], $response[0]["email_".$suffix]);
                
                        $key = "myPrivateKeybxe_234w";
                        $jwt = JWT::encode($token, $key, 'HS256');
    
                        # Update the database with the user's security token
                        #======================================
                        $data = array(
                            # Database properties to be updated
                            "token_".$suffix => $jwt,
                            "token_exp_".$suffix => $token["exp"]
                        );
                        
                        $update = PutModel::putData($table, $data, $response[0]["id_".$suffix], "id_".$suffix);
    
                        if(isset($update["Comment"]) && $update["Comment"] == "The proccess was Successfully"){
                            $response[0]["token_".$suffix] = $jwt;
                            $response[0]["token_exp_".$suffix] = $token["exp"];
    
                            $return = new PostController();
                            $return->fncResponse($response, null, $suffix);
                          $return = Connection::close($return);
                        } 
                    }
                }

            }
            
        }


        # Post request to sign in the user
        # ==============================================
        static public function postSignin($table, $data, $suffix){
          
            # Validate that the user you want to log in exists
            # ==============================================
            $response =  GetModel::getDataFilter($table, "*", "email_".$suffix, $data["email_".$suffix], null, null, null, null);
         
            if(!empty($response)){

                # If the user registers through an external api, the password value in the db is null. If it is not null, register from the form
                # when the user is logging in from an external api
                # We ask if the password exists
                # ==============================================
                if($response[0]["password_".$suffix] != null){

                    # If is not empty, encrypt the password that the user gives us
                    $crypt = crypt($data["password_".$suffix], '$2a$07$usesomesillystringforsmxz$');
                    $crypt2 = crypt($data["confirm_password_".$suffix], '$2a$07$usesomesillystringforsmxz$');
                
                    # Comparing the encrypted passwd in the db with the one we are passing to it
                        // if(strcmp($response[0]["password_".$suffix], $crypt) == 0 && strcmp($response[0]["confirm_password_".$suffix], $crypt2) == 0) {}
                        
                        if($response[0]["password_".$suffix]  && $response[0]["confirm_password_".$suffix]  ){
                         
                        # If everything goes well, we create the security token
                        $token = Connection::jwt($response[0]["id_".$suffix], $response[0]["email_".$suffix]);
                       
                        
                        # Encoding or creating the token
                        $key = "myPrivateKeybxe_234w";
                        $jwt = JWT::encode($token, $key, 'HS256');
                        // echo '<pre>'; print_r($jwt); echo '</pre>';
                        // return;

                        # Update the database with the user's security token
                        # ==============================================
                        $data = array(
                            # Propiedades de la base de datos que se actualizaran
                            "token_".$suffix => $jwt,
                            "token_exp_".$suffix => $token["exp"]
                        );
                       
                        # Insert the token in the db to the corresponding user
                        $update = PutModel::putData($table, $data, $response[0]["id_".$suffix], "id_".$suffix);

                        # Validating the update variable
                        if(isset($update["Comment"]) && $update["Comment"] == "The proccess was Successfully"){

                            # Returning the information that has just been updated to be able to add the updated fields that we uploaded to the DB
                            $response[0]["token_".$suffix] = $jwt;
                            $response[0]["token_exp_".$suffix] = $token["exp"];

                            $return = new PostController();
                            $return->fncResponse($response, null, $suffix);
                          $return = Connection::close($return);
                        } 

                    }else{
                     
                        # When the password is not correct
                        $response = null;
                        $return = new PostController();
                        $return->fncResponse($response, "Wrong Password",$suffix,);
                      $return = Connection::close($return);
                    }
                    // echo '<pre>'; print_r($response); echo '</pre>';
                    // return ;
                }else{

                    # But if the value of the passwd in the DB is null, we update the user token again for users who are logged in from external applications
                    # ==============================================
                    $token = Connection::jwt($response[0]["id_".$suffix], $response[0]["email_".$suffix]);
                
                    $key = "myPrivateKeybxe_234w";
                    $jwt = JWT::encode($token, $key, 'HS256');

                    $data = array(
                        "token_".$suffix => $jwt,
                        "token_exp_".$suffix => $token["exp"]
                    );
                    
                    $update = PutModel::putData($table, $data, $response[0]["id_".$suffix], "id_".$suffix);

                    # Returns the information that has just been updated to be able to add the updated fields that we uploaded to the DB
                    if(isset($update["Comment"]) && $update["Comment"] == "The proccess was Successfully"){
                        $response[0]["token_".$suffix] = $jwt;
                        $response[0]["token_exp_".$suffix] = $token["exp"];

                        $return = new PostController();
                        $return->fncResponse($response, null, $suffix);
                      $return = Connection::close($return);
                    } 
                } 

            }else{
                # When the user does not exist correctly in the db
                $response = null;
                $return = new PostController();
                $return->fncResponse($response, "Wrong Email", $suffix,);
              $return = Connection::close($return);
            }
        }


        # controller response
        # ==============================================
        static public function fncResponse($response, $error, $suffix){
            
            if(!empty($response)){
                # Remove passwords from the returned response
                #======================================
                if(isset($response[0]["password_".$suffix]) || isset($response[0]["confirm_password_".$suffix]))
                unset($response[0]["password_".$suffix]);
                unset($response[0]["confirm_password_".$suffix]);

                $json = array(
                    'status' => 200,
                    'result' => $response
                );
            }
            else{

                $json = array(
                    'status' => 404,
                    'result' => 'Not Found',
                    'method' => 'Post'
                );
                // }
            }
            echo json_encode($json, http_response_code($json["status"]));
        }
    }


?>