<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/main.css">
  <title>Login - UniBiblio</title>
</head>
<body>

    <?php

        require_once("scripts/utils.php");

        require("scripts/index_redirector.php");

    ?>
    <header class="header-main">
    </header>
    <div class="container is-max-desktop">
    
        <?php if (isset($_SESSION["feedback"])): ?>
              <div>
                <strong><?= $_SESSION["feedback"] ?><?php unset($_SESSION["feedback"]) ?></strong>
              </div>
        <?php endif; ?>

        <form class="box p-6" action="scripts/login.php" method="post">
    
          <span>
            <h1>UniBiblio Login</h1>
          </span>
    
          <label>Email</label>
          <div>
              <input class="input" type="text" name="email" placeholder="nome.cognome">
                <select class="input" name="type">
                  <option>@lettore.it</option>
                  <option>@bibliotecario.it</option>
                </select>
          </div>
    
          <label>Password</label>
          <div>
            <p>
              <input class="input" type="password" name="password" placeholder="Password">
            </p>
          </div>
          
          <div>
            <p>
              <button>
                Login
              </button>
            </p>
          </div>
    
        </form>
        
      </div>
</body>
</html>