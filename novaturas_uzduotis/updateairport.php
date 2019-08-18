<?php
require_once('./includes/dbconnect.php');
include('./includes/functions.php');
$id = $senas_pavadinimas = $sena_ilguma = $sena_platuma = $sena_salis_pavadinimas = $sena_salis_id = $sena_salis_iso = $naujas_pavadinimas = $nauja_salis_id = $nauja_ilguma = $nauja_platuma = $pavadinimas_err = $lokacija_err = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"])))
{
    $id = filter_var(test_input(trim($_GET["id"])), FILTER_SANITIZE_NUMBER_INT);
    if (filter_var($id, FILTER_VALIDATE_INT))
    {
        $sql = $sql = "SELECT orouostas.pavadinimas, orouostas.ilguma, orouostas.platuma, salis.pavadinimas, salis.id, salis.iso FROM salis INNER JOIN orouostas ON salis.id = orouostas.salis WHERE orouostas.id = ? ";
        if ($stmt = $mysqli -> prepare($sql))
        {
            $stmt -> bind_param("i", $id);
            if ($stmt -> execute())
            {
                $result = $stmt->get_result();
                if ($result->num_rows ==1)
                {
                    while ($row = $result -> fetch_array())
                    {
                        $senas_pavadinimas = $row[0];
                        $sena_ilguma = $row[1];
                        $sena_platuma = $row[2];
                        $sena_salis_pavadinimas = $row[3];
                        $sena_salis_id = $row[4];
                        $sena_salis_iso = $row[5];
                    }
                    $result->free();
                }
                else error();
            }
            else error();
        }
    }
    else error();
    $stmt -> close();
}


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
        $naujas_pavadinimas = $ivestas_pavadinimas;
        $pavadinimas_err = "";
    }
    
    //------------------------------------------------------------------------------------------------------------
    $ivesta_salis_id = test_input($_POST["salis"]);
    $ivesta_salis_id = filter_var($ivesta_salis_id, FILTER_SANITIZE_NUMBER_INT);
    if (!filter_var($ivesta_salis_id,FILTER_VALIDATE_INT) || empty($ivesta_salis_id)) error();
    else $nauja_salis_id = $ivesta_salis_id;
    //------------------------------------------------------------------------------------------------------------
    
    $nauja_ilguma = test_input($_POST["ilguma"]);
    $nauja_platuma = test_input($_POST["platuma"]);
    if (empty($nauja_ilguma) || empty($nauja_platuma))
    {
        $lokacija_err = "Pazymekite oro uosto lokacija";
    }
    else if (!filter_var($nauja_ilguma,FILTER_VALIDATE_FLOAT) || !filter_var($nauja_platuma, FILTER_VALIDATE_FLOAT))  error();
    else
    {
        $lokacija_err = "";
    }
    
    //------------------------------------------------------------------------------------------------------------
    
    if (empty($pavadinimas_err) && empty($lokacija_err) && isset($id) && isset($naujas_pavadinimas) && isset($nauja_salis_id) && isset($nauja_ilguma) && isset($nauja_platuma))
    {
        $sql = "UPDATE orouostas SET pavadinimas=? , salis=? , ilguma=?, platuma=? WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql))
        {
            $id = test_input($_GET["id"]);
            if (filter_var($id,FILTER_VALIDATE_INT))
            {
                $stmt->bind_param("ssssi", $naujas_pavadinimas,$nauja_salis_id, $nauja_ilguma, $nauja_platuma, $id);
                if ($stmt->execute())
                {
                    $stmt->close();
                    $mysqli->close();
                    header("location:index.php");
                    exit();
                }
            }
            else error();
        }
        else  error();
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
                        <h2>Redaguoti oro uosta</h2>
                    </div>
                    
                    <form action="<?php echo basename($_SERVER['REQUEST_URI']); ?>" method="post">
                        <div class="form-group <?php echo (!empty($pavadinimas_err)) ? 'error' : ''; ?>"
                            <label>Pavadinimas</label>
                            <input type="text" maxlenght="64" name="pavadinimas" class="form-control" value="<?php echo $senas_pavadinimas; ?>">
                            <span class="help-block" style="color:red"><?php echo $pavadinimas_err;?></span>
                        </div>
                        
                        <div class="form-group"
                            <label>Salis</label>
                            <select name="salis" class="form-control">
                            <?php
                            echo "<option value='$sena_salis_id'>$sena_salis_pavadinimas [$sena_salis_iso]</option>";
                            $salys = $mysqli->query("SELECT * FROM salis ORDER BY pavadinimas");
                            while($rows = $salys->fetch_assoc())
                            {
                                $saliu_pavadinimai = $rows['pavadinimas'];
                                $saliu_iso = $rows['iso'];
                                $saliu_id = $rows['id'];
                                if ($saliu_id != $sena_salis_id)
                                {
                                    echo "<option value ='$saliu_id'>$saliu_pavadinimai [$saliu_iso] </option>";
                                }
                            }
                            $salys->free();
                            ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <input type="hidden" id="ilguma" name="ilguma" class="form-control" value="<?php echo $sena_ilguma ?>">
                            <span style="color:red" class="help-block"><?php echo $lokacija_err;?></span>
                        </div>
                        
                        <div class="form-group">
                            <input id="platuma" type="hidden" name="platuma" class="form-control" value="<?php echo $sena_platuma?>">
                        </div>
                        
                        <label>Oro uosto lokacija</label>
                        <div class="row">
                            <div class="col">
                            <div id="map"></div>
                             </div>
                        </div>
                        
                        <br>
                        
                        <input type="submit" class="btn btn-primary" value="Redaguoti">
                        <a href="index.php" class="btn btn-default">Atsaukti</a>
                    </form>
                    
                </div>
            </div>        
        </div>
    </div>
        
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8tW0lrIlogiK-v-LRKjgucjlL8IuJasA&callback=createMap" async defer></script>
        <script src="./includes/javaScripts/mapScriptForUpdating.js"></script>
        
    </body>
</html>