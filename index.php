<?php
require_once 'connec.php';

$pdo = new \PDO(DSN, USER, PASS);
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>QUEST PDO</title>
</head>
<body>
<?php include 'include/header.html' ?>

<form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="container-fluid">
            <div class="form-group">
                <label for="firstname"></label><br>
                <input id="firstname" type="text" name="firstname" placeholder="First name" required/>
            
            </div>
            <div class="form-group">
                <label for="lastname"></label><br>
                <input id="lastname" type="text" name="lastname" placeholder="Last name" required />
            </div>

            <div>
                <button type="submit">Add friend!</button>
            </div>
        </div>
</form>
<?php 
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    echo '<br><div class="alert alert-success" role="alert">Bravo! You made a new friend, <a href="/index.php" class="alert-link">click</a> to refresh and see them.</div><br>';
 }
 ?>

    <?php foreach($friends as $friend) : ?>
            <ul>
                <li><h2><?= $friend["firstname"] . " " . $friend["lastname"] ?></h2></li>
            </ul>
    <?php endforeach ?>
    
</body>
</html>

<?php


//var_dump($friends);

// get the data from a form 


if ($_SERVER["REQUEST_METHOD"] === 'POST') {

$firstname = clean_input($_POST['firstname']); 
$lastname = clean_input($_POST['lastname']);

if ((empty($firstname)) || ($firstname === '')) {
    $errors[] = "First name is required";
    } elseif(strlen($firstname) > 45){
      $errors[] = "The first name must be max 45 characters";
    } elseif(strlen($firstname) < 2){
      $errors[] = "The first name must be longer than 1 character.";
    } 

    if ((empty($lastname)) || ($lastname === '')) {
        $errors[] = "Last name is required";
        } elseif(strlen($lastname) > 45){
          $errors[] = "The last name must be max 45 characters";
        } elseif(strlen($lastname) < 2){
          $errors[] = "The last name must be longer than 1 character.";
        }

        if (empty($errors)){
            $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
            $statement = $pdo->prepare($query);
            
            $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
            $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
            
            $statement->execute();
        } else {
            echo "Something went terribly wrong....";
            var_dump($errors);
        }


}

function clean_input($data) {
    $data = trim($data);
    $data = htmlentities($data);
    return $data;
  }
?>