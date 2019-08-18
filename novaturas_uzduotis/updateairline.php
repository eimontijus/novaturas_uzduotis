<?php
require_once('./includes/dbconnect.php');
include('./includes/functions.php');
$senas_pavadinimas = $sena_salis_pavadinimas = $sena_salis_id = $sena_salis_iso = $pavadinimas_err = $id = $ivestas_pavadinimas = $naujas_pavadinimas = $nauja_salis_id = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"])))
{
    $id = filter_var(test_input(trim($_GET["id"])), FILTER_SANITIZE_NUMBER_INT);
    if (filter_var($id, FILTER_VALIDATE_INT))
    {
        $sql = "SELECT avialinija.pavadinimas, salis.pavadinimas, salis.id, salis.iso FROM salis INNER JOIN avialinija ON salis.id = avialinija.salis_id WHERE avialinija.id = ? ";
        if ($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("i",$id);
                if ($stmt -> execute())
                {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1)
                    {
                        while ($row = $result->fetch_array())
                        {
                            $senas_pavadinimas = $row[0];
                            $sena_salis_pavadinimas = $row[1];
                            $sena_salis_id = $row[2];
                            $sena_salis_iso = $row[3];
                        }
                        $result->free();
                    }
                    else error();
                }
            else error();
        }
        $stmt->close();
    }
    else error();
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    //-------------------------------------------------------------------------------------------------------------------------------
    $ivestas_pavadinimas = test_input($_POST["pavadinimas"]);
    if(empty($ivestas_pavadinimas))
    {
        $pavadinimas_err = "Iveskite pavadinima";
    }
    elseif(!filter_var($ivestas_pavadinimas, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $pavadinimas_err = "Iveskite tinkama pavadinima (gali sudaryti tik raides)";
    }
    elseif(strlen($ivestas_pavadinimas) > 64)
    {
        $pavadinimas_err = "Pavadinimas per ilgas. Maksimalus ilgis 64 simboliai.";
    }
    else{
        $naujas_pavadinimas = $ivestas_pavadinimas;
        $pavadinimas_err = "";
    }
    //-------------------------------------------------------------------------------------------------------------------------------
    
    $ivesta_salis = test_input($_POST["salis"]);
    $ivesta_salis = filter_var($ivesta_salis, FILTER_SANITIZE_NUMBER_INT);
    if (!filter_var($ivesta_salis,FILTER_VALIDATE_INT)|| empty($ivesta_salis)) error();
    else $nauja_salis_id = $ivesta_salis;
    
    //-------------------------------------------------------------------------------------------------------------------------------
    $id = test_input($id);
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    //-------------------------------------------------------------------------------------------------------------------------------
    if (empty($pavadinimas_err) && isset($naujas_pavadinimas) && isset($nauja_salis_id) && isset($id))
    {
        $sql = "UPDATE avialinija SET pavadinimas=?, salis_id=? WHERE id=?";
        if ($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("ssi", $naujas_pavadinimas, $nauja_salis_id, $id);
            if ($stmt->execute())
            {
                $stmt->close();
                $mysqli->close();
                header("location:airlines.php");
                exit();
            }
        }
        else error();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avialinijos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="./includes/style.css">
    
</head>

<body>
    
     <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Oro uostai</a></li>
                    <li class="active"><a href="airlines.php">Avialinijos</a></li>
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
    
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Redaguoti avialinijas</h2>
                    </div>
                    
                    <form action="<?php echo test_input(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        
                        <div class="form-group <?php echo (!empty($pavadinimas_err)) ? 'error' : ''; ?>"
                            <label>Pavadinimas</label>
                            <input type="text" name="pavadinimas" class="form-control" value="<?php echo $senas_pavadinimas; ?>">
                            <span class="help-block" style="color:red"><?php echo $pavadinimas_err;?></span>
                        </div>
                        
                        <div class="form-group"
                            <label>Salis</label>
                            <select  value="" name="salis" class="form-control">    
                            <?php
                            echo "<option value='$sena_salis_id'>$sena_salis_pavadinimas [$sena_salis_pavadinimas]</option>";
                            $salys = $mysqli->query("SELECT * FROM salis ORDER BY pavadinimas");
                            while($rows = $salys->fetch_assoc())
                            {
                                $saliu_pavadinimai = $rows['pavadinimas'];
                                $saliu_id = $rows['id'];
                                $saliu_iso = $rows['iso'];
                                if ($saliu_pavadinimai != $sena_salis_pavadinimas) 
                                {
                                    echo "<option value ='$saliu_id'>$saliu_pavadinimai [$saliu_iso]</option>";
                                }
                            }
                            ?>
                            </select>
                        </div>
                        
                        <input type="submit" name="btnSubmit" class="btn btn-primary" value="Redaguoti">
                        <a href="airlines.php" class="btn btn-default">Atsaukti</a>
                        
                    </form>
                    
                </div>
            </div>        
        </div>
    </div>
</body>
</html>