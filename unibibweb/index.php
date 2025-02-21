<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.3/css/bulma.min.css">
  <link rel="stylesheet" href="../styles/index.css">
  <script src="https://kit.fontawesome.com/eb793f993c.js" crossorigin="anonymous"></script>
  <title>Login - UniBib</title>
</head>
<body class="has-background-dark has-text-light hero is-fullheight">

    <?php

        require_once("scripts/utils.php");

        require("scripts/index_redirector.php");

    ?>
    <div class="container is-max-desktop box">
    
        <?php if (isset($_SESSION["feedback"])): ?>
              <div class="notification is-danger is-light mt-6">
                <strong><?= $_SESSION["feedback"] ?><?php unset($_SESSION["feedback"]) ?></strong>
              </div>
        <?php endif; ?>

        <form class="box p-6" action="scripts/login.php" method="post">
    
          <span class="icon-text">
            <span class="icon is-large">
              <i class="fa-solid fa-user fa-2xl"></i>
            </span>
              <h1 class="title mt-2"> Benvenuti su UniBib</h1>
          </span>
    
          <label class="label mt-5">Email</label>
          <div class="field has-addons has-addons-right">
            <p class="control has-icons-left is-expanded">
              <input class="input" type="text" name="email" placeholder="nome.cognome">
              <span class="icon is-small is-left">
                <i class="fa-solid fa-envelope"></i>
              </span>
            </p>
            <p class="control is-expanded">
              <span class="select is-fullwidth">
                <select class="input" name="type">
                  <option>@lettore.it</option>
                  <option>@bibliotecario.it</option>
                </select>
              </span>
            </p>
          </div>
    
          <label class="label mt-5">Password</label>
          <div class="field">
            <p class="control has-icons-left">
              <input class="input" type="password" name="password" placeholder="Password">
              <span class="icon is-small is-left">
                <i class="fa-solid fa-lock"></i>
              </span>
            </p>
          </div>
          
          <div class="field mt-5">
            <p class="control">
              <button class="button is-link is-fullwidth is-medium">
                Login
              </button>
            </p>
          </div>
    
        </form>
        
      </div>

      <?php require("components/footer.php"); ?>

</body>
</html>