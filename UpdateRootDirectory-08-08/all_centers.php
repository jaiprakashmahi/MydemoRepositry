<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php
// Database connection
include 'conn.php';

// Fetch all centers
$sql = "SELECT * FROM center_details";
$result = $conn->query($sql);
?>

<head>
  <!-- Meta Tags -->
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="author" content="" />
  <!-- Page Title -->
  <title>Our Centre</title>
  <!-- Favicon and Touch Icons -->
  <link href="images/favicon.png" rel="shortcut icon" type="image/png">
  <link href="images/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="images/apple-touch-icon-72x72.png" rel="apple-touch-icon" sizes="72x72">
  <link href="images/apple-touch-icon-114x114.png" rel="apple-touch-icon" sizes="114x114">
  <link href="images/apple-touch-icon-144x144.png" rel="apple-touch-icon" sizes="144x144">

  <!-- Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css">
  <link href="css/animate.css" rel="stylesheet" type="text/css">
  <link href="css/css-plugin-collections.css" rel="stylesheet" />
  <!-- CSS | menuzord megamenu skins -->
  <link id="menuzord-menu-skins" href="css/menuzord-skins/menuzord-rounded-boxed.css" rel="stylesheet" />
  <!-- CSS | Main style file -->
  <link href="css/style-main.css" rel="stylesheet" type="text/css">
  <!-- CSS | Preloader Styles -->
  <link href="css/preloader.css" rel="stylesheet" type="text/css">
  <!-- CSS | Custom Margin Padding Collection -->
  <link href="css/custom-bootstrap-margin-padding.css" rel="stylesheet" type="text/css">
  <!-- CSS | Responsive media queries -->
  <link href="css/responsive.css" rel="stylesheet" type="text/css">
  <!-- CSS | Style css. This is the file where you can place your own custom css code. Just uncomment it and use it. -->
  <!-- <link href="css/style.css" rel="stylesheet" type="text/css"> -->

  <!-- CSS | Theme Color -->
  <link href="css/colors/theme-skin-color-set-1.css" rel="stylesheet" type="text/css">

  <!-- external javascripts -->
  <script src="js/jquery-2.2.4.min.js"></script>
  <script src="js/jquery-ui.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- JS | jquery plugin collection for this theme -->
  <script src="js/jquery-plugin-collection.js"></script>

  <style>
    /* Additional styling for center cards */
    .center-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-radius: 10px;
      overflow: hidden;
      height: 100%;
      background: #ffffff;
      border: 1px solid #e9ecef;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .center-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .center-card .card-body {
      padding: 25px 20px;
    }

    .avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #0066cc;
      padding: 3px;
      background: #ffffff;
    }

    .user-img {
      margin-bottom: 15px;
    }

    .user-name {
      color: #333;
      font-weight: 600;
      font-size: 18px;
      margin-bottom: 5px;
    }

    .user-email {
      color: #6c757d;
      font-size: 14px;
      margin-bottom: 0;
    }

    .btn-primary {
      background-color: #0066cc;
      border-color: #0066cc;
    }

    .btn-primary:hover {
      background-color: #0052a3;
      border-color: #0052a3;
    }

    .btn-light {
      background-color: #f8f9fa;
      border-color: #dee2e6;
      color: #333;
    }

    .btn-light:hover {
      background-color: #e9ecef;
      border-color: #dee2e6;
    }

    .text-theme-color-2 {
      color: #0066cc !important;
    }

    .border-left-theme-color-2-3px {
      border-left: 3px solid #0066cc !important;
    }

    .funfact .pe-7s-smile,
    .funfact .pe-7s-note2,
    .funfact .pe-7s-users,
    .funfact .pe-7s-cup {
      font-size: 48px;
      color: #0066cc;
    }

    /* Ensure consistent card height */
    .center-card-wrapper {
      display: flex;
      margin-bottom: 30px;
    }

    .center-card-wrapper .center-card {
      width: 100%;
    }

    /* Fix for last row single item alignment */
    .row.center-grid {
      display: flex;
      flex-wrap: wrap;
    }

    .row.center-grid .col-lg-4 {
      display: flex;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
      .row.center-grid .col-md-6 {
        display: flex;
      }
    }

    @media (max-width: 767px) {
      .row.center-grid .col-sm-6 {
        display: flex;
      }
    }

    @media (max-width: 575px) {
      .row.center-grid .col-12 {
        display: flex;
      }
    }
  </style>
