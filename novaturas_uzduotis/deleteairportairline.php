
<?php
require_once('./includes/dbconnect.php');
include('./includes/functions.php');
$_id = $_page = "";

if(isset($_GET['id']) && isset($_GET['page']))
{
    $_id = filter_var(test_input($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
    $_page = filter_var(test_input($_GET['page']), FILTER_SANITIZE_NUMBER_INT);
    
    if (filter_var($_id,FILTER_VALIDATE_INT) && filter_var($_page,FILTER_VALIDATE_INT))
    {
        $sql = "DELETE FROM susieti WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("i", $_id);
            if($stmt->execute()){
            $url = "airportairlines.php?id={$_page}";
            header("location: ".$url);
            exit();
        } else{
            echo "Nepavyko. Bandykite dar karta!";
        }
    }
    $stmt->close();
    $mysqli->close();
    }
    else error();
} else{
    if(empty(test_input($_GET["id"]))) error();
}
?>


