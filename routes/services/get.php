<?php

    require_once "controllers/get.controller.php";
    require_once "models/get.model.php";
    require_once "models/connection.php";
    
    # ESTA ES LA VISTA
    # Set the select variables. if there is not a select variable defined at the url, select will habe the default value. * :
    $select = $_GET["select"] ??  "*";
    $orderBy = $_GET["orderBy"] ??  null; 
    $orderMode = $_GET["orderMode"] ??  null; 

    # Limit the number of rows to be returned at the request time
    $startAt = $_GET["startAt"] ?? null;
    $endAt = $_GET["endAt"] ?? null;

    # When these values are not sent in the request when selecting ranges they are equal to null
    $filterTo = $_GET["filterTo"] ?? null;
    $inTo = $_GET["inTo"] ?? null;
    
    $response = new GetController();


    # Get request with filter
    # ===================================================
    if(isset($_GET["linkTo"]) && isset($_GET["equalTo"]) && !isset($_GET["rel"]) && !isset($_GET["type"])){

        # controller for request with filters. To filter the results
        $response->getDataFilter($table, $select, $_GET["linkTo"],$_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);
    }  
    # Unfiltered GET requests between related tables
    # ===================================================
    elseif(isset($_GET["rel"]) && isset($_GET["type"]) &&  $table == "relations" && !isset($_GET["linkTo"]) && !isset($_GET["equalTo"]) ){

        $response->getRelData($_GET["rel"], $_GET["type"], $select, $orderBy, $orderMode, $startAt, $endAt);
    }
    # Peticiones GET con filtro entre tabal ralacionadas
    # ===================================================
    elseif(isset($_GET["rel"]) && isset($_GET["type"]) &&  $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"]) ){
        
        $response->getRelDataFilter($_GET["rel"], $_GET["type"], $select, $_GET["linkTo"],$_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);
    }
    # Get request for the search engine without realacions
    # ===================================================
    elseif(!isset($_GET["rel"]) && !isset($_GET["type"]) && isset($_GET["linkTo"]) && isset($_GET["search"])){
        $response->getDataSearch($table, $select, $_GET["linkTo"],$_GET["search"], $orderBy, $orderMode, $startAt, $endAt);
    }
    # Get request for the browser with realacions
    # ===================================================
    elseif(isset($_GET["rel"]) && isset($_GET["type"]) &&  $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["search"])){
        $response->getRelDataSearch($_GET["rel"], $_GET["type"], $select, $_GET["linkTo"],$_GET["search"], $orderBy, $orderMode, $startAt, $endAt);
    }
    # Get requests to select ranges
    # ===================================================
    elseif(!isset($_GET["rel"]) && !isset($_GET["type"]) && isset($_GET["linkTo"]) && isset($_GET["between1"]) && isset($_GET["between2"])){
        $response->getDataRange($table, $select, $_GET["linkTo"], $_GET["between1"], $_GET["between2"],  $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);
    }
    # Get requests to select ranges with relationships
    # ===================================================
    elseif(isset($_GET["rel"]) && isset($_GET["type"]) &&  $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["between1"]) && isset($_GET["between2"])){
        $response->getRelDataRange($_GET["rel"], $_GET["type"], $select, $_GET["linkTo"], $_GET["between1"], $_GET["between2"],  $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);
    }
    else{
        # Get request without filter
        $response -> getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);
    }
    
?>