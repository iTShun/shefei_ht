<?php

require_once 'AppDatas.php';
require_once 'Medoo.php';
// Using Medoo namespace
use Medoo\Medoo;

/*
用户操作功能：登录核对、
*/
class UserTotal
{
	protected $database_table_name = 'user_total';

	public function __construct($database)
	{
		try {
			$sql = "CREATE TABLE IF NOT EXISTS `" . $this->tableName() . "`(
			   `u_id` bigint not null comment '用户ID' primary key AUTO_INCREMENT,
			   `g_id`  tinyint default NULL comment '用户组别',
			   `username` varchar(15) not null comment '用户名',
			   `password` varchar(32) not null comment '用户密码',
			   `name` varchar(8) comment '名字',
			   `status`  tinyint not null comment '用户身份'
			)AUTO_INCREMENT=0 ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			//InnoDB: 读少、写多。 MyISAM: 读多、写少。
			$database->query( $sql )->fetchAll();
		}
		catch (PDOException $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function tableName()
	{
		return $this->database_table_name;
	}

	protected function insert($databaseName, $username, $password, $status)
	{
		$sql = "INSERT INTO ". $databaseName . "." . $this->tableName() .
        "(username, password, status) ".
        "VALUES ".
        "('$username','$password','$status')";

		$this->db_database->query( $sql )->fetchAll();
	}

	public function login($username, $password, $app)
	{
		$data_datas = $app['db_database']->select($this->tableName(), "*", array("username" => $username));
		
		$datas = $app['hint']['isset']($data_datas, 0);
		
		if ($app['hint']['array_valid']($datas) && $datas[ 'username' ] == $username)
		{
			if ($datas[ 'password' ] == md5($password))
			{
				return $datas;
			}
			else
			{
				$app['hint']['hint']('密码错误!');
				$app['hint']['back_up']();
			}
		}
		else
		{
			$app['hint']['hint']('用户名不存在!');
			$app['hint']['back_up']();
		}
		
		return null;
	}

	public function check($username, $app)
	{
		$data_datas = $app['db_database']->select($this->tableName(), "*", array("username" => $username));
		
		$datas = $app['hint']['isset']($data_datas, 0);

		if ($app['hint']['array_valid']($datas) && $datas[ 'username' ] == $username)
		{
			return $datas;
		}

		return null;
	}

	public function checkGroup()
	{
		$data_datas = $this->db_database->select($this->tableName(), "*", array("g_id[!]" => NULL));
		
		if ($data_datas && is_array($data_datas))
		{
			return $data_datas;
		}

		return null;
	}
}



?>