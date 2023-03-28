<!-- Header component -->
<?php
    // require "./sql/db_class.php";
    
    // initialize the session
    // session_start();
    
    // if(!isset($_SESSION["user"])){
    //     header("Location: login.php");
    //     return;

    // }else{
    //     $objConn = new DBconn();

    //     // Retrieve all contacts from the database
    //     $contacts = $objConn->dataConsult("SELECT * FROM contacts" );
    // }
?>

<?php require "views/partials/header.php" ?>

<div class=" container-expande mt-5 p-5 row m-auto">
    <div class="col-md-6">
        <div
            class="h-100 p-5 text-white bg-primary border rounded-3">
            <h2>PHP Public Api v0.1</h2>
            <p>This is a PHP public API that uses a MySQL database als software system that allows external developers or users to access and interact with data stored in a MySQL database using HTTP requests. </p>

            <p> This API was built using PHP programming language, which is a server-side language that is commonly used for web development. The API exposes a set of endpoints, which are URLs that can be accessed by other applications to retrieve or manipulate data. </p>

            <p> The API endpoints can use standard HTTP methods, such as GET, POST, PUT, and DELETE, to perform various operations on the MySQL database, such as retrieving data, inserting new data, updating existing data, and deleting data.</p>
            <button class="btn btn-outline-primary" type="button">Example button</button>
        </div>
    </div>
    <div class="col-md-6">
        <div
            class="h-100 p-5 bg-primary border rounded-3">
            <h2>PHP API uses the MySQLi</h2>
            <p>To access the MySQL database, the PHP API uses the MySQLi extension, which is a PHP extension that provides an interface to MySQL databases. This allows the API to connect to the MySQL database, execute SQL queries, and retrieve or modify data stored in the database. </p>

            <p> To ensure security and prevent unauthorized access to the MySQL database, the API may use authentication mechanisms, such as API keys or OAuth tokens. Additionally, the API may implement rate limiting or other access control measures to prevent abuse or excessive usage.</p>

            <p> Overall, a PHP public API that uses a MySQL database provides a convenient and standardized way for developers or users to access and interact with data stored in a MySQL database, making it easier to build applications that rely on this data.</p>
            
            <button class="btn Swap the background-color utility and add a `.text-*` color utility to mix up the jumbotron look. Then,
                mix and match with additional component themes and more." type="button">Example button</button>
        </div>
    </div>
</div>



<?php require "views/partials/footer.php" ?>


