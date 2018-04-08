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
	if(!empty($_COOKIE['username']) && check_user($_COOKIE['username'], 'Admin'))
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

	function search_totaltable(id, name, phone) 
	{
		if (id == 0 && 
			name == 0 && 
			phone == 0)
		{
			alert('请输入查询内容!');
			return false;
		}

		var arr = { c_id:'gettotaltable', pos:'#headingOne', batch:'true' };

		if (id != 0)
			arr['u_id'] = id;

		if (name != 0)
			arr['name'] = name;

		if (phone != 0)
			arr['phone'] = phone;
		
		$.post('php/control.php', arr, function(msg){
			if (msg == 0)
				alert('无查询内容!');
			else
			{
				document.getElementsByName('search_id')[0].value='';
				document.getElementsByName('search_name')[0].value='';
				document.getElementsByName('search_phone')[0].value='';
				$("#total_table_tbody").html(msg.html);
				$("#total_table_tfoot").html(msg.page);
			}
		},
		'json');
		return true;
	}

	function search_batchtable(group, index)
	{
		if (group == 0)
		{
			alert('请输入查询内容!');
			return false;
		}

		var arr = { c_id:'getbatchtable', pos:'#headingTwo', g_id:group, index:index };

		$.post('php/control.php', arr, function(msg){
			if (msg == 0)
				alert('无查询内容!');
			else
			{
				document.getElementsByName('search_groupid')[0].value='';
				$("#batch_table_tbody").html(msg.html);
				$("#batch_table_tfoot").html(msg.page);
			}
		},
		'json');
	}

	function page_table(curPage, table_type, batchGroup = -1)
	{
		if (curPage <= 0)
			curPage = 1;

		if (curPage == 1)
		{
			if (table_type == 1)
				search_totaltable(1);
			else if (table_type == 2)
			{
				search_batchtable(batchGroup, 0);
			}
		}
		else
		{
			var table_show_num = <?php echo $app['table_show_count']; ?>;

			if (table_type == 1)
			{
				var id = (curPage - 1) * table_show_num + 1;

				search_totaltable(id);
			}
			else if (table_type == 2)
			{
				var index = (curPage - 1) * table_show_num;
				search_batchtable(batchGroup, index);
			}
		}
	}

	$(document).ready(function(){
		page_table(1, 1);
		page_table(1, 2);
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
			            <h1><a class="navbar-brand" href="Admin.php"><span class="fa fa-area-chart"></span> 奢妃</a></h1>
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
				                		<a id="total_table" href="TotalTable.php" >
				                			<i class="fa fa-angle-right"></i>
				                			<span>总表</span>
				                		</a>
				                	</li>
				                	<li>
				                		<a id="batch_table" href="BatchTable.php">
				                			<i class="fa fa-angle-right"></i>
				                			<span>批次表</span>
				                		</a>
				                	</li>
				                	<li><a href="#"><i class="fa fa-angle-right"></i>已开发表</a></li>
				                	<li><a href="#"><i class="fa fa-angle-right"></i>待授权表</a></li>
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
			<div class="main-page">
				<h3 class="title1">批次表</h3>
				<div class="panel-group tool-tips widget-shadow" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							  发布
							</a>
						  </h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body">
								<form action="#">
									<input type="text" name="search_id" placeholder="ID" />
									<input type="text" name="search_name" placeholder="名字" />
									<input type="text" name="search_phone" placeholder="电话" />
									<input type="button" class="btn btn-primary" onclick="search_totaltable(search_id.value, search_name.value, search_phone.value);" value="查询" />
								</form>
								<br/>
								<form action="php/control.php?c_id=batchtableadd" method="post" enctype="multipart/form-data">
									<input type="text" name="group_id" placeholder="组别ID" />
									<br/>
									<br/>
									<table class="table table-bordered table-striped no-margin grd_tble">
										<thead>
											<tr> <th>ID</th> <th>名字</th> <th>电话</th> <th>微信号</th> <th>状态</th> <th>描述</th> <th>来源</th> <th>发布</th> </tr>
										</thead>

										<tbody id="total_table_tbody">
											
										</tbody>
									</table>
									
						            <input type="submit" class="btn btn-primary" value="提交" />
						            <div id="total_table_tfoot">
						            </div>
					        	</form>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
						  <h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								数据
							</a>
						  </h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
						  	<div class="panel-body">
						  		<form action="#">
									<input type="text" name="search_groupid" placeholder="组别ID" />
									<input type="button" class="btn btn-primary" onclick="search_batchtable(search_groupid.value, 0);" value="查询" />
								</form>
								<br/>
								<table class="table table-bordered table-striped no-margin grd_tble">
									<thead>
										<tr> <th>组别</th> <th>ID</th> <th>名字</th> <th>电话</th> <th>微信号</th> <th>状态</th> <th>描述</th> <th>来源</th> <th>时间</th> </tr>
									</thead>

									<tbody id="batch_table_tbody">
										
									</tbody>
								</table>
								<div id="batch_table_tfoot">
					            </div>
							</div>
						</div>
					</div>
				</div>
			</div>
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