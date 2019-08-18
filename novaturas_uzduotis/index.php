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
        
        <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Oro uostai</h2>
                        <a href="createairport.php" class="btn btn-success pull-right">Prideti nauja</a>
                    </div>
                    <?php
                    require_once('./includes/dbconnect.php');
                    $sql = "SELECT orouostas.id, orouostas.pavadinimas, orouostas.ilguma, orouostas.platuma, salis.pavadinimas FROM salis INNER JOIN orouostas ON salis.id = orouostas.salis ";
                    
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>ID</th>";
                                        echo "<th>Pavadinimas</th>";
                                        echo "<th>Salis</th>";
                                        echo "<th>Ilguma</th>";
                                        echo "<th>Platuma</th>";
                                        echo "<th>Veiksmai</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = $result->fetch_array()){
                                    echo "<tr>";
                                        echo "<td>" . $row[0] . "</td>";
                                        echo "<td>" . $row[1] . "</td>";
                                        echo "<td>" . $row[4] . "</td>";
                                        echo "<td>" . $row[2] . "</td>";
                                        echo "<td>" . $row[3] . "</td>";
                                        echo "<td>";
                                            echo "<a href='updateairport.php?id=". $row['id'] ."'title='Redaguoti' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='deleteairport.php?id=". $row['id'] ."' title='Istrinti' data-toggle='tooltip' onclick='return istrinti()'><span class='glyphicon glyphicon-trash'></span></a>";
                                            echo "<a href='airportairlines.php?id=". $row['id'] ."' title='Avialinijos' data-toggle='tooltip'><span class='glyphicon glyphicon-send'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            $result->free();
                        } else{
                            echo "<p class='lead'><em>Irasu nera!</em></p>";
                        }
                    } else{
                        error();
                    }
                    $mysqli->close();
                    ?>
                </div>
            </div>        
        </div>
    </div>
    </body>
    

</html>