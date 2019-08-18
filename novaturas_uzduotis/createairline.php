<?php

require_once('./includes/dbconnect.php');
include('./includes/functions.php');
$pavadinimas = $ivestas_pavadinimas = $pavadinimas_err = $salis = $ivesta_salis = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    //-----------------------------------------------------------------------------------------------------------------------------------------
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
        $pavadinimas = $ivestas_pavadinimas;
        $pavadinimas_err = "";
    } 
    //-----------------------------------------------------------------------------------------------------------------------------------------
    
    $ivesta_salis = test_input($_POST["salis"]);
    $ivesta_salis = filter_var($ivesta_salis, FILTER_SANITIZE_NUMBER_INT);
    if (!filter_var($ivesta_salis,FILTER_VALIDATE_INT) || empty($ivesta_salis)) error();
    else $salis = $ivesta_salis;
    
    //-----------------------------------------------------------------------------------------------------------------------------------------
    
    if (empty($pavadinimas_err) && isset($pavadinimas) && isset($salis))
    {
        $sql = "INSERT INTO avialinija (pavadinimas, salis_id) VALUES (?, ?)";
        if ($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("ss", $pavadinimas, $salis);
            if ($stmt->execute())
            {
                header("location:airlines.php");
                $stmt->close();
                $mysqli->close();
                exit();
            }
        }
        else error();
    }
}
?>


<!DOCTYPE html>
<html>
    
    <head>
        <title>Avialinijos</title>
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
                        <h2>Prideti naujas avialinijas</h2>
                    </div>
                    
                    <form action="<?php echo test_input($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($pavadinimas_err)) ? 'error' : ''; ?>"
                            <label>Pavadinimas</label>
                            <input type="text" maxlenght="64" name="pavadinimas" class="form-control" value="<?php echo $ivestas_pavadinimas; ?>">
                            <span class="help-block" style="color:red"><?php echo $pavadinimas_err;?></span>
                        </div>
                        <div class="form-group"
                            <label>Salis</label>
                            <select name="salis" class="form-control">
                                
                            <?php
                            $salys = $mysqli->query("SELECT * FROM salis ORDER BY pavadinimas");
                            while($rows = $salys->fetch_assoc())
                            {
                                $pavadinimai = $rows['pavadinimas'];
                                $iso = $rows['iso'];
                                $id = $rows['id'];
                                echo "<option value ='$id'>$pavadinimai [$iso] </option>";
                            }
                            $salys->free();
                            ?>
                                
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Prideti">
                        <a href="airlines.php" class="btn btn-default">Atsaukti</a>
                    </form>
                    
                </div>
            </div>        
        </div>
    </div>   
    </body>
</html>