


<?php
$_id = "";
require_once('./includes/dbconnect.php');
include('./includes/functions.php');

if(isset($_GET["id"]) && !empty($_GET["id"])){
    $_id = filter_var(test_input($_GET["id"]),FILTER_SANITIZE_NUMBER_INT);
    if(filter_var($_id,FILTER_VALIDATE_INT) && isset($_id)){
        $sql = "DELETE FROM orouostas WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("i",$_id);
            if($stmt->execute()){
                header("location:index.php");
                exit();
            }
            else{
                echo "Nepavyko, bandykite dar karta. (Jei oro uostas turi susietu avialiniju, pirma pasalinkite jas) <br> <a href='index.php'>Grizti</a>";
            }
        }
        $stmt->close();
        $mysqli->close();
    }
    else error();
}
?>
