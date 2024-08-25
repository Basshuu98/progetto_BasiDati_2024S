<!-- component to be included by other files -->

<nav class="c-navbar navbar is-spaced">
  <div class="navbar-brand">
    <a class="navbar-item" href="home.php">
      <h1 class="title">UniBiblio</h1>
    </a>
  </div>

  <div class="navbar-menu">
    <div class="navbar-start ml-4">
      <a class="navbar-item" href="home.php">Home</a>
    </div>
    <div class="navbar-start ml-4">
      <p class="navbar-item">
        Autenticato come <?= $_SESSION["usertype"]  ?>:
        <strong>&nbsp;<?= $_SESSION["username"] ?></strong>
      </p>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <a class="button is-link" href="../scripts/logout.php">
            <strong>Logout</strong>
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>