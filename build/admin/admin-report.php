<?php
   require('../../helper/connect.php');
   include('../../app/app.php');
   session_start();
   
   //Check if user is logged in
   if (!isset($_SESSION['id'])) {
      header('Location: ../../index.php');
      exit();
   }

   //Hande Logout
   if (isset($_POST['logout_session'])) {
      session_destroy();
      header("Location: ../../index.php");
      exit;
   }
   
   //Generate TitleCase Username for Display
   $username = titleCase($_SESSION['username']);

   //Handles Total Variables
   $result = mysqli_query($connection, "select * from request where status = 'request'");
   $pendingRequest = 0;
   $pendingQuantity = 0;
   $totalSupplier = 0;
   $totalItemPrice = 0;
   $suppliers = [];

   if ($result) {
      $categoryArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
      foreach($categoryArray as $items) {
         $pendingRequest++;
         $pendingQuantity += (int) $items['quantity'];
         $totalItemPrice += (int) $items['price'] * (int) $items['quantity'];
         $suppliers[] = $items['supplier'];
      }

      $uniqueSupplier = array_unique($suppliers);
      $totalSupplier = count($uniqueSupplier);
   }

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Retrieve the selected date from the form
      $selected_date = $_POST["report-date"];
      $_SESSION['$selected_date'] = $selected_date;
  
  } else {
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>DashBoard</title>
   <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
   <link rel="stylesheet" href="../../styles/home-admin.css">
</head>
<body>
<div class="app-container">
   <div class="sidebar">
      <div class="sidebar-header">
         <div class="app-icon">
         <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M507.606 371.054a187.217 187.217 0 00-23.051-19.606c-17.316 19.999-37.648 36.808-60.572 50.041-35.508 20.505-75.893 31.452-116.875 31.711 21.762 8.776 45.224 13.38 69.396 13.38 49.524 0 96.084-19.286 131.103-54.305a15 15 0 004.394-10.606 15.028 15.028 0 00-4.395-10.615zM27.445 351.448a187.392 187.392 0 00-23.051 19.606C1.581 373.868 0 377.691 0 381.669s1.581 7.793 4.394 10.606c35.019 35.019 81.579 54.305 131.103 54.305 24.172 0 47.634-4.604 69.396-13.38-40.985-.259-81.367-11.206-116.879-31.713-22.922-13.231-43.254-30.04-60.569-50.039zM103.015 375.508c24.937 14.4 53.928 24.056 84.837 26.854-53.409-29.561-82.274-70.602-95.861-94.135-14.942-25.878-25.041-53.917-30.063-83.421-14.921.64-29.775 2.868-44.227 6.709-6.6 1.576-11.507 7.517-11.507 14.599 0 1.312.172 2.618.512 3.885 15.32 57.142 52.726 100.35 96.309 125.509zM324.148 402.362c30.908-2.799 59.9-12.454 84.837-26.854 43.583-25.159 80.989-68.367 96.31-125.508.34-1.267.512-2.573.512-3.885 0-7.082-4.907-13.023-11.507-14.599-14.452-3.841-29.306-6.07-44.227-6.709-5.022 29.504-15.121 57.543-30.063 83.421-13.588 23.533-42.419 64.554-95.862 94.134zM187.301 366.948c-15.157-24.483-38.696-71.48-38.696-135.903 0-32.646 6.043-64.401 17.945-94.529-16.394-9.351-33.972-16.623-52.273-21.525-8.004-2.142-16.225 2.604-18.37 10.605-16.372 61.078-4.825 121.063 22.064 167.631 16.325 28.275 39.769 54.111 69.33 73.721zM324.684 366.957c29.568-19.611 53.017-45.451 69.344-73.73 26.889-46.569 38.436-106.553 22.064-167.631-2.145-8.001-10.366-12.748-18.37-10.605-18.304 4.902-35.883 12.176-52.279 21.529 11.9 30.126 17.943 61.88 17.943 94.525.001 64.478-23.58 111.488-38.702 135.912zM266.606 69.813c-2.813-2.813-6.637-4.394-10.615-4.394a15 15 0 00-10.606 4.394c-39.289 39.289-66.78 96.005-66.78 161.231 0 65.256 27.522 121.974 66.78 161.231 2.813 2.813 6.637 4.394 10.615 4.394s7.793-1.581 10.606-4.394c39.248-39.247 66.78-95.96 66.78-161.231.001-65.256-27.511-121.964-66.78-161.231z"/></svg>
         </div>
         <p class="logo"><?= 'Welcome ' . $username ?></p>
      </div>
      <ul class="sidebar-list">
         <li class="sidebar-list-item">
         <a href="admin-purchase.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <span>Purchasing</span>
         </a>
         </li>
         <li class="sidebar-list-item ">
         <a href="admin-request.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
            <span>Request</span>
         </a>
         </li>
         <li class="sidebar-list-item">
         <a href="admin-inventory.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            <span>Inventory</span>
         </a>
         </li>
         <li class="sidebar-list-item active">
         <a href="admin-report.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <span>Reports</span>
         </a>
         </li>
         <li class="sidebar-list-item">
         <a href="admin-account.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span>User Accounts</span>
         </a>
         </li>
      </ul>
      <div class="account-info">
         <div class="account-info-picture">
         <img src="https://images.unsplash.com/photo-1527736947477-2790e28f3443?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTE2fHx3b21hbnxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=60" alt="Account">
         </div>
         <div class="account-info-name"><?= $username ?></div>

         <!-- Handle Logout Query -->
         <form method="POST" action="../../index.php" class="account-info-more">
            <button type="submit" class="account-info-more" name="logout_session">
               <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                     <path d="M17 16L21 12M21 12L17 8M21 12L7 12M13 16V17C13 18.6569 11.6569 20 10 20H6C4.34315 20 3 18.6569 3 17V7C3 5.34315 4.34315 4 6 4H10C11.6569 4 13 5.34315 13 7V8" stroke="#374151" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
               </svg>
            </button>
         </form>

      </div>
   </div>
   <div class="app-content">
         <div class="app-content-header">
          <h1 class="app-content-headerText">Reports</h1>
          <button class="mode-switch" title="Switch Theme">
            <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
              <defs></defs>
              <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
            </svg>
          </button>
        </div>
        <div class="report-container">
              <div class="requests waveeffect hourly-report">
               <h1>Hourly Report</h1>
               <p class="">Click to View</p>
            </div>
            <div class="total-amount waveeffect sales-report">
               <h1>Sales Report</h1>
               <p class="">Click to View</p>
            </div>
            <div class="total-paid-amount waveeffect cashier-report">
               <h1>Cashier Report</h1>
               <p class="">Click to View</p>
            </div>
            <div class="total-purchase-due waveeffect product-report">
               <h1>Product Report</h1>
               <p class="">Click to View</p>
            </div>
            <div class="total-purchase-due waveeffect cash-report">
               <h1>Cash Report</h1>
               <p class="">Click to View</p>
            </div>
            <div class="report-list-2">
               <h1>Terminal Report</h1>
               <form action="admin-report.php" method="post" class="sort-form">
                  <input type="date" id="report-date" name="report-date" class="app-content-headerButton" value="<?php echo $_SESSION['$selected_date'] ?? date('Y-m-d') ?>">
                  <button type="submit" class="app-content-headerButton">Print Report</button>
               </form>

               
               <div class="report-table">
                  <iframe src="../report/terminal-report.php" frameborder="0"></iframe>
               </div>
            </div>
        </div>
   </div>
   </div>
    <script src="../../app/toggle.js">
   </script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script>
      document.querySelector('.hourly-report').addEventListener('click', () => {
         window.location.href = 'admin-hourly-report.php';
      });

      document.querySelector('.sales-report').addEventListener('click', () => {
         window.location.href = 'admin-sales-report.php';
      });

      document.querySelector('.cashier-report').addEventListener('click', () => {
         window.location.href = 'admin-cashier-report.php';
      });

      document.querySelector('.product-report').addEventListener('click', () => {
         window.location.href = 'admin-product-report.php';
      });

      document.querySelector('.cash-report').addEventListener('click', () => {
         window.location.href = 'admin-cash-report.php';
      });
   </script>
</body>
</html>