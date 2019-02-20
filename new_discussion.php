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

  $sql = "SELECT * FROM category";
  $PDOStatement = $conn->query($sql, PDO::FETCH_ASSOC);
  $categories = $PDOStatement->fetchAll();
  
} catch (PDOException $e) {
  
}
?>

<h3 class="text-center mt-3">New Discussion</h3>

<form action="new_discussion" method="POST">
<div class="form-group mt-5 new-discussion p-3">
  <select name="category" class="mt-3 form-control">
    <?php
    foreach ($categories as $category) {
      echo '<option value="' . $category['id'] . '">' . $category['category_name'] . '</option>';
    }
    ?>
  </select>
  <br>
  <label for="headline" class="mt-3"><b>Text</b></label>
  <br>
  <textarea name="headline" class="form-control" rows="10" cols="100"></textarea>
  <br>
  <input type="submit" value="Post" class="btn btn-dark">
</div>
</form>

<?php

if (isset($_POST['category'])) {
  $query = $conn->prepare('INSERT INTO discussion (headline, category_id) VALUES (:headline, :category)');

  $query->bindParam(':headline', $_POST['headline']);
  $query->bindParam(':category', $_POST['category']);

  $query->execute();
}

?>

</body>
</html>
