<?php include("includes/header.php");?>

<?php
  $query = "SELECT
              positions.position_id,
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
              elections.status IN ('completed', 'running')
            GROUP BY
              positions.position_id,
              elections.status,
              candidates.name
            ORDER BY
              positions.priority ASC;
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
        <?php
            $getStatus = "SELECT status FROM electiontable WHERE election_id = '$election_id'";
            $squery = $con->query($getStatus);
            if ($squery) {
                $row = $squery->fetch_assoc();
                $status = $row['status']; 
                
                if ($status === 'completed') {
                    echo '
                        <div class="d-flex justify-content-end">
                            <button type="button" id="downloadBtnPanel" class="btn btn-primary me-lg-4">GENERATE REPORT</button>
                        </div>
                    ';
                }

            } else {
                echo "Error: " . $con->error;
            }
            
        ?>
        <div class="row pt-4" id="resultPanelpdf" <?= $status === 'completed' ? 'style="overflow: auto; height: 75vh;"' : ''; ?>>
            <?php
                if(isset($_SESSION['election-id'])) {
                    $election_id = $_SESSION['election-id'];
                    $sql = "SELECT * FROM positionstable WHERE election_id = '$election_id' ORDER BY priority ASC";
                    $query = $con->query($sql);
                    while($row = $query->fetch_assoc()){
                        echo '
                            <div class="col-lg-6 col-md-12">
                                <div class="card z-index-2 mb-5">
                                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                                        <div class="bg-dark-blue shadow-primary border-radius-lg py-3 pe-1">
                                        <div class="chart">
                                            <canvas id="'. $row["position_id"] .'" class="chart-canvas" height="350"></canvas>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="card-body pb-2 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Partial and Unofficial Results for '.$row["position_desc"].'</h6>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-html2canvas@latest/dist/jspdf-html2canvas.min.js"></script>
    <script>
        let btn = document.getElementById('downloadBtnPanel');
        let page = document.getElementById('resultPanelpdf');
        
        btn.addEventListener('click', function(){
             var title = document.getElementById('election-name').textContent;
             html2PDF(page, {
                jsPDF: {
                    unit: 'pt',
                    format: 'a4',
                    pagesplit: true,
                },
                imageType: 'image/jpeg',
                margin: {
                    top: 50,
                },
                output: './pdf/generated.pdf',
                html2canvas: {
                    logging: true,
                    allowTaint: false,
                    windowHeight: page.scrollHeight + 500
                },
                watermark({ pdf, pageNumber, totalPageNumber }) {
                    // pdf: jsPDF instance
                    pdf.setTextColor('#000');
                    pdf.setFontSize(20);
                    pdf.text(15, 30, `${title}`);
                }
             });
        });
    </script>
<?php
  $sql = "SELECT * FROM positionstable WHERE election_id = '$election_id' ORDER BY priority ASC";
  $query = $con->query($sql);
  while($row = $query->fetch_assoc()){
  ?>
    <script>
        var votesData = <?php echo $jsonData; ?>;
        var rowid = '<?php echo $row['position_id']; ?>';
        var ctx = document.getElementById(rowid).getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: votesData
                .filter(item => item.position_id === '<?php echo $row['position_id']; ?>')
                .map(item => item.candidate_name),
                datasets: [{
                label: "Votes",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "rgba(255, 255, 255, .8)",
                data: votesData
                .filter(item => item.position_id === '<?php echo $row['position_id']; ?>')
                .map(item => item.vote_count),
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
  <?php
  }
?>
<?php include("../includes/footer.php");?>
<script src="../assets/js/material-dashboard.js"></script>
<script src="../assets/js/script.js" defer></script>
</body>
</html>