<?php

require_once 'AppDatas.php';
require_once 'Medoo.php';
require_once 'UserTotal.php';
require_once 'PHPExcel.php';
require_once 'PHPExcel/IOFactory.php';

// Using Medoo namespace
use Medoo\Medoo;

date_default_timezone_set('PRC');
date_default_timezone_set('Asia/Shanghai');

$app['user_total'] = new UserTotal($app['db_database']);

$c_id = $app['hint']['isset']($_GET, 'c_id');
$u_id = $app['hint']['isset']($_GET, 'u_id');
$addr = $app['hint']['isset']($_POST, 'addr');

if (is_null($c_id))
{
	$c_id = $app['hint']['isset']($_POST, 'c_id');
	
}

function check_user($username, $status = 'Normal')
{
	global $app;
	if ($username)
	{
		$datas = $app['user_total']->check($username, $app);
		
		if ($app['hint']['isset']($datas, 'username'))
		{
			$admin = ($datas[ 'status' ] == '1');
			if ($status == 'Admin' && !$admin)
				return false;
			
			return true;
		}
	}

	return false;
}

function check_data($table, $condition)
{
	global $app;
	$datas = $app['db_database']->select($table, '*', $condition);
	if ($datas && is_array($datas))
		return true;
	
	return false;
}

function total_table_status_convert($status)
{
	switch ($status) {
		case '0':
			return '待开发';
			break;
		
		case '1':
			return '开发中';
			break;

		case '2':
			return '已开发';
			break;

		case '3':
			return '废弃';
			break;
	}
	return '0';
}

function batch_table_status_convert($status)
{
	switch ($status) {
		case '0':
			return '<select name="status_%u_id%" class="form-control2" onchange="updateField(this)">
										<option value="0">待开发</option>
										<option value="1">开发中</option>
										<option value="2">已开发</option>
										<option value="3">废弃</option>
									</select>';
			break;
		
		case '1':
			return '<select name="status_%u_id%" class="form-control2" onchange="updateField(this)">
										<option value="1">开发中</option>
										<option value="2">已开发</option>
										<option value="3">废弃</option>
										<option value="0">待开发</option>
									</select>';
			break;

		case '2':
			return '<select name="status_%u_id%" class="form-control2" onchange="updateField(this)">
										<option value="2">已开发</option>
										<option value="3">废弃</option>
										<option value="0">待开发</option>
										<option value="1">开发中</option>
									</select>';
			break;

		case '3':
			return '<select name="status_%u_id%" class="form-control2" onchange="updateField(this)">
										<option value="3">废弃</option>
										<option value="0">待开发</option>
										<option value="1">开发中</option>
										<option value="2">已开发</option>
									</select>';
			break;
	}
	return '';
}

function pagination($curPage, $type, $postion, $pageMax, $batchGroup = -1)
{
	global $app;

	$pageHtml = '<ul class="pagination pagination-sm">';
	
	$temp = '<li class="%status%" onclick="page_table(%page%, ' . $type . ', ' . $batchGroup . ');"><a href="%pos%">%sign%</a></li>';
	$ec = array("%status%", "%page%", "%sign%", "%pos%");

	if ($pageMax == 1)
	{
		$pageHtml = $pageHtml . '<li class="disabled"><a>«</a></li>';

		$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion), $temp);

		$pageHtml = $pageHtml . '<li class="disabled"><a>»</a></li>';
	}
	else if ($pageMax == 2)
	{
		if ($curPage == 1)
		{
			$pageHtml = $pageHtml . '<li class="disabled"><a>«</a></li>';

			$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, $curPage + 1, $postion), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, "»", $postion), $temp);
		}
		else
		{
			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, "«", $postion), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, $curPage - 1, $postion), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion), $temp);

			$pageHtml = $pageHtml . '<li class="disabled"><a>»</a></li>';
		}
	}
	else if ($pageMax >= 3)
	{
		if ($curPage == 1)
		{
			$pageHtml = $pageHtml . '<li class="disabled"><a>«</a></li>';

			$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, $curPage + 1, $postion), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 2, $curPage + 2, $postion), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, "»", $postion), $temp);
		}
		else
		{
			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, "«", $postion), $temp);

			if ($curPage == $pageMax)
			{
				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 2, $curPage - 2, $postion), $temp);

				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, $curPage - 1, $postion), $temp);
				$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion), $temp);
				$pageHtml = $pageHtml . '<li class="disabled"><a>»</a></li>';
			}
			else
			{
				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, $curPage - 1, $postion), $temp);
				
				$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion), $temp);
				
				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, $curPage + 1, $postion), $temp);
			
				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, "»", $postion), $temp);
			}
		}
	}

	$pageHtml = $pageHtml . ' </ul>';

	return $pageHtml;
}


