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
    <title>QUEST PDO</title>
</head>
<body>

<form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div>
                <label for="firstname"></label><br>
                <input id="firstname" type="text" name="firstname" placeholder="First name"/>
            
            </div>
            <div>
                <label for="lastname"></label><br>
                <input id="lastname" type="text" name="lastname" placeholder="Last name" />
            </div>

            <div >
                <button type="submit">Add friend!</button>
            </div>
</form>


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
    } elseif(strlen($firstname) > 100){
      $errors[] = "The first name must be max 100 characters";
    } elseif(strlen($firstname) < 2){
      $errors[] = "The first name must be longer than 1 character.";
    } 

    if ((empty($lastname)) || ($lastname === '')) {
        $errors[] = "Last name is required";
        } elseif(strlen($lastname) > 100){
          $errors[] = "The last name must be max 100 characters";
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