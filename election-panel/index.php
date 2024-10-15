<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php include("includes/header.php");?>

<?php
    $election_id = $_SESSION['election-id'];
    $query = "SELECT
                CASE
                     WHEN elections.status = 'completed' THEN candidates.name
                     WHEN elections.status = 'running' THEN '?'
                     ELSE NULL -- Handle other cases as needed
                END AS candidate_name,
                COUNT(votes.candidate_id) AS vote_count
            FROM
                candidates_table AS candidates
            JOIN
                votestable AS votes ON candidates.candidate_id = votes.candidate_id
            JOIN
                 positionstable AS positions ON votes.position_id = positions.position_id
            JOIN
                electiontable AS elections ON positions.election_id = elections.election_id
            WHERE
                elections.status IN ('completed', 'running') AND positions.election_id = '$election_id'
            GROUP BY
                candidates.candidate_id, elections.status
            ORDER BY
                positions.priority DESC
            LIMIT 1;
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
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-dark-blue shadow-dark text-center border-radius-xl mt-n4 position-absolute">
              <i class="fa-solid fa-ranking-star opacity-10"></i>
            </div>
            <div class="text-end pt-1">
              <h4 class="mb-0 fs-1 text-black">
              <?php
                $sql = "SELECT * FROM positionstable WHERE election_id = '$election_id'";
                $query = $con->query($sql);

                echo "$query->num_rows";
              ?>
              </h4>
              <p class="text-sm mb-0 text-capitalize text-black">Number of Positions</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-dark-blue shadow-primary text-center border-radius-xl mt-n4 position-absolute">
              <i class="fa-brands fa-black-tie opacity-10"></i>
            </div>
            <div class="text-end pt-1">
              <h4 class="mb-0 fs-1 text-black">
                <?php
                  $sql = "SELECT * FROM candidates_table WHERE election_id = '$election_id' AND isData_verified = 1";
                  $query = $con->query($sql);

                  echo "$query->num_rows";
                ?>
              </h4>
              <p class="text-sm mb-0 text-capitalize text-black">Number of Candidates</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-dark-blue shadow-primary text-center border-radius-xl mt-n4 position-absolute">
              <i class="fa-solid fa-people-group opacity-10"></i>
            </div>
            <div class="text-end pt-1">
              <h4 class="mb-0 fs-1 text-black">
                <?php
                  $sql = "SELECT * FROM voters_table WHERE election_id = '$election_id'";
                  $query = $con->query($sql);

                  echo "$query->num_rows";
                ?>
              </h4>
              <p class="text-sm mb-0 text-capitalize text-black">Number of Voters</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-dark-blue shadow-primary text-center border-radius-xl mt-n4 position-absolute">
              <i class="fa-solid fa-pen-to-square opacity-10"></i>
            </div>
            <div class="text-end pt-1">
              <h4 class="mb-0 fs-1 text-black"><?php
                  $sql = "SELECT * FROM votestable WHERE election_id = '$election_id' GROUP BY voter_id";
                  $query = $con->query($sql);

                  echo "$query->num_rows";
                ?>
              </h4>
              <p class="text-sm mb-0 text-capitalize text-black">Number of Voters voted</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4 ">
      <?php
        $sql = "SELECT positionstable.*, electiontable.status
                FROM positionstable
                JOIN electiontable ON positionstable.election_id = electiontable.election_id
                WHERE positionstable.election_id = '$election_id' AND electiontable.status != 'building'
                ORDER BY priority ASC LIMIT 1";

        $query = $con->query($sql);

        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $positionDesc = "Partial and Unofficial Results for " . $row["position_desc"];
        } else {
            $positionDesc = "Partial and Unofficial Results";
        }

        echo '
            <div class="col-lg-6 col-md-6 mt-4">
                <div class="card z-index-2">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-dark-blue shadow-primary border-radius-lg py-3 pe-1">
                            <div class="chart">
                                <canvas id="empty_result" class="chart-canvas" height="330"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">'.$positionDesc.'</h6>
                        <button class="btn btn-primary" id="view_moreBtn">View More</button>
                    </div>
                </div>
            </div>
        ';
      ?>
      <div class="col-lg-6 col-md-6">
        <div class="row">
          <div class="col-lg-6">
            <div class="card mb-4">
              <div class="card-header pb-0">
                <h6 class="text-black"><i class="fa-regular fa-calendar-days me-2"></i>Start Date</h6>
              </div>
              <div class="card-body">
                  <p class="text-dark mb-2 fw-normal" id="start-date">Dec 13, 2022, 11:00:00 PM</p>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header pb-0">
                <h6 class="text-black"><i class="fa-regular fa-calendar-days me-2"></i>End Date</h6>
              </div>
              <div class="card-body">
                  <p class="text-dark mb-2 fw-normal" id="end-date">Dec 25, 2022, 11:00:00 PM</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="card">
              <div class="card-header pb-0">
                  <h5 class="text-black"><i class="fa-solid fa-earth-americas me-2"></i>Election Voting Information</h5>
              </div>
              <div class="card-body pb-2">
              <h6 class="text-black">Election URL</h6>
                <div class="input-group input-group-outline my-3">
                  <input type="url" class="form-control" value="http://localhost/vote/index.php" id="election_link" readonly>
                  <button type="button" id="copy_link" class="btn bg-primary mb-0">COPY</button>
                </div>
                <h6 class="text-black">Election Voting Code</h6>
                <div class="input-group input-group-outline my-3">
                  <input type="text" class="form-control" value="xdf-kjf-ply" id="election_code" readonly style="letter-spacing: 1px;">
                  <button type="button" id="copy_code" class="btn bg-primary mb-0">COPY</button>
                </div>
              </div>
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
  var ctx = document.getElementById("empty_result").getContext("2d");

  new Chart(ctx, {
      type: "bar",
      data: {
          labels: votesData.map(item => item.candidate_name),
          datasets: [{
            label: "Votes",
            tension: 0.4,
            borderWidth: 0,
            borderRadius: 4,
            borderSkipped: false,
            backgroundColor: "rgba(255, 255, 255, .8)",
            data: votesData.map(item => item.vote_count),
            maxBarThickness: 8
          }, ],
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
                display: false,
            }
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
                grid: {
                  drawBorder: false,
                  display: true,
                  drawOnChartArea: true,
                  drawTicks: false,
                  borderDash: [5, 5],
                  color: 'rgba(255, 255, 255, .2)'
                },
                ticks: {
                  suggestedMin: 0,
                  suggestedMax: 500,
                  beginAtZero: true,
                  padding: 10,
                  font: {
                      size: 14,
                      weight: 300,
                      family: "Roboto",
                      style: 'normal',
                      lineHeight: 2
                  },
                  color: "#fff"
                },
            },
            x: {
                grid: {
                  drawBorder: false,
                  display: true,
                  drawOnChartArea: true,
                  drawTicks: false,
                  borderDash: [5, 5],
                  color: 'rgba(255, 255, 255, .2)'
                },
                ticks: {
                  display: true,
                  color: '#f8f9fa',
                  padding: 10,
                  font: {
                      size: 14,
                      weight: 300,
                      family: "Roboto",
                      style: 'normal',
                      lineHeight: 2
                  },
                }
            },
          },
      },
  });
</script>

<?php include("../includes/footer.php");?>
<script src="../assets/js/material-dashboard.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>