switch ($c_id) 
{
	case 'logining':
		{
			if (isset($_POST['username']) && isset($_POST['password']))
			{
				$datas = $app['user_total']->login($_POST['username'], $_POST['password'], $app);
				if ($app['hint']['array_valid']($datas))
				{
					if (isset($_POST['checkbox']))
					{
						session_start();
						setcookie('username',$datas[ 'username' ],time()+3600*24*7,"/");
						setcookie('password',$_POST['password'],time()+3600*24*7,"/");
						setcookie('name',$datas[ 'name' ],time()+3600*24*7,"/");
					}
					else
					{
						session_start();
						setcookie('username',$datas[ 'username' ],time()+3600*24*7,"/");
						setCookie('password','',time()-10);
						setcookie('name',$datas[ 'name' ],time()+3600*24*7,"/");
					}
					
					if ($datas[ 'status' ] == '1')
					{
						$app['hint']['goto']('../Admin.php');
					}
					else
					{
						$app['hint']['goto']('../Normal.php');
					}
				}
				else
					$app['hint']['back_up']();
			}
			else
				$app['hint']['back_up']();
		}
		break;

	case 'totaltableadd':
		{
			$tableName = "total_table";
			$sql = "CREATE TABLE IF NOT EXISTS `" . $tableName . "`(
					   `u_id` bigint not null comment 'ID' primary key AUTO_INCREMENT,
					   `name` varchar(8) comment '名字',
					   `phone` varchar(20) not null comment '电话',
					   `addr` varchar(80) comment '地址',
					   `wechat` varchar(30) comment '微信号',
					   `status`  tinyint not null comment '状态',
					   `desc` varchar(32) comment '描述',
					   `source` varchar(8) comment '来源'
					)AUTO_INCREMENT=0 ENGINE=MyISAM DEFAULT CHARSET=utf8;";
					//InnoDB: 读少、写多。 MyISAM: 读多、写少。
			$app['db_database']->query( $sql )->fetchAll();

			$data_datas = $app['db_database']->select($tableName, '*');
			$nums = 0;
			if (is_array($data_datas))
				$nums = count($data_datas);

			if (isset($_FILES["file"]) && isset($_FILES["file"]["name"]))
			{
				$path = "../upload/";
				$fileName = pathinfo($_FILES["file"]["name"], PATHINFO_BASENAME);
				$extension = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
				$source = $app['hint']['isset']($_POST, 'table_source') == null ? '' : $_POST['table_source'];

				$filePath = $path . $_FILES["file"]["name"];

				$objReader = null;
				$objPHPExcel = null;

				if($extension == 'xlsx')
				{
				    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
				    $objPHPExcel = $objReader->load($filePath,'utf-8');
				}elseif($extension == 'xls')
				{
				    $objReader = PHPExcel_IOFactory::createReader('Excel5');
				    $objPHPExcel = $objReader->load($filePath,'utf-8');
				}

				$sheet = $objPHPExcel->getSheet(0);
				$highestRow = $sheet->getHighestRow(); // 取得总行数
				$highestColumn = $sheet->getHighestColumn(); // 取得总列数

				$bWechat = isset($_POST['wechat']) ? $_POST['wechat'] : null;

				for($j=1;$j<=$highestRow;$j++) 
				{
				    $str = '';
				    for ($k = 'A'; $k <= $highestColumn; $k++) 
				    {
				        $str .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue() . '\\';//读取单元格
				    }
				    $strs = explode("\\", $str);
				    $count = count($strs);

				    switch ($count) 
				    {
				    	case 1:
				    	{
				    		if ($bWechat)
				    		{
				    			if (check_data($tableName, array("wechat" => $strs[ 2 ])))
				    				break;

				    			$app['db_database']->insert($tableName, array(
									"wechat" => $strs[ 2 ],
									"status" => 0,
									"source" => $source
								));
				    		}
				    		else
				    		{
				    			if (check_data($tableName, array("phone" => $strs[ 1 ])))
				    				break;

				    			$app['db_database']->insert($tableName, array(
									"phone" => $strs[ 1 ],
									"status" => 0,
									"source" => $source
								));
				    		}
				    		
				    	}
				    		
				    		break;

				    	case 2:
				    	{
				    		if ($bWechat)
				    		{
				    			if (check_data($tableName, array("wechat" => $strs[ 2 ])))
				    				break;

				    			$app['db_database']->insert($tableName, array(
									"name" => $strs[ 0 ],
									"wechat" => $strs[ 2 ],
									"status" => 0,
									"source" => $source
								));
				    		}
				    		else
				    		{
				    			if (check_data($tableName, array("phone" => $strs[ 1 ])))
				    				break;

				    			$app['db_database']->insert($tableName, array(
									"name" => $strs[ 0 ],
									"phone" => $strs[ 1 ],
									"status" => 0,
									"source" => $source
								));
				    		}
				    	}
				    		break;

				    	case 3:
				    	{
				    		if (check_data($tableName, array("phone" => $strs[ 1 ], "wechat" => $strs[ 2 ])))
				    			break;

				    		$app['db_database']->insert($tableName, array(
								"name" => $strs[ 0 ],
								"phone" => $strs[ 1 ],
								"wechat" => $strs[ 2 ],
								"status" => 0,
								"source" => $source
							));
				    	}
				    		break;
				    	
				    	default:
				    	{
				    		if (check_data($tableName, array("phone" => $strs[ 1 ], "wechat" => $strs[ 2 ])))
				    			break;

				    		$app['db_database']->insert($tableName, array(
								"name" => $strs[ 0 ],
								"phone" => $strs[ 1 ],
								"wechat" => $strs[ 2 ],
								"addr" => $strs[ 3 ],
								"status" => 0,
								"source" => $source
							));
				    	}
				    		break;
				    }
				}

				$temp = '%number%-%source%.%ext%';
				$ec = array('%number%', '%source%', '%ext%');

				$newFileName = iconv('UTF-8', 'GB18030', str_replace($ec, array($nums + 1, $source, $extension), $temp));

				rename($path . $_FILES["file"]["name"], $path . $newFileName);

				$app['hint']['hint']('添加成功!');
				$app['hint']['back_up']();
			}
			else
			{
				$app['hint']['hint']('错误代码101');
			}
		}
		break;

	case 'gettotaltable':
		{
			if (isset($_POST['u_id']) || isset($_POST['name']) || isset($_POST['phone']))
			{
				$tableName = "total_table";
				$table_show_num = $app['table_show_count'];
				$bBatch = isset($_POST['batch']);

				$index = 0;
				$u_name = null;
				$u_phone = null;

				if (isset($_POST[ 'u_id' ]))
				{
					$index = $_POST[ 'u_id' ] - 1;
				}

				if (isset($_POST[ 'name' ]))
				{
					$u_name = $_POST[ 'name' ];
				}

				if (isset($_POST[ 'phone' ]))
				{
					$u_phone = $_POST[ 'phone' ];
				}

				$data_datas = $app['db_database']->select($tableName, '*');

				if ($u_phone)
				{
					$datas = $app['db_database']->select($tableName, '*', array("phone" => $u_phone));
					
					if ($datas && is_array($datas))
					{
						$index = $datas[0]['u_id'] - 1;
					}
				}
				else if ($u_name)
				{
					$datas = $app['db_database']->select($tableName, '*', array("name" => $u_name));
					
					if ($datas && is_array($datas))
					{
						$index = $datas[0]['u_id'] - 1;
					}
				}

				$dbNum = count($data_datas);
				$app['total_table_size'] = $dbNum;
				if ($index < 0 || $index > $dbNum)
					$index = 0;

				$resultDatas = array();
				$resultDatas['html'] = '';
				$resultDatas['page'] = '';

				$temp = '<td>%u_id%</td> <td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_addr%</td> <td>%u_desc%</td> <td>%u_source%</td>';
				$ec = array('%u_id%', '%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_addr%', '%u_desc%', '%u_source%');

				if ($bBatch)
				{
					$temp = '<td>%u_id%</td> <td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_desc%</td> <td>%u_source%</td> <td>%c_name%</td>';
					$ec = array('%u_id%', '%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_desc%', '%u_source%', '%c_name%');
				}
				
				for ($i=$index, $j=0; $j < $table_show_num; $i++, $j++) 
				{
					if (isset($data_datas[$i]) && is_array($data_datas[$i]))
					{
						$resultDatas['html'] = $resultDatas['html'] . '<tr>';

						$datas = $data_datas[$i];
						if ($bBatch)
						{
							if ($datas['status'] == 0)
							{
								$c_name = '<input type="checkbox" name="' . $datas['u_id'] . '">&nbsp;添加';
								$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], total_table_status_convert($datas['status']), $datas['desc'], $datas['source'], $c_name), $temp);
							}
							else
							{
								$c_name = '已添加';

								$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], total_table_status_convert($datas['status']), $datas['desc'], $datas['source'], $c_name), $temp);
							}
						}
						else
						{
							$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], total_table_status_convert($datas['status']), $datas['addr'], $datas['desc'], $datas['source']), $temp);
						}

						$resultDatas['html'] = $resultDatas['html'] . '</tr> ';
					}
				}

				$pageIndex = ($index / $table_show_num + 1) | 0;
				if ($pageIndex <= 0)
					$pageIndex = 1;

				$pos = isset($_POST['pos']) ? $_POST['pos'] : '';
				$pageMax = ceil($app['total_table_size'] / $app['table_show_count']);
				
				$resultDatas['page'] = pagination($pageIndex, 1, $pos, $pageMax);

				echo json_encode($resultDatas);
			}

			/*
				if (isset($_POST['u_id']) || isset($_POST['name']) || isset($_POST['phone']))
			{
				$tableName = "total_table";
				$table_show_num = $app['table_show_count'];
				$bBatch = isset($_POST['batch']);

				$index = 0;
				$u_name = null;
				$u_phone = null;

				if (isset($_POST[ 'u_id' ]))
				{
					$index = $_POST[ 'u_id' ] - 1;
				}

				if (isset($_POST[ 'name' ]))
				{
					$u_name = $_POST[ 'name' ];
				}

				if (isset($_POST[ 'phone' ]))
				{
					$u_phone = $_POST[ 'phone' ];
				}

				$data_datas = null;

				if ($bBatch)
					$data_datas = $app['db_database']->select($tableName, '*', array("status" => 0));
				else
					$data_datas = $app['db_database']->select($tableName, '*');

				if ($u_phone)
				{
					$datas = $app['db_database']->select($tableName, '*', array("phone" => $u_phone));
					
					if ($datas && is_array($datas))
					{
						$index = $datas[0]['u_id'] - 1;
					}
				}
				else if ($u_name)
				{
					$datas = $app['db_database']->select($tableName, '*', array("name" => $u_name));
					
					if ($datas && is_array($datas))
					{
						$index = $datas[0]['u_id'] - 1;
					}
				}

				$dbNum = count($data_datas);
				$app['total_table_size'] = $dbNum;
				if ($index < 0 || $index > $dbNum)
					$index = 0;

				$resultDatas = array();
				$resultDatas['html'] = '';
				$resultDatas['page'] = '';

				$temp = '<td>%u_id%</td> <td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_addr%</td> <td>%u_desc%</td> <td>%u_source%</td>';
				$ec = array('%u_id%', '%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_addr%', '%u_desc%', '%u_source%');

				if ($bBatch)
				{
					$temp = '<td>%u_id%</td> <td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_desc%</td> <td>%u_source%</td> <td> <input type="checkbox" name="%c_name%">&nbsp;添加 </td>';
					$ec = array('%u_id%', '%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_desc%', '%u_source%', '%c_name%');
				}
				
				for ($i=$index, $j=0; $j < $table_show_num; $i++, $j++) 
				{
					if (isset($data_datas[$i]) && is_array($data_datas[$i]))
					{
						$resultDatas['html'] = $resultDatas['html'] . '<tr>';

						$datas = $data_datas[$i];
						if ($bBatch)
						{
							$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], total_table_status_convert($datas['status']), $datas['desc'], $datas['source'], $datas['u_id']), $temp);
						}
						else
						{
							$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], total_table_status_convert($datas['status']), $datas['addr'], $datas['desc'], $datas['source']), $temp);
						}

						$resultDatas['html'] = $resultDatas['html'] . '</tr> ';
					}
				}

				$pageIndex = ($index / $table_show_num + 1) | 0;
				if ($pageIndex <= 0)
					$pageIndex = 1;

				$pos = isset($_POST['pos']) ? $_POST['pos'] : '';
				$pageMax = ceil($app['total_table_size'] / $app['table_show_count']);
				
				$resultDatas['page'] = pagination($pageIndex, 1, $pos, $pageMax);

				echo json_encode($resultDatas);
			}
			*/
		}
		break;

	case 'batchtableadd':
		{
			if (isset($_POST['group_id']) && $_POST['group_id'] != '')
			{
				$tableName = 'batch_table';
				$groupID = $_POST['group_id'];
				$sql = "CREATE TABLE IF NOT EXISTS `" . $tableName . "`(
					   `u_id` bigint not null comment 'ID' primary key,
					   `name` varchar(8) comment '名字',
					   `phone` varchar(20) not null comment '电话',
					   `wechat` varchar(30) comment '微信号',
					   `status`  tinyint not null comment '状态',
					   `desc` varchar(32) comment '描述',
					   `source` varchar(8) comment '来源',
					   `g_id`  tinyint not null comment '组别',
					   `time` datetime comment '时间'
					)AUTO_INCREMENT=0 ENGINE=MyISAM DEFAULT CHARSET=utf8;";
					//InnoDB: 读少、写多。 MyISAM: 读多、写少。
				$app['db_database']->query( $sql )->fetchAll();

				$user_datas = $app['db_database']->select('user_total', '*', array("g_id" => $groupID));

				if ($user_datas && is_array($user_datas))
				{
					$time = date("Y-m-d H:i:s");
					foreach ($_POST as $key=>$value) 
					{
						if ($key != 'group_id')
						{
							$datas = $app['db_database']->select('total_table', '*', array("u_id" => $key));
							if ($datas && is_array($datas))
							{
								//if (!check_data($tableName, array("u_id" => $key)))
								//{
									$app['db_database']->update('total_table', array("status" => 1), array("u_id" => $key));
									$app['db_database']->insert($tableName, array(
										'u_id' => $datas[0]['u_id'],
										'name' => $datas[0]['name'],
										'phone' => $datas[0]['phone'],
										'wechat' => $datas[0]['wechat'],
										'status' => 1,
										'desc' => $datas[0]['desc'],
										'source' => $datas[0]['source'],
										'g_id' => $groupID,
										'time' => $time
									));
								//}
							}
						}
					}
					$app['hint']['hint']('发布成功!');
					$app['hint']['back_up']();
				}
			}
			else
			{
				$app['hint']['hint']('请输入组别ID!');
				$app['hint']['back_up']();
			}
		}
		break;

	case 'getbatchtable':
		{
			$tableName = "batch_table";
			$table_show_num = $app['table_show_count'];
			$groupID = 0;
			$day = 0;
			$index = 0;
			$new_flag = 0;

			$admin = check_user($_COOKIE['username'], 'Admin');
			
			if (isset($_POST[ 'g_id' ]))
			{
				$groupID = $_POST[ 'g_id' ];
			}
			else if (!$admin)
			{
				$user_datas = $app['db_database']->select('user_total', '*', array("username" => $_COOKIE['username']));
				
				if ($user_datas && is_array($user_datas))
				{
					$groupID = $user_datas[0]['g_id'];
				}
			}

			if (isset($_POST[ 'day' ]))
			{
				$day = $_POST[ 'day' ];
			}

			if (isset($_POST[ 'index' ]))
			{
				$index = $_POST[ 'index' ];
			}

			if (isset($_POST[ 'new' ]))
			{
				$new_flag = $_POST[ 'new' ];
			}

			$data_datas = null;
			if ($groupID == -1)
				$data_datas = $app['db_database']->select($tableName, '*');
			else
			{
				if ($admin)
					$data_datas = $app['db_database']->select($tableName, '*', array("g_id" => $groupID));
				else
				{
					if ($day)
					{
						if ($day == -1)
							$data_datas = $app['db_database']->select($tableName, '*', array("g_id" => $groupID, "status" => 1));
						else
						{
							$time = date("Y-m-");
							if ($day < 10)
								$time = $time . '0' . $day;
							else
								$time = $time . $day;

							$timeSmall = $time . ' 00:00:00';
							$timeMax = $time . ' 23:59:59';
							
							$data_datas = $app['db_database']->select($tableName, '*', array("g_id" => $groupID, "status" => 1, "time[>=]" => $timeSmall, "time[<=]" => $timeMax));
						}
					}
					else if ($new_flag)
					{
						$data_datas = $app['db_database']->select($tableName, '*', array("g_id" => $groupID, "status" => 1, 'new_flag' => 0));
					}
				}
			}

			$dbNum = count($data_datas);

			$resultDatas = array();
			$resultDatas['html'] = '';
			$resultDatas['page'] = '';

			$temp = '<td>%u_gid%</td> <td>%u_id%</td> <td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_desc%</td> <td>%u_source%</td> <td>%u_time%</td>';
			$ec = array('%u_gid%', '%u_id%', '%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_desc%', '%u_source%', '%u_time%');

			if (!$admin)
			{
				if ($new_flag)
				{
					$temp = '<td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_desc%</td> <td>%u_time%</td>';
					$ec = array('%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_desc%', '%u_time%');
				}
				else
				{
					$temp = '<td>%u_name%</td> <td>%u_phone%</td> <td><input type="text" name="wechat_%u_id%"  value="%u_wechat%" onchange="updateField(this)" placeholder="微信号"/></td> <td>%u_status%</td> <td><input type="text" name="desc_%u_id%"  value="%u_desc%" onchange="updateField(this)" placeholder="描述"/></td> <td>%u_time%</td>';
					$ec = array('%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_desc%', '%u_time%', '%u_id%');
				}
			}

			for ($i=$index, $j=0; $j < $table_show_num; $i++, $j++)
			{
				if (isset($data_datas[$i]) && is_array($data_datas[$i]))
				{
					$resultDatas['html'] = $resultDatas['html'] . '<tr>';

					$datas = $data_datas[$i];
					if ($admin)
					{
						$user_datas = $app['db_database']->select('user_total', '*', array("g_id" => $datas['g_id']));
						$name = '';
						if ($user_datas && is_array($user_datas))
							$name = '-' . $user_datas[0]['name'];

						$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['g_id'] . $name, $datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], total_table_status_convert($datas['status']), $datas['desc'], $datas['source'], $datas['time']), $temp);
					}
					else
					{
						$times = explode(" ", $datas['time']);
						if ($new_flag && $datas['new_flag'] == '0')
						{
							$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['name'], $datas['phone'], $datas['wechat'], total_table_status_convert($datas['status']), $datas['desc'], $times[0]), $temp) . '<td><input type="checkbox" name="' . $datas['u_id'] . '">&nbsp;已操作</td>';
						}
						else
						{
							$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['name'], $datas['phone'], $datas['wechat'], batch_table_status_convert($datas['status']), $datas['desc'], $times[0], $datas['u_id']), $temp);
						}
					}

					$resultDatas['html'] = $resultDatas['html'] . '</tr> ';
				}
			}

			$pageIndex = ($index / $table_show_num + 1) | 0;
			if ($pageIndex <= 0)
				$pageIndex = 1;

			$pos = isset($_POST['pos']) ? $_POST['pos'] : '';
			$pageMax = ceil($dbNum / $app['table_show_count']);
			
			$resultDatas['page'] = pagination($pageIndex, 2, $pos, $pageMax, $groupID);

			echo json_encode($resultDatas);
		}
		break;

	case 'updatebatchdatas':
		{
			if (isset($_POST['update']))
			{
				$tableName = "batch_table";
				$wechat = $app['hint']['isset']($_POST['update'], 'wechat');
				$status = $app['hint']['isset']($_POST['update'], 'status');
				$desc = $app['hint']['isset']($_POST['update'], 'desc');

				$result = '';

				if ($wechat && is_array($wechat))
				{
					foreach ($wechat as $key => $value) 
					{
						$app['db_database']->update($tableName, array("wechat" => $value), array("u_id" => $key));
					}

					$result = '修改内容成功!';
				}

				if ($status && is_array($status))
				{
					foreach ($status as $key => $value) 
					{
						if ($value == '0')
						{
							$app['db_database']->delete($tableName, array("u_id" => $key));
						}
						else
						{
							$app['db_database']->update($tableName, array("status" => $value), array("u_id" => $key));
						}

						$app['db_database']->update('total_table', array("status" => $value), array("u_id" => $key));
					}

					$result = '修改内容成功!';
				}

				if ($desc && is_array($desc))
				{
					foreach ($desc as $key => $value) 
					{
						$app['db_database']->update($tableName, array("desc" => $value), array("u_id" => $key));
					}

					$result = '修改内容成功!';
				}

				echo $result;
			}
		}
		break;

	case 'batchtablenew':
		{
			$tableName = "batch_table";
			foreach ($_POST as $key=>$value) 
			{
				if ($key)
				{
					$datas = $app['db_database']->select($tableName, '*', array("u_id" => $key));
					if ($datas && is_array($datas))
					{
						$app['db_database']->update($tableName, array("new_flag" => 1), array("u_id" => $key));
					}
				}
			}
			$app['hint']['hint']('修改成功!');
			$app['hint']['back_up']();
		}
		break;
	
	default:
		# code...
		break;
}

?>