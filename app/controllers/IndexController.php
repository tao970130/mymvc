<?php
namespace app\controllers;

use fastphp\base\Controller;
use fastphp\db\Db;

class IndexController extends Controller
{
	/**
	 * 数据库列表
	 */
	public function dblist()
	{
		echo "数据库列表";
	}

}