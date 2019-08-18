<?php
require_once('./includes/dbconnect.php');
include('./includes/functions.php');
$esami = array();
$_id = $nauja_avialinija = "";
$sql = "SELECT * FROM orouostas WHERE id = ?";

if ($stmt = $mysqli ->prepare($sql))
{
    $_id = filter_var(test_input($_GET["id"]), FILTER_SANITIZE_NUMBER_INT);
    if (filter_var($_id, FILTER_VALIDATE_INT))
    {       
        $stmt->bind_param("i", $_id);
        if($stmt->execute())
        {
            
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if (empty($row['pavadinimas'])) error();
            $oro_uostas = $row['pavadinimas'];
        }
    $stmt->close();
    $result->free();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $sql = "INSERT INTO susieti (oro_uostas_id, avialinija_id) VALUES (?, ?)";
        if ($stmt = $mysqli->prepare($sql))
        {
            $nauja_avialinija = filter_var(test_input($_POST['avialinija']), FILTER_SANITIZE_NUMBER_INT);
            $_id = filter_var(test_input($_POST['id']), FILTER_SANITIZE_NUMBER_INT);
            if (filter_var($_id, FILTER_VALIDATE_INT) && filter_var($nauja_avialinija, FILTER_VALIDATE_INT) && isset($_id) && isset($nauja_avialinija))
            {
                $stmt->bind_param("ss", $_id, $nauja_avialinija);
                if ($stmt->execute())
                {
                    $stmt->close();
                    header("location:airportairlines.php?id=".$_id."");
                }
            }
            elseif (is_numeric($_id)) {
                header("location:airportairlines.php?id=".$_id."");
            }
            else error();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <title>Oro uosto avialinijos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./includes/style.css">
    <script type="text/javascript">
    function istrinti()
    {
    return window.confirm("Ar tikrai norite istrinti irasa?");
    }
   </script>
</head>
<body>
    
    <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li  class="active"><a href="index.php">Oro uostai</a></li>
                    <li><a href="airlines.php">Avialinijos</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ataskaitos<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            <li><a href="reports.php?action=1">Salys, neturincios avialiniju</a></li>
                            <li><a href="reports.php?action=2">Salys, neturincios avialiniju ir oro uostu</a></li>
                            </ul>
                    </li>
                </ul>
            </div>
        </nav>
    
    <h2><?php if (empty($oro_uostas) && empty($_id)) error(); else echo $oro_uostas;?></h2>
<?php
    $sql = "SELECT susieti.id, orouostas.pavadinimas, avialinija.pavadinimas, avialinija.id, orouostas.id FROM orouostas INNER JOIN (avialinija INNER JOIN susieti ON avialinija.id = susieti.avialinija_id) ON orouostas.id = susieti.oro_uostas_id WHERE susieti.oro_uostas_id = ?";
    if($stmt = $mysqli->prepare($sql)){
        $_id = filter_var(test_input($_GET["id"]), FILTER_SANITIZE_NUMBER_INT);
        if (filter_var($_id,FILTER_VALIDATE_INT)){
        $stmt->bind_param("i", $_id);
        if($stmt->execute()){
            $result= $stmt->get_result();
            if($result->num_rows >= 1){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = $result->fetch_array()){
                                    array_push($esami, $row['3']);
                                    echo "<tr>";
                                        echo "<td>" . $row['2'] . "</td>";
                                        echo "<td>";
                                        echo "<a href='deleteairportairline.php?id=". $row['0'] ."&page=".$row['4']."' title='Istrinti' data-toggle='tooltip' onclick='return istrinti();'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            $result->free();
                        }
            else
        {
            echo "Susietu avialiniju nera!";
        }
        }
        else error();
        }
    }
    $stmt->close();
    ?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo filter_var(test_input($_GET["id"]),FILTER_SANITIZE_NUMBER_INT); ?>"/>
            <select name="avialinija">
                <?php
                    $avialinijos = $mysqli->query("SELECT * FROM avialinija ORDER BY pavadinimas");
                    while ($row = $avialinijos->fetch_assoc())
                    {
                        $poz = true;
                        foreach ($esami as $var)
                        {
                            if ($row['id'] == $var)
                            {
                                $poz = false;
                            }
                        }
                        if ($poz)
                        {
                            $avialinija = $row['pavadinimas'];
                            $avialinija_id = $row['id'];
                            echo "<option value ='$avialinija_id'>$avialinija</option>";
                        }
                    }
                ?>
            </select>
            <input type="submit" name="submit" class="btn btn btn-light" value="Prideti">
        </form>
    <br></br>
</body>
</html>