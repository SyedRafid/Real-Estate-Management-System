<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/partials/_analytics.php';

$title = "Dashboard";
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'partials/_head.php'; ?>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include 'partials/_sidebar.php'; ?>
    <!-- End of Sidebar -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <?php include 'partials/_topbar.php'; ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (daily) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div
                        class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                        Total Assets
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo formatBDT($totalAssets); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fa-solid fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div
                        class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Payments (Monthly)
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo formatBDT($monthPayment); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fa-regular fa-calendar-days fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Year) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div
                        class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Payments (Annual)
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo formatBDT($yearPayment); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fa-regular fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (daily) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div
                        class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Expenses (Daily)
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo formatBDT($dayexpense); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fa-solid fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div
                        class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Expenses (Monthly)
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo formatBDT($monthExpense); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fa-regular fa-calendar-days fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Year) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div
                        class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Expenses (Annual)
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo formatBDT($yearxpense); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fa-regular fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <!-- <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div
                        class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Sales (Monthly)
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo formatBDT($monthSale); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fa-regular fa-calendar-days fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->

            <!-- Earnings (Year) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div
                        class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Sales (Annual)
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo formatBDT($yearSale); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fa-regular fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Year) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div
                        class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Funding (Annual)
                      </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo formatBDT($yearfunding); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fa-regular fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>


          </div>

          <!-- Content Row -->

          <div class="row">
            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                  class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">
                    Assets Overview
                  </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="assetChart" width="100%" height="40"></canvas>
                  </div>
                </div>
              </div>
            </div>


            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                  class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">
                    Assets Sources
                  </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4 pb-2">
                    <canvas id="assetPieChart"></canvas>
                  </div>
                  <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Payments
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Fundings
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->
          <div class="row">
            <!-- Content Column -->
            <div class="col-lg-6 mb-4">
              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                  class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">
                    Sales Overview
                  </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="salesChart" width="100%"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6 mb-4">
              <!-- Illustrations -->
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                  class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">
                    Payments Overview
                  </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="paymentsChart" width="100%"></canvas>
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php include 'partials/_footer.php'; ?>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->

  <!-- Logout Modal-->
  <?php include 'partials/_logoutModal.php'; ?>
  <?php include 'partials/_scripts.php'; ?>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const canvas = document.getElementById("assetChart");
      const ctx = canvas.getContext("2d");

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: <?php echo json_encode($labels); ?>,
          datasets: [{
            label: "Total Assets (৳)",
            data: <?php echo json_encode($totals); ?>,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          scales: {
            y: {
              ticks: {
                callback: function(value) {
                  return '৳' + value.toLocaleString('en-IN');
                }
              }
            }
          }
        }
      });


      var totalPayments = <?php echo $totalPayments; ?>;
      var totalFundings = <?php echo $totalFundings; ?>;
      var pieCtx = document.getElementById("assetPieChart").getContext("2d");

      new Chart(pieCtx, {
        type: 'doughnut',
        data: {
          labels: ["Payments", "Fundings"],
          datasets: [{
            data: [totalPayments, totalFundings],
            backgroundColor: ['#4e73df', '#1cc88a'],
            hoverBackgroundColor: ['#2e59d9', '#17a673'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          }],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: true,
            caretPadding: 10,
          },
          legend: {
            display: false
          },
          cutoutPercentage: 70,
        },
      });


      var salesCtx = document.getElementById("salesChart").getContext("2d");
      new Chart(salesCtx, {
        type: 'line',
        data: {
          labels: <?php echo $jsLabels; ?>,
          datasets: [{
            label: "Total Sales (৳)",
            data: <?php echo $jsData; ?>,
            lineTension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "#4e73df",
            pointRadius: 3,
            pointBackgroundColor: "#4e73df",
            pointBorderColor: "#4e73df",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "#4e73df",
            pointHoverBorderColor: "#4e73df",
            pointHitRadius: 10,
            pointBorderWidth: 2,
          }],
        },
        options: {
          maintainAspectRatio: false,
          scales: {
            x: {
              title: {
                display: true,
                text: 'Month'
              },
              grid: {
                display: false
              }
            },
            y: {
              title: {
                display: true,
                text: 'Sales Amount (৳)'
              },
              beginAtZero: true
            }
          },
          plugins: {
            legend: {
              display: true
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return "৳" + context.raw.toLocaleString('en-IN');
                }
              }
            }
          }
        }
      });


      const paymentCtx = document.getElementById('paymentsChart').getContext('2d');

      const paymentsChart = new Chart(paymentCtx, {
        type: 'line',
        data: {
          labels: [
            <?php foreach ($monthly_payments_data as $row) {
              echo '"' . $row['label'] . '",';
            } ?>
          ],
          datasets: [{
            label: 'Monthly Payments (৳)',
            data: [
              <?php foreach ($monthly_payments_data as $row) {
                echo $row['monthly_payment_total'] . ',';
              } ?>
            ],
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 2,
            pointRadius: 3,
            pointHoverRadius: 5,
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '৳' + value.toLocaleString('en-IN');
                }
              }
            }
          },
          plugins: {
            legend: {
              display: true
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return '৳' + context.parsed.y.toLocaleString('en-IN');
                }
              }
            }
          }
        }
      });


    });
  </script>


</body>

</html>