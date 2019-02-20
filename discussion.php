<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Discussio - Anonymous Online Discussion Forum</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar-container">
  <div class="website-name">
    <h2>Discussio.club</h2>
    <h6>Anonymous discussion forum</h6>
  </div>

  <div class="right-navbar">
    <ul>
      <li><a class="text-orange" href="index.php">Home</a></li>
      <li><a class="text-orange" href="new_discussion.php">New Topic</a></li>
      <li><a class="text-orange" href="search.php">Search</a></li>
    </ul>
  </div>
</div>

<?php
$servername = "localhost";
$username = "root";
$string = file_get_contents("env.json");
$env = json_decode($string);
$password = $env->db_pass;

try {
  $conn = new PDO("mysql:host=$servername;dbname=discussion_forum", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "SELECT * FROM comment WHERE discussion_id = {$_GET['id']} ORDER BY created ASC";
  $PDOStatement = $conn->query($sql, PDO::FETCH_ASSOC);
  $comments = $PDOStatement->fetchAll();

  if (isset($_POST['name'])) {
    $content = $_POST['name'];
    $id = $_GET['id'];
    $conn->exec("INSERT INTO comment (content, discussion_id) VALUES ('".$content."', '".$id."')");
    ?>
    <script>window.location = window.location.href;</script>
    <?php
  }

  $sql = "SELECT * FROM discussion WHERE id = {$_GET['id']}";
  $query = $conn->query($sql, PDO::FETCH_ASSOC);
  $discussion = $query->fetchAll()[0];

  $sql = "SELECT * FROM category WHERE id = {$discussion['category_id']}";
  $query = $conn->query($sql, PDO::FETCH_ASSOC);
  $category = $query->fetchAll()[0]['category_name'];
?>

<p class="headline m-2">"<?= $discussion['headline'] ?>"</p>
<span class="category m-2">Category: <b><?= $category ?></b></span>
<ul class="list-group comments">

<?php

  foreach ($comments as $comment) {
    ?>
    <li class="list-group-item comment">
      <img src="img/user.png" width="50">
      <br>
      <?= $comment['content'] ?>
    </li>
    <?php
  }
}
catch(PDOException $e) {

}
?>
</ul>

<form action="" method="POST">
<div class="form-group" class="mt-5">
  <input type="text" name="name" class="form-control" placeholder="Leave your own comment..." 
  autocomplete="off">
  <input type="submit" class="btn btn-dark">
</div>
</form>

</body>
</html>