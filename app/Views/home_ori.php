<?php
  // if( !isset($_SESSION['uLoginStatus']) || ($_SESSION['uLoginStatus']) != 1)
  // {
  //   return view('login');
  //   exit(0);
  // } 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
    <!-- Twitter meta-->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@pratikborsadiya">
    <meta property="twitter:creator" content="@pratikborsadiya">
    <!-- Open Graph Meta-->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vali Admin">
    <meta property="og:title" content="Vali - Free Bootstrap 4 admin theme">
    <!-- <meta property="og:url" content="http://pratikborsadiya.in/blog/vali-admin"> -->
    <!-- <meta property="og:image" content="http://pratikborsadiya.in/blog/vali-admin/hero-social.png"> -->
    <meta property="og:description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
    <title>Vali Admin - Free Bootstrap 4 Admin Template</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/css/main.css">
    <!-- Font-icon css-->
    <!-- <link rel="stylesheet" type="text/css" href="<?php #echo base_url(); ?>/fontawesome513/css/fontawesome.min.css"> -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/fontawesome513/css/all.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
  </head>
  <body class="app sidebar-mini">
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo" href="index.html">Vali</a>
      <!-- Sidebar toggle button-->
      <!-- <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a> -->
      <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar">
        <!-- <i class='fas fa-list' style='font-size:21px;color:red'></i> -->
      </a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <li class="app-search">
          <input class="app-search__input" type="search" placeholder="Search">
          <button class="app-search__button"><i class="fas fa-search"></i></button>
        </li>

        <!--Notification Menu-->
        <li class="dropdown">
          <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications">
            <i class="fas fa-bell fa-lg"></i>
          </a>
          <ul class="app-notification dropdown-menu dropdown-menu-right">
            <li class="app-notification__title">You have 4 new notifications.</li>
            <div class="app-notification__content">
              <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fas fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                  <div>
                    <p class="app-notification__message">Lisa sent you a mail</p>
                    <p class="app-notification__meta">2 min ago</p>
                  </div></a></li>
              <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fas fa-hdd fa-stack-1x fa-inverse"></i></span></span>
                  <div>
                    <p class="app-notification__message">Mail server not working</p>
                    <p class="app-notification__meta">5 min ago</p>
                  </div></a></li>
              <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-success"></i><i class="fas fa-handshake fa-stack-1x fa-inverse"></i></span></span>
                  <div>
                    <p class="app-notification__message">Transaction complete</p>
                    <p class="app-notification__meta">2 days ago</p>
                  </div></a></li>
              <div class="app-notification__content">
                <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fas fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                    <div>
                      <p class="app-notification__message">Lisa sent you a mail</p>
                      <p class="app-notification__meta">2 min ago</p>
                    </div></a></li>
                <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-hdd-o fa-stack-1x fa-inverse"></i></span></span>
                    <div>
                      <p class="app-notification__message">Mail server not working</p>
                      <p class="app-notification__meta">5 min ago</p>
                    </div></a></li>
                <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-success"></i><i class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>
                    <div>
                      <p class="app-notification__message">Transaction complete</p>
                      <p class="app-notification__meta">2 days ago</p>
                    </div></a></li>
              </div>
            </div>
            <li class="app-notification__footer"><a href="#">See all notifications.</a></li>
          </ul>
        </li>

        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fas fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="page-user.html"><i class="fas fa-cog fa-lg"></i> Settings</a></li>
            <li><a class="dropdown-item" href="page-user.html"><i class="fas fa-user fa-lg"></i> Profile</a></li>
            <li><a class="dropdown-item" href="<?php echo base_url(); ?>/Home/logout"><i class="fas fa-sign-in-alt fa-lg"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image">
        <div>
          <p class="app-sidebar__user-name"><?php echo $_SESSION['uId'] #$userName ?></p>
          <p class="app-sidebar__user-designation"><?php echo $_SESSION['uGroup'] #$userGroup ?></p>
        </div>
      </div>
      <ul class="app-menu">
        <!-- DASBOARD -->
        <li><a class="app-menu__item active" href="<?php echo base_url() ?>/Home"><i class="app-menu__icon fas fa-th"></i><span class="app-menu__label">DASHBOARD</span></a></li>
        
        <!-- START GENERATE GROUP -->
        <?php 
          $groupMenu = $_SESSION['groupMenu'];
          $accessMenu = $_SESSION['accessMenu'];
          foreach ($groupMenu as $row) {
            $menuTitle = $row['menu_group'];
            $iconTitle = $row['icon'];
        ?>
          <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
              <!-- <i class="app-menu__icon fa fa-laptop"></i> -->
              <i class="app-menu__icon <?php echo $iconTitle ?>"></i>
              <span class="app-menu__label"><?php echo strtoupper($menuTitle) ?></span>
              <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
              
              <!-- START GENERATE MENU -->
              <?php

                // $odd = array_filter($accessMenu, function ($e) {
                //        return $e['menu_group'] == $menuTitle;
                // });

                $filter = $menuTitle;
                $odd = array_filter($accessMenu, function($element) use($filter){
                    return $element['menu_group'] == $filter;
                });

                // foreach ($accessMenu as $mAcces) 
                foreach ($odd as $mAcces) 
                {
                  $accessGroup = $mAcces['menu_group'];
                  $menuName = $mAcces['menu_name'];
                  $uri = base_url().'/'.$mAcces['menu_url'];
                  if($accessGroup == $menuTitle)
                  {
              ?>
                <li>
                  <a class="treeview-item" href="<?php echo $uri ?>">
                    <i class="icon fas fa-angle-right"></i> <?php echo $menuName ?>
                  </a>
                </li>

              <?php
                  }
                } 
              ?>
              <!-- START GENERATE MENU -->
              
              <!-- <li>
                <a class="treeview-item" href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="noopener">
                  <i class="icon fas fa-angle-right"></i> Master 2
                </a>
              </li>
              <li>
                <a class="treeview-item" href="ui-cards.html">
                  <i class="icon fas fa-angle-right"></i> Master 3
                </a>
              </li>   -->

            </ul>
          </li> 
        <?php }; ?>   
        <!-- END GENERATE GROUP -->

        <!-- MASTER MENU -->
        <!-- <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fas fa-desktop"></i>
            <span class="app-menu__label">MASTERS</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a class="treeview-item" href="bootstrap-components.html">
                <i class="icon fas fa-angle-right"></i> Master 1
              </a>
            </li>
            <li>
              <a class="treeview-item" href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="noopener">
                <i class="icon fas fa-angle-right"></i> Master 2
              </a>
            </li>
            <li>
              <a class="treeview-item" href="ui-cards.html">
                <i class="icon fas fa-angle-right"></i> Master 3
              </a>
            </li>            
          </ul>
        </li> -->

        <!-- TRANSACTION MENU -->
        <!-- <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fas fa-file-alt"></i>
            <span class="app-menu__label">TRANSACTIONS</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a class="treeview-item" href="#">
                <i class="icon fas fa-angle-right"></i> Transaction 1
              </a>
            </li>
            <li>
              <a class="treeview-item" href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="noopener">
                <i class="icon fas fa-angle-right"></i> Transaction 2
              </a>
            </li>
            <li>
              <a class="treeview-item" href="#">
                <i class="icon fas fa-angle-right"></i> Transaction 3
              </a>
            </li>            
          </ul>
        </li> -->


        <!-- ADMIN MENU -->
        <!-- <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fas fa-user-tie"></i>
            <span class="app-menu__label">ADMIN</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a class="treeview-item" href="bootstrap-components.html">
                <i class="icon fas fa-angle-right"></i> Menu
              </a>
            </li>
            <li>
              <a class="treeview-item" href="#">
                <i class="icon fas fa-angle-right"></i> User
              </a>
            </li>
            <li>
              <a class="treeview-item" href="ui-cards.html">
                <i class="icon fas fa-angle-right"></i> User Access
              </a>
            </li>            
          </ul>
        </li> -->


        <!-- TOOLS MENU -->
        <!-- <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fas fa-tools"></i>
            <span class="app-menu__label">TOOLS</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a class="treeview-item" href="bootstrap-components.html">
                <i class="icon fas fa-angle-right"></i> Tools 1
              </a>
            </li>
            <li>
              <a class="treeview-item" href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="noopener">
                <i class="icon fas fa-angle-right"></i> Tools 2
              </a>
            </li>
            <li>
              <a class="treeview-item" href="ui-cards.html">
                <i class="icon fas fa-angle-right"></i> Tools 3
              </a>
            </li>            
          </ul>
        </li> -->

        <!-- HELP MENU -->
        <!-- <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fas fa-question"></i>
            <span class="app-menu__label">HELP</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a class="treeview-item" href="bootstrap-components.html">
                <i class="icon fas fa-angle-right"></i> Tools 1
              </a>
            </li>
            <li>
              <a class="treeview-item" href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="noopener">
                <i class="icon fas fa-angle-right"></i> Tools 2
              </a>
            </li>
            <li>
              <a class="treeview-item" href="ui-cards.html">
                <i class="icon fas fa-angle-right"></i> Tools 3
              </a>
            </li>            
          </ul>
        </li> -->


        <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-laptop"></i>
            <span class="app-menu__label">UI Elements</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li>
              <a class="treeview-item" href="bootstrap-components.html">
                <i class="icon fas fa-angle-right"></i> Bootstrap Elements
              </a>
            </li>
            <li>
              <a class="treeview-item" href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="noopener">
                <i class="icon fas fa-angle-right"></i> Font Icons
              </a>
            </li>
            <li>
              <a class="treeview-item" href="ui-cards.html">
                <i class="icon fas fa-angle-right"></i> Cards
              </a>
            </li>
            <li>
              <a class="treeview-item" href="widgets.html">
                <i class="icon fas fa-angle-right"></i> Widgets
              </a>
            </li>
          </ul>
        </li>

        <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon fas fa-chart-pie"></i><span class="app-menu__label">Charts</span></a></li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-edit"></i><span class="app-menu__label">Forms</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="form-components.html"><i class="icon fas fa-angle-right"></i> Form Components</a></li>
            <li><a class="treeview-item" href="form-custom.html"><i class="icon fas fa-angle-right"></i> Custom Components</a></li>
            <li><a class="treeview-item" href="form-samples.html"><i class="icon fas fa-angle-right"></i> Form Samples</a></li>
            <li><a class="treeview-item" href="form-notifications.html"><i class="icon fas fa-angle-right"></i> Form Notifications</a></li>
          </ul>
        </li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-th-list"></i><span class="app-menu__label">Tables</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="table-basic.html"><i class="icon fas fa-angle-right"></i> Basic Tables</a></li>
            <li><a class="treeview-item" href="table-data-table.html"><i class="icon fas fa-angle-right"></i> Data Tables</a></li>
          </ul>
        </li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-file-alt"></i><span class="app-menu__label">Pages</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="blank-page.html"><i class="icon fas fa-angle-right"></i> Blank Page</a></li>
            <li><a class="treeview-item" href="page-login.html"><i class="icon fas fa-angle-right"></i> Login Page</a></li>
            <li><a class="treeview-item" href="page-lockscreen.html"><i class="icon fas fa-angle-right"></i> Lockscreen Page</a></li>
            <li><a class="treeview-item" href="page-user.html"><i class="icon fas fa-angle-right"></i> User Page</a></li>
            <li><a class="treeview-item" href="page-invoice.html"><i class="icon fas fa-angle-right"></i> Invoice Page</a></li>
            <li><a class="treeview-item" href="page-calendar.html"><i class="icon fas fa-angle-right"></i> Calendar Page</a></li>
            <li><a class="treeview-item" href="page-mailbox.html"><i class="icon fas fa-angle-right"></i> Mailbox</a></li>
            <li><a class="treeview-item" href="page-error.html"><i class="icon fas fa-angle-right"></i> Error Page</a></li>
          </ul>
        </li>
        <li><a class="app-menu__item" href="docs.html"><i class="app-menu__icon fas fa-folder-open"></i><span class="app-menu__label">Docs</span></a></li>
      </ul>
    </aside>
    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fas fa-th"></i> Dashboard</h1>
          <p>A free and open source Bootstrap 4 admin template</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fas fa-home"></i></li>
          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        </ul>
      </div>


      <!-- START CONTENT -->
      <?php
        // $items = [1,2,3,4,5];

        // $filter = 'Admin';
        // $data = array_filter(array_column($accessMenu, 'menu_group'));
        // foreach ($data as $key => $value) {
        //   # code...
        //   if($value == $filter)
        //   {
        //     echo $value;
        //   }
        // }


      // $items = [1,2,3,4,5];
 
      // $fl = 'Admin';
      // $odd = array_filter($accessMenu, function ($e) {
      //     return $e['menu_group'] == 'Admin';
      // });

        $filter = 'Admin';
        $filteredItems = array_filter($accessMenu, function($element) use($filter){
            return $element['menu_group'] == $filter;
        });
        // $inputs = [
        //   'first'    => 'John',
        //   'last'     => 'Doe',
        //   'password' => 'secret',
        //   'email'    => ''
        // ];

        // $ak = $arrayName = array('password' => 'secret');
         
        // $filtered = array_filter($inputs, function ($value, $key) {
        //     return $value !== '' && $key !== $ak['password'];
        // }, ARRAY_FILTER_USE_BOTH);
        echo $url= $_SERVER['HTTP_REFERER'];
        echo '<pre>';
        // print_r($ak);
        print_r($filteredItems);
        echo '</pre>';

        // PHP function to check for even elements in an array 
        // function Even($array) 
        // { 
        //   if($array%2==0) 
        //   return TRUE; 
        //   else
        //   return FALSE; 
        // } 

        // $array = array(12, 0, 0, 18, 27, 0, 46); 
        // print_r(array_filter($array, "Even")); 

        // $dataFilter = 'Admin';
        // function even($value)
        // {
        //   return $value == 'Admin';
        // }

        // $data = array_filter(array_column($accessMenu, 'menu_group'));
        // echo '<pre>';
        // print_r(array_filter($data,"even"));
        // echo '</pre>';



        // $data = array_filter(array_column($accessMenu, 'menu_group'));

        // print_r($data);
        // echo view($actView);
        // echo $actView;
        // $activeMenu = $_POST['activeMenu'];
        // echo $activeMenu;

        // if($activeMenu == 'Users'){
        //    echo 'Hello Man..';
        // } 
        // switch ($activeMenu) {
        //   case 'Users':
        //     echo 'Hello Boy';
        //     break;
          
        //   default:
        //     break;
        // }
        // echo 'Hello';
            // echo $activeMenu;
      ?>
        
      

      <!-- <div class="row">
        <div class="col-md-6 col-lg-3">
          <div class="widget-small primary coloured-icon"><i class="icon fas fa-users"></i>
            <div class="info">
              <h4>Users</h4>
              <p><b>5</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small info coloured-icon"><i class="icon fas fa-thumbs-up"></i>
            <div class="info">
              <h4>Likes</h4>
              <p><b>25</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small warning coloured-icon"><i class="icon far fa-copy"></i>
            <div class="info">
              <h4>Uploades</h4>
              <p><b>10</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small danger coloured-icon"><i class="icon fas fa-star"></i>
            <div class="info">
              <h4>Stars</h4>
              <p><b>500</b></p>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Monthly Sales</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Support Requests</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
            </div>
          </div>
        </div>
      </div> -->
      <!-- END CONTENT -->
    
    </main>
    <!-- Essential javascripts for application to work-->
    <script src="<?php echo base_url(); ?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?php echo base_url(); ?>/assets/js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/plugins/chart.js"></script>
    <script type="text/javascript">

    $('.treeview-menu').on('click', function(){
       var currUrl = '';
       window.location.href = currUrl;
       // var currUrl = window.location.href;
       var am = $(this);       

       $.ajax({
          url : currUrl,
          method : 'POST',
          success : function(data)
          {
            window.location.href = currUrl;
            am.parent('.treeview').removeClass('is-expanded');
            am.parent('.treeview').addClass('is-expanded');     
          }
       });

       // alert(currUrl);
       // am.parents()  
       // $('.treeview-menu').parent('.treeview').removeClass('is-expanded'); 
       // $('.treeview-menu').parent('.treeview').addClass('is-expanded'); 
    });

     // function setMenu($menuName)
     // {
     //   let myUrl = "<?php #echo base_url(); ?>/User/Activity/setActiveMenu/"+$menuName;
     //   alert(myUrl);
     //   $.ajax({
     //      url     : myUrl,
     //      method  : "POST",
     //      data    : {
     //        activeMenu : $menuName
     //      },
     //      success : function(data)
     //      {
     //        alert($menuName)
     //      }
     //   })
     // }


      var data = {
      	labels: ["January", "February", "March", "April", "May"],
      	datasets: [
      		{
      			label: "My First dataset",
      			fillColor: "rgba(220,220,220,0.2)",
      			strokeColor: "rgba(220,220,220,1)",
      			pointColor: "rgba(220,220,220,1)",
      			pointStrokeColor: "#fff",
      			pointHighlightFill: "#fff",
      			pointHighlightStroke: "rgba(220,220,220,1)",
      			data: [65, 59, 80, 81, 56]
      		},
      		{
      			label: "My Second dataset",
      			fillColor: "rgba(151,187,205,0.2)",
      			strokeColor: "rgba(151,187,205,1)",
      			pointColor: "rgba(151,187,205,1)",
      			pointStrokeColor: "#fff",
      			pointHighlightFill: "#fff",
      			pointHighlightStroke: "rgba(151,187,205,1)",
      			data: [28, 48, 40, 19, 86]
      		}
      	]
      };
      var pdata = [
      	{
      		value: 300,
      		color: "#46BFBD",
      		highlight: "#5AD3D1",
      		label: "Complete"
      	},
      	{
      		value: 50,
      		color:"#F7464A",
      		highlight: "#FF5A5E",
      		label: "In-Progress"
      	}
      ]
      
      var ctxl = $("#lineChartDemo").get(0).getContext("2d");
      var lineChart = new Chart(ctxl).Line(data);
      
      var ctxp = $("#pieChartDemo").get(0).getContext("2d");
      var pieChart = new Chart(ctxp).Pie(pdata);
    </script>
    <!-- Google analytics script-->
    <!-- <script type="text/javascript">
      if(document.location.hostname == 'pratikborsadiya.in') {
      	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      	ga('create', 'UA-72504830-1', 'auto');
      	ga('send', 'pageview');
      }
    </script> -->
  
    

  </body>
</html>