</head>

<body class="">
  <div id="wrapper" class="clearfix">
    <!-- preloader -->

    <!-- Header -->
    <?php include 'head.php'; ?>
    <!-- Start main-content -->
    <div class="main-content">

      <!-- Section: inner-header -->
      <section class="inner-header divider parallax layer-overlay overlay-dark-5" data-bg-img="images/bg/bg3.jpg">
        <div class="container pt-70 pb-20">
          <!-- Section Content -->
          <div class="section-content">
            <div class="row">
              <div class="col-md-12">
                <h2 class="title text-white">Our Centre</h2>
                <ol class="breadcrumb text-left text-black mt-10">
                  <li><a href="index.php">Home</a></li>
                  <li><a href="all_centers.php">Current page</a></li>
                </ol>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section: Centers -->
      <section class="bg-lighter">
        <div class="container pb-40">
          <div class="section-title mb-0">
            <div class="row">
              <div class="col-md-8">
                <h2 class="text-uppercase font-28 line-bottom mt-0 line-height-1">Our <span class="text-theme-color-2 font-weight-400">CENTERS</span></h2>
              </div>
            </div>
          </div>
          <div class="section-content">
            <div class="row center-grid">
              <?php
              // Check if any records exist
              $sql = "SELECT * FROM center_details";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  // Professional image handling logic
                  $photo_path = trim((string)($row['photo'] ?? ''));
                  $physicalPath = __DIR__ . DIRECTORY_SEPARATOR . $photo_path;
                  if (!empty($photo_path) && file_exists($physicalPath)) {
                    $photo_url = htmlspecialchars($photo_path);
                  } else {
                    $photo_url = 'https://www.gravatar.com/avatar/?d=mp&s=150';
                  }
              ?>
                  <div class="col-lg-4 col-md-6 col-sm-6 col-12 center-card-wrapper">
                    <div class="center-card">
                      <div class="card-body">
                        <div class="user-content">
                          <div class="user-info">
                            <div class="user-img text-center">
                              <img src="<?php echo $photo_url; ?>" alt="<?php echo htmlspecialchars($row['center_name']); ?>" class="avatar">
                            </div>
                            <div class="user-details text-center">
                              <h4 class="user-name"><?php echo htmlspecialchars($row['center_name']); ?></h4>
                              <p class="user-email"><?php echo htmlspecialchars($row['email']); ?></p>
                            </div>
                          </div>

                          <div class="d-flex align-items-center mt-3">
                            <a href="tel:<?php echo htmlspecialchars($row['phone']); ?>" class="btn btn-primary btn-sm w-50 me-2">
                              <i class="fa fa-phone me-1"></i> <?php echo htmlspecialchars($row['phone']); ?>
                            </a>
                            <a class="btn btn-light btn-sm w-50">
                              <i class="fa fa-map-marker me-1"></i> <?php echo htmlspecialchars($row['center_code']); ?>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

              <?php
                }
              } else {
                echo "<div class='col-12'><p class='text-center'>No centers found.</p></div>";
              }
              ?>
            </div>
          </div>
        </div>
      </section>

      <section class="divider parallax layer-overlay overlay-theme-colored-9" data-bg-img="images/bg/bg2.jpg" data-parallax-ratio="0.7">
        <div class="container">
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
              <div class="funfact text-center">
                <i class="pe-7s-smile mt-5 text-theme-color-2"></i>
                <h2 data-animation-duration="2000" data-value="5248" class="animate-number text-white mt-0 font-38 font-weight-500">0</h2>
                <h5 class="text-white text-uppercase mb-0">Happy Students</h5>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
              <div class="funfact text-center">
                <i class="pe-7s-note2 mt-5 text-theme-color-2"></i>
                <h2 data-animation-duration="2000" data-value="675" class="animate-number text-white mt-0 font-38 font-weight-500">0</h2>
                <h5 class="text-white text-uppercase mb-0">Our Courses</h5>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 mb-md-50">
              <div class="funfact text-center">
                <i class="pe-7s-users mt-5 text-theme-color-2"></i>
                <h2 data-animation-duration="2000" data-value="248" class="animate-number text-white mt-0 font-38 font-weight-500">0</h2>
                <h5 class="text-white text-uppercase mb-0">Our Teachers</h5>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 mb-md-0">
              <div class="funfact text-center">
                <i class="pe-7s-cup mt-5 text-theme-color-2"></i>
                <h2 data-animation-duration="2000" data-value="24" class="animate-number text-white mt-0 font-38 font-weight-500">0</h2>
                <h5 class="text-white text-uppercase mb-0">Awards Won</h5>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section: Services -->
      <section id="services" class="bg-lighter">
        <div class="container">
          <div class="section-title">
            <div class="row">
              <div class="col-md-12">
                <h2 class="text-uppercase text-theme-colored title line-bottom">Our <span class="text-theme-color-2 font-weight-400">Features</span></h2>
              </div>
            </div>
          </div>
          <div class="row mtli-row-clearfix">
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="icon-box iconbox-theme-colored bg-white p-15 mb-30 border-1px">
                <a class="icon icon-dark border-left-theme-color-2-3px pull-left flip mb-0 mr-0 mt-5" href="#">
                  <i class="pe-7s-scissors"></i>
                </a>
                <div class="icon-box-details">
                  <h4 class="icon-box-title font-16 font-weight-600 m-0 mb-5">Practical Training with Live Project</h4>
                  <p class="text-gray font-13 mb-0">Practical training with a live project provides a bridge between theoretical knowledge and practical application.</p>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="icon-box iconbox-theme-colored bg-white p-15 mb-30 border-1px">
                <a class="icon icon-dark border-left-theme-color-2-3px pull-left flip mb-0 mr-0 mt-5" href="#">
                  <i class="pe-7s-pen"></i>
                </a>
                <div class="icon-box-details">
                  <h4 class="icon-box-title font-16 font-weight-600 m-0 mb-5">100% Job Oriented Training</h4>
                  <p class="text-gray font-13 mb-0">This type of training allows participants to apply what they have learned in a practical setting, increasing their employability</p>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="icon-box iconbox-theme-colored bg-white p-15 mb-30 border-1px">
                <a class="icon icon-dark border-left-theme-color-2-3px pull-left flip mb-0 mr-0 mt-5" href="#">
                  <i class="pe-7s-tools"></i>
                </a>
                <div class="icon-box-details">
                  <h4 class="icon-box-title font-16 font-weight-600 m-0 mb-5">Flexible timing & weekend classes</h4>
                  <p class="text-gray font-13 mb-0">Weekend classes are specifically designed for individuals who are unable to attend training during weekdays due to work or other commitments.</p>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="icon-box iconbox-theme-colored bg-white p-15 mb-30 border-1px">
                <a class="icon icon-dark border-left-theme-color-2-3px pull-left flip mb-0 mr-0 mt-5" href="#">
                  <i class="pe-7s-vector"></i>
                </a>
                <div class="icon-box-details">
                  <h4 class="icon-box-title font-16 font-weight-600 m-0 mb-5">Interactive sessions</h4>
                  <p class="text-gray font-13 mb-0">Interactive sessions are designed to enhance participant engagement, promote active learning, and create a collaborative learning environmen</p>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="icon-box iconbox-theme-colored bg-white p-15 mb-30 border-1px">
                <a class="icon icon-dark border-left-theme-color-2-3px pull-left flip mb-0 mr-0 mt-5" href="#">
                  <i class="pe-7s-phone"></i>
                </a>
                <div class="icon-box-details">
                  <h4 class="icon-box-title font-16 font-weight-600 m-0 mb-5">Standard study material</h4>
                  <p class="text-gray font-13 mb-0">Standard study material refers to educational resources that are widely recognized and accepted as authoritative and reliable for a particular subject or field of study.</p>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="icon-box iconbox-theme-colored bg-white p-15 mb-30 border-1px">
                <a class="icon icon-dark border-left-theme-color-2-3px pull-left flip mb-0 mr-0 mt-5" href="#">
                  <i class="pe-7s-light"></i>
                </a>
                <div class="icon-box-details">
                  <h4 class="icon-box-title font-16 font-weight-600 m-0 mb-5">Experienced corporate Trainer</h4>
                  <p class="text-gray font-13 mb-0">Experienced corporate trainers expertise and training interventions contribute to organizational growth, employee engagement, and success.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Divider: Funfact -->

    </div>
    <!-- end main-content -->

    <?php include 'footer.php'; ?>
    <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
  </div>
  <!-- end wrapper -->

  <!-- Footer Scripts -->
  <!-- JS | Custom script for all pages -->
  <script src="js/custom.js"></script>

</body>

</html>