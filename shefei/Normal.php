<!DOCTYPE HTML>
<html>
<head>
<title>奢妃后台</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />

<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />

<!-- font-awesome icons CSS-->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons CSS-->

 <!-- side nav css file -->
 <link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
 <!-- side nav css file -->
 
 <!-- js-->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
 
<!-- Metis Menu -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
<!--//Metis Menu -->

<?php
require_once 'php/control.php';

function getCookieUsername()
{
	if(!empty($_COOKIE['username']) && check_user($_COOKIE['username']))
	{
		return $_COOKIE['username'];
	}else
	{
		return "<script>window.location.href='index.php';</script>";
	}
}
function getCookieName()
{
	if(!empty($_COOKIE['name']))
	{
		return $_COOKIE['name'];
	}else
	{
		return "<script>window.location.href='index.php';</script>";
	}
}
?>

<script>

	function getUrlParam(name) {
    	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
       	var r = window.location.search.substr(1).match(reg);
       	if (r != null) return unescape(r[2]); return null;
    }

	$(document).ready(function(){
	});

</script>

</head> 
<body class="cbp-spmenu-push" oncontextmenu=self.event.returnValue=false onselectstart="return false">
	<div class="main-content">
		<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<!--left-fixed -navigation-->
			<aside class="sidebar-left">
				<nav class="navbar navbar-inverse">
					<div class="navbar-header">
			            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
			            <span class="sr-only">Toggle navigation</span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            </button>
			            <h1><a class="navbar-brand" href="Normal.php"><span class="fa fa-area-chart"></span> 奢妃</a></h1>
			        </div>

			        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			        	<ul class="sidebar-menu">
			        		<li class="treeview">
				                <a href="#">
				                	<i class="fa fa-folder"></i>
				                	<span>资源</span>
				                	<i class="fa fa-angle-left pull-right"></i>
				                </a>
				                <ul class="treeview-menu">
				                	<li>
				                		<a id="batch_table" href="BatchTable_N.php">
				                			<i class="fa fa-angle-right"></i>
				                			<span>批次表</span>
				                		</a>
				                	</li>
				                </ul>
				            </li>
			        	</ul>
			        </div>
			        <!-- /.navbar-collapse -->
				</nav>
			</aside>
		</div>
		<!--left-fixed -navigation-->

		<!-- header-starts -->
		<div class="sticky-header header-section ">
			<div class="header-left">
				<!--toggle button start-->
				<button id="showLeftPush"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
			</div>
			<div class="header-right">
				<div class="profile_details">
					<ul>
						<li class="dropdown profile_details_drop">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">	
									<span class="prfil-img"><img src="images/2.jpg" alt=""> </span> 
									<div class="user-name">
										<p id="name"><?php echo getCookieName(); ?></p>
										<span id="username"><?php echo getCookieUsername(); ?></span>
									</div>
									<i class="fa fa-angle-down lnr"></i>
									<i class="fa fa-angle-up lnr"></i>
									<div class="clearfix"></div>
								</div>
							</a>
							<ul class="dropdown-menu drp-mnu">
								<li> <a href="index.php"><i class="fa fa-sign-out"></i> 退出</a> </li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div class="clearfix"> </div>
		</div>
		<!-- header-end -->

		<!-- main content start-->
		<div id="page-wrapper">
		
		</div>
		<!-- main content end-->
		
	   	<!--footer-->
		<div class="footer">
		   <p>技术支持：Shun</p>
		</div>
	    <!--//footer-->
	</div>

	<!-- side nav js -->
	<script src='js/SidebarNav.min.js' type='text/javascript'></script>
	<script>
      $('.sidebar-menu').SidebarNav()
    </script>
	<!-- //side nav js -->
	
	<!-- Classie --><!-- for toggle left push menu script -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			
			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>
	<!-- //Classie --><!-- //for toggle left push menu script -->
		
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	
	<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->
</body>
</html>