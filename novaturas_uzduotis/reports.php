
<!DOCTYPE html>
<html>
    <head>
        <title>Oro uostai</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="./includes/style.css">
    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Oro uostai</a></li>
                    <li><a href="airlines.php">Avialinijos</a></li>
                    <li class="dropdown" class="active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ataskaitos<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            <li><a href="reports.php?action=1">Salys, neturincios avialiniju</a></li>
                            <li><a href="reports.php?action=2">Salys, neturincios avialiniju ir oro uostu</a></li>
                            </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <?php 
require_once('./includes/dbconnect.php');
include('./includes/functions.php');
$action = "";
$action = filter_var(test_input($_GET["action"]), FILTER_SANITIZE_NUMBER_INT);
if (filter_var($action, FILTER_VALIDATE_INT))
{
    switch($action)
    {
        case 1:
            $avialiniju_salys = array();
            $atrinkti = array();
            $sql = "SELECT salis_id FROM avialinija";
            if ($result=$mysqli->query($sql))
            {
                if ($result->num_rows >0)
                {
                    while ($row=$result->fetch_assoc())
                    {
                        array_push($avialiniju_salys, $row['salis_id']);
                    }
                }
                $result->free();
            }
            $sql = "SELECT id FROM salis ORDER BY pavadinimas";
            if ($result=$mysqli->query($sql))
            {
                if ($result->num_rows >0)
                {
                    while ($row = $result->fetch_assoc())
                    {
                        $poz = true;
                        $salis_id = $row['id'];
                        foreach ($avialiniju_salys as $id)
                        {
                            if ($salis_id == $id) $poz = false;
                        }
                        if ($poz) array_push($atrinkti, $salis_id);
                    }
                    
                }
                $result->free();
            }
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>ISO</th>";
            echo "<th>Pavadinimas</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($atrinkti as $salis)
            {
                $salis = filter_var(test_input($salis), FILTER_SANITIZE_NUMBER_INT);
                if (filter_var($salis, FILTER_VALIDATE_INT))
                {
                    $sql = "SELECT * FROM salis WHERE id = ?";
                    if ($stmt=$mysqli->prepare($sql))
                    {
                        $stmt->bind_param("i", $salis);
                        if ($stmt -> execute())
                        {
                            $result = $stmt->get_result();
                            if ($result->num_rows >0)
                                {
                                        
                                    while ($row = $result->fetch_array()){
                                        echo "<tr>";
                                        echo "<td>".$row[0]."</td>";
                                        echo "<td>".$row[1]."</td>";
                                        echo "<td>".$row[2]."</td>";
                                        echo "</tr>";
                                    }
                                }
                        }
                        else error();
                    }
                    else error();
                    $result->free();
                }
                else error();
            }
            echo "</tbody>";
            echo "</table";
            break;
        case 2:
            $avialiniju_salys = array();
            $orouostu_salys = array();
            $atrinkti = array();
            $galutiniai = array();
            $sql = "SELECT salis_id FROM avialinija";
            if ($result=$mysqli->query($sql))
            {
                if ($result->num_rows >0)
                {
                    while ($row=$result->fetch_assoc())
                    {
                        array_push($avialiniju_salys, $row['salis_id']);
                    }
                }
                $result->free();
            }
            $sql = "SELECT id FROM salis ORDER BY pavadinimas";
            if ($result=$mysqli->query($sql))
            {
                if ($result->num_rows >0)
                {
                    while ($row = $result->fetch_assoc())
                    {
                        $poz = true;
                        $salis_id = $row['id'];
                        foreach ($avialiniju_salys as $id)
                        {
                            if ($salis_id == $id) $poz = false;
                        }
                        if ($poz) array_push($atrinkti, $salis_id);
                    }
                    
                }
                $result->free();
            }
            $sql = "SELECT salis FROM orouostas";
            if ($result=$mysqli->query($sql))
            {
                if ($result->num_rows >0)
                {
                    while ($row=$result->fetch_assoc())
                    {
                        array_push($orouostu_salys, $row['salis']);
                    }
                }
                $result->free();
            }
            foreach ($atrinkti as $atrinkti_id)
            {
                $poz = true;
                foreach($orouostu_salys as $oro_uosto_salis)
                {
                    if ($atrinkti_id == $oro_uosto_salis) $poz = false;
                }
                if ($poz) array_push($galutiniai, $atrinkti_id);
            }
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>ISO</th>";
            echo "<th>Pavadinimas</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($galutiniai as $salis)
            {
                $salis = filter_var(test_input($salis), FILTER_SANITIZE_NUMBER_INT);
                if (filter_var($salis, FILTER_VALIDATE_INT))
                {
                    $sql = "SELECT * FROM salis WHERE id = ?";
                    if ($stmt=$mysqli->prepare($sql))
                    {
                        $stmt->bind_param("i", $salis);
                        if ($stmt -> execute())
                        {
                            $result = $stmt->get_result();
                            if ($result->num_rows >0)
                                {
                                        
                                    while ($row = $result->fetch_array()){
                                        echo "<tr>";
                                        echo "<td>".$row[0]."</td>";
                                        echo "<td>".$row[1]."</td>";
                                        echo "<td>".$row[2]."</td>";
                                        echo "</tr>";
                                    }
                                }
                        }
                        else error();
                    }
                    else error();
                    $result->free();
                }
                else error();
            }
            echo "</tbody>";
            echo "</table";
            
            break;
        default:
            error();
    }
}
else error();
?>
    </body>
</html>