<?php
namespace app\controllers;

use fastphp\base\Controller;
use fastphp\db\Db;

class LoginController extends Controller
{
	/**
	 * 登录数据库
	 */
	public function index()
	{
		$this->assign('title', '登录数据库');
		$this->render();
	}

	/**
	 * 登录
	 */
	public function login()
	{
		$ip = isset($_POST['ip'])?$_POST['ip']:null;
		$port = isset($_POST['port'])?$_POST['port']:null;
		$username = isset($_POST['username'])?$_POST['username']:null;
		$password = isset($_POST['password'])?$_POST['password']:null;
		$db=new Db();
		$result=$db->obj($ip,$port,$username,$password);
		if($result)
		{
			header('Location:http://www.mymvc.com/index/dblist');
		}
	}

}