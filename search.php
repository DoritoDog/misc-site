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
$password = $env['db_pass'];

try {
  $conn = new PDO("mysql:host=$servername;dbname=discussion_forum", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (isset($_GET['q'])) {
    $search_crit = $_GET['q'];
    $sql = "SELECT * FROM discussion WHERE headline LIKE '%$search_crit%' ORDER BY modified ASC;";
    $PDOStatement = $conn->query($sql, PDO::FETCH_ASSOC);
    $rows = $PDOStatement->fetchAll();
  ?>

  <form action="" method="get" class="form-group">
    <input type="text" name="q" class="form-control search-field" placeholder="Search topics...">
  </form>

  <table class="table mx-auto topics-table">
    <thead>
      <tr class="light-gray">
        <th>Topics</th>
        <th>Replies</th>
        <th>Freshness</th>
      </tr>
    </thead>
    <tbody>
    <?php
      foreach ($rows as $row) {
        $id = $row['id'];
        $comments_sql = "SELECT * FROM `comment` WHERE `discussion_id` = $id;";
        $comments_query = $conn->query($comments_sql, PDO::FETCH_ASSOC);
        $comments = $comments_query->fetchAll();
        $headline = $row['headline'];
        $trimmed_headline = (strlen($headline) > 100) ? substr($headline,0,100).'...' : $headline;
    ?>
        <tr>
          <td width="400px">
            <div class="topic-link">
              <img src="img/user.png" width="50">
              <div class="topic-text">
                <a href="<?= 'discussion.php?id='.$row['id'] ?>" class="discussion-link">
                  <?= $trimmed_headline ?>
                </a>
                <br>
                <!-- <div class="posted-by">
                  <span class="black">Posted by</span>
                  <span class="text-orange">Anonymous User</span>
                </div> -->
              </div>
            </div>
          </td>
          <td><?= count($comments) ?></td>
          <td>
            <?php
            $datetime1 = date_create($row['created']);
            $datetime2 = date_create();
            $interval = date_diff($datetime1, $datetime2);
            echo $interval->format('%a Days');
            ?>
          </td>
        </tr>
    <?php
      }
    ?>
    </tbody>
</table>

<?php
  }
} catch (PDOException $e) {

}
?>

</body>
</html>
