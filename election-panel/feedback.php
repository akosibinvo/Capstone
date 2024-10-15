<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php include("includes/header.php");?>

<?php
    $election_id = $_SESSION['election-id'];
    $query = "SELECT 
                overall,
                COUNT(*) AS count_per_sentiment,
                CONCAT(ROUND((COUNT(*) / (SELECT COUNT(*) FROM sentiment_tb WHERE election_id = '$election_id')) * 100, 2), '%') AS percentage
            FROM sentiment_tb
            WHERE election_id = '$election_id'
            GROUP BY overall DESC;
            ";

  $result = mysqli_query($con, $query);

  // Fetch the results and store them in an associative array
  $data = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  // Encode the array into JSON for use in Chart.js
  $jsonData = json_encode($data);
?>

  <div class="container-fluid pt-4 pb-2 pe-4">
    <div class="row">
      <?php
        $sql = "SELECT * FROM sentiment_tb where election_id = '$election_id'";

        $query = $con->query($sql);

        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
             echo '
                <div class="col-lg-5 col-md-6">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-dark-blue shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                              <i class="fa-solid fa-thumbs-up opacity-10"></i>
                            </div>
                            
                        </div>
                        <div class="card-body pb-0">
                        <h5 class="ms-5 ps-4 mt-n5 pt-2">Voters Feedback</h5>
                            <div class="chart mt-5" style="height: 71vh !important;">
                                <canvas id="feedback_chart" class="chart-canvas" ></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
      ?>
        <div class="col-lg-7 col-md-6">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="table-responsive p-0" style="height: 79vh !important;overflow-y: auto !important">
                            <table class="table align-items-center mb-0 table-fixed">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Voter Id</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Feedback</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder">Overall Results</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(isset($_SESSION['election-id'])) {
                                            $election_id = $_SESSION['election-id'];
                                            $output = '';

                                            $sql = "SELECT * FROM sentiment_tb WHERE election_id = '$election_id'";
                                                $query = $con->query($sql);
                                                

                                                if(mysqli_num_rows($query) > 0){
                                                    while($row = $query->fetch_assoc()){
                                                        $output .= '
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-0 text-xs">'.$row["voter_id"].'</h6>
                                                            </td>
                                                            <td> 
                                                                <h6 class="mb-0 text-xs">'.$row["sentiment"].'</h6>
                                                            </td>
                                                            <td>
                                                                <h6 class="mb-0 text-xs">'.$row["overall"].'</h6>
                                                            </td>
                                                        </tr> 
                                                        ';
                                                    }
                                                    echo $output;
                                                }
                                                else {
                                                    ?>

                                                    <tr>
                                                        <td colspan="3">
                                                            <h6 class="mb-0 text-md ps-3 text-center pt-3">No Voter Feedbacks.</h6>
                                                        </td>
                                                    </tr>

                                                    <?php
                                                }
                                        }
                                    ?>
                                                            
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
     // Use the encoded JSON data in JavaScript
    var votesData = <?php echo $jsonData; ?>;
    var ctx = document.getElementById('feedback_chart').getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: votesData.map(item => `${item.overall} ${item.percentage}`),
            datasets: [{
                data: votesData.map(item => item.count_per_sentiment), // Values representing the proportions
                backgroundColor: votesData.map(item => {
                    // Map sentiment categories to colors
                    if (item.overall === 'Positive') {
                        return '#00008b'; // Positive color
                    } else if (item.overall === 'Negative') {
                        return 'red'; // Negative color
                    } else if (item.overall === 'Neutral') {
                        return 'orange'; // Neutral color
                    }
                }),
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: {
                position: 'bottom',
                align: 'center',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                }
              },
              title: {
                display: false,
                text: 'Voters Feedback',
                color: 'black',
                font: {
                    size: 20,
                }
              }
            },
        },
    });
</script>

<?php include("../includes/footer.php");?>
<script src="../assets/js/material-dashboard.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>