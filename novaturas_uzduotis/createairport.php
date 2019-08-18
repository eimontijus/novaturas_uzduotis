<?php
require_once('./includes/dbconnect.php');
include('./includes/functions.php');
$ivestas_pavadinimas = $pavadinimas = $pavadinimas_err = $ivesta_salis = $salis = $ivesta_ilguma = $ilguma = $ivesta_platuma = $platuma = $lokacija_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    
    //------------------------------------------------------------------------------------------------------------
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
    
    //------------------------------------------------------------------------------------------------------------
    $ivesta_salis = test_input($_POST["salis"]);
    $ivesta_salis = filter_var($ivesta_salis, FILTER_SANITIZE_NUMBER_INT);
    if (!filter_var($ivesta_salis,FILTER_VALIDATE_INT) || empty($ivesta_salis)) error();
    else $salis = $ivesta_salis;
    
    //------------------------------------------------------------------------------------------------------------
       
    $ivesta_ilguma = test_input($_POST["ilguma"]);
    $ivesta_platuma = test_input($_POST["platuma"]);
    if (empty($ivesta_ilguma) || empty($ivesta_platuma))
    {
        $lokacija_err = "Pazymekite oro uosto lokacija";
    }
    else if (!filter_var($ivesta_ilguma,FILTER_VALIDATE_FLOAT) || !filter_var($ivesta_platuma, FILTER_VALIDATE_FLOAT)) error();
    else
    {
        $ilguma = $ivesta_ilguma;
        $platuma = $ivesta_platuma;
        $lokacija_err = "";
    }
    
    //------------------------------------------------------------------------------------------------------------
    if (empty($pavadinimas_err) && empty($lokacija_err) && isset($pavadinimas) && isset($ilguma) && isset($platuma) && isset($salis))
    {
        $sql = "INSERT INTO orouostas (pavadinimas, salis, ilguma, platuma) VALUES (?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("ssss", $pavadinimas, $salis, $ilguma, $platuma);
            if ($stmt->execute())
            {
                header("location:index.php");
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
        
        <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Prideti nauja orouosta</h2>
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
                         
                        <div class="form-group">
                            <input type="hidden" name="ilguma" id="ilguma" name="hidden" class="form-control">
                            <span style="color:red" class="help-block"><?php echo $lokacija_err;?></span>
                        </div>
                        
                        <div class="form-group">
                            <input id="platuma" type="hidden" name="platuma" class="form-control">
                        </div>
                        
                        <label>Oro uosto lokacija</label>
                        <div class="row">
                            <div class="col">
                                <div id="map"></div>
                            </div>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Prideti">
                        <a href="index.php" class="btn btn-default">Atsaukti</a>
                        
                    </form>
                </div>
            </div>        
        </div>
    </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8tW0lrIlogiK-v-LRKjgucjlL8IuJasA&callback=createMap"async defer></script>
        <script src="./includes/javaScripts/mapScriptForCreation.js"></script>
    </body>
</html>