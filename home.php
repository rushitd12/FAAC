<?php
session_start();
if(!isset($_SESSION['email']))
{
    header("Location: index.html");
}
$filter = $_GET['filter'];
$dept = $_SESSION['department'];
include "admin/database.php";
$con = connect();
$sql = "SELECT * FROM `feedback`";
if($filter == "done")
    $sql = $sql." WHERE done='D'";
else if($filter == "remaining")
    $sql = $sql." WHERE done='R'";
else if($filter == "positive")
    $sql = $sql." WHERE sentiment='P'";
else if($filter == "negative")
    $sql = $sql." WHERE sentiment='N'";
if($filter == "all")
	$sql = $sql." WHERE department='".$dept."'";
else
	$sql = $sql." AND department='".$dept."'";
$result = mysqli_query($con,$sql);


/*$chartSqlPositive = "SELECT Count(sentiment) as 'countP' FROM `feedback` WHERE department='".$dept."' AND Sentiment='P'";
$chartSqlNegative = "SELECT Count(sentiment) as 'countN' FROM `feedback` WHERE department='".$dept."' AND Sentiment='N'";
$chartSqlTotal = "SELECT Count(sentiment) as 'Total' FROM `feedback` WHERE department='".$dept."'";
$chartResultPositive = mysqli_fetch_assoc(mysqli_query($con, $chartSqlPositive));
$chartResultNegative = mysqli_fetch_assoc(mysqli_query($con, $chartSqlNegative));
$chartResultTotal = mysqli_fetch_assoc(mysqli_query($con, $chartSqlTotal));*/

?>
<!doctype html>
<html lang="en">
  <head>
    <title>FAAC</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script>
	window.onload = function() {

	var chart = new CanvasJS.Chart("chartContainer", {
		animationEnabled: true,
		title: {
			text: "Positive/Negative Feedback Ratio"
		},
		data: [{
			type: "pie",
			startAngle: 240,
			yValueFormatString: "##0.00\"%\"",
			indexLabel: "{label} {y}",
			dataPoints: [
				{y: 60, label: "Positive"},
				{y: 40, label: "Negative"}
			]
		}]
	});
	chart.render();

	}
	</script>
  </head>
  <body>
      
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Feedback Analyzer And Classifier</a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="process.php?action=logout">Sign Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row mt-2">
                <!--For the feedbacks-->
                <div class="col-sm-7" style="border-right: 1px solid black;" >

                    <!--Heading-->
                    <h3 class="display-4" style="float: block">Recent Feedbacks:</h3> 

                    <!--Filters-->
                    <div class="dropdown mt-4" style="float: right block">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filters
                        </button>
                        <div class="dropdown-menu" aria-labelledby="triggerId">
                            <a class="dropdown-item <?php if($filter == "all") echo 'active';?>" href="home.php?filter=all">All</a>
                            <a class="dropdown-item <?php if($filter == "done") echo 'active';?>" href="home.php?filter=done">Done</a>
                            <a class="dropdown-item <?php if($filter == "remaining") echo 'active';?>" href="home.php?filter=remaining">Remaining</a>
                            <a class="dropdown-item <?php if($filter == "positive") echo 'active';?>" href="home.php?filter=positive">Positive</a>
                            <a class="dropdown-item <?php if($filter == "negative") echo 'active';?>" href="home.php?filter=negative">Negative</a>
                        </div>
                        
                    </div>
                    
                    <!--Feedbacks-->
                    <div class="row m-2">
                        <div class="col-lg-6" style="border:1px solid">
                            <h5>Feedbacks</h5>
                        </div>
                        <div class="col-lg-3" style="border:1px solid">
                            <h5>Positive
                            /Negative</h5>
                        </div>
                        <div class="col-lg-3" style="border:1px solid">
                            <h5>Done
                                /Reamaining</h5>
                        </div>
                    </div>
                    <?php
					if($result!=FALSE)
					{
                    while($data = mysqli_fetch_assoc($result))
                    {
                        extract($data);
                    ?>
                    <div class="row m-2">
                        <div class="col-lg-6 p-3" style="border:1px solid">
                            <?php echo $text; ?>
                        </div>
                        <div class="col-lg-3 p-3" style="border:1px solid">
                            <?php 
                                if($sentiment == 'P')
                                    echo "Positive";
                                else
                                    echo "Negative";?>
                        </div>
                        <div class="col-lg-3 p-3" style="border:1px solid">
                            <?php
                                if($done == 'R')
                                {
                                        ?>
                                    <a href="process.php?action=done&fid=<?php echo $fid;?>&filter=<?php echo $filter;?>"><button class="btn">Mark as Done</button></a>
                            <?php
                                }
                                else
                                {
                                    echo "Done";
                                }
                            ?>
                            
                        </div>
                    </div>
                    <?php
                    }
					}
                    ?>
                    <!--Feedbacks over-->    

                </div>
                
                <!--For the graph-->
                <div class="col-sm-5">
                    <h3 class="display-4">Analysis:</h3>  
					<div id="chartContainer" style="height: 300px; width: 100%;">
					</div>
                </div>
        </div>
        
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  </body>
</html>