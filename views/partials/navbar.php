<!-- Menu  Here we indicate which menu items will be seen when you are not logged in -->

<!-- <nav class="navbar bg-dark fixed-top"> -->
<nav class="navbar navbar-expand-lg bg-dark fixed-top">
  <div class="container-fluid">
    <!-- <a class="navbar-brand" href="index.php">Idea Trade</a> -->

    <!-- if the user is logged  this path will be displayed in the navigation -->
    <?php if (isset($_SESSION["user"])) : ?>
      <form class="d-flex " role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
        
      <button class="navbar-toggler bg-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="offcanvas offcanvas-end bg-success" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white" id="offcanvasNavbarLabel">Idea Trade</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="home.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contacts.php">Add Contact</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="my-contacts.php">My Contacts</a>
              </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  User
                </a>
                <ul class="dropdown-menu bg-dark">
                  <li><a class="dropdown-item" href="#">Profile</a></li>
                  <li><a class="dropdown-item" href="#">Settings</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>

                  <!-- Render the actul user name -->
                  <?php if (isset($_SESSION["user"])) :?>
                    <li class="dropdown-item user">
                      Hi,
                      <?= $_SESSION["user"]["name"]?> 
                 
                    </li>
                  <?php endif ?>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
              </li>

              <!-- If the user is not logged render this routes -->
             <?php else : ?>
                <div class="d-flex">
                  <a class="nav-link text-white m-3 text-decoration-underline" href="https://github.com/Merci4dev?tab=repositories">Visit the Code Priject </a>
                    <!-- 
                    <a class="nav-link text-white m-3" href="signup.php">Sign up </a> -->
                    
                </div>
            
              <?php endif ?>
              </ul>

            <!-- Render the actul user name when the user is logged -->
            <?php if (isset($_SESSION["user"])) :?>
              <div class="p-2">
                <p class="user"> 
                  Hi, 
                  <?= $_SESSION["user"]["name"]?>
                </p>
              </div>
            <?php endif ?>
        </div>
      </div>
  </div>
</nav>