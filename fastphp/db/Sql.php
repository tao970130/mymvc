
<?php
namespace fastphp\db;

use \PDOStatment;

class Sql
{
	//数据库表名
	protected $table;

	//主键
	protected $primary = 'id';

	//pdo dindParam()绑定的参数
	private $param = array();

    // WHERE和ORDER拼装后的条件
    private $filter = '';



	/**
     * 查询条件拼接，使用方式：
     *
     * $this->where(['id = 1','and title="Web"', ...])->fetch();
     * 为防止注入，建议通过$param方式传入参数：
     * $this->where(['id = :id'], [':id' => $id])->fetch();
     *
     * @param array $where 条件
     * @return $this 当前对象
     */
    public function where($where=array(), $param=array())
    {
    	if($where)
    	{
    		$this->filter .= 'WHERE';
    		$this->filter .= implode(' ', $where);

    		$this->param = $param;
    	}

    	return $this;
    }

    /**
	 * 拼装排序条件，使用方式：
	 *
	 * $this->order(['id DESC', 'title ASC', ...])->fetch();
	 *
	 * @param array $order 排序条件
	 * @return $this
	 */
     public function order($order = array())
     {
     	if($order)
     	{
     		$this->filter .= ' ORDER BY';
     		$this->filter .= implode(',', $order);
     	}

     	return $this;
     }

     /**
      * 查询所有
      */
     public function fetchAll()
     {
     	$sql= sprintf("select * from `%s` %s",$this->table, $this->filter);
     	$sth = Db::pdo()->prepare($sql);
     	$sth = $this->formatParam($sth, $this->param);
     	$sth->execute();

     	return $sth->fetchAll();
     }

     /**
      * 查询一条
      */
     public function fetch()
     {
     	$sql = sprintf("select * from `%s` %s", $this->table, $this->filter);
     	$sth = Db::pdo()->prepare($sql);
     	$sth = $this->formatParam($sth, $this->param);
     	$sth->execute();

     	return $sth->fetch();
     }

     /**
      * 根据条件(id)删除
      */
     public function delete($id)
     {
     	$sql = sprintf("delete from `%s` where `%s` = :%s",$this->table, $this->primary, $this->primary);
     	$sth = Db::pdo()->prepare($sql);
     	$sth = $this->formatParam($sth,[$this->primary => $id]);
     	$sth->execute();

     	return $sth->rowCount();
     }

     /**
      * 新增数据
      */
     public function add($data)
     {
     	$sql = sprintf("insert to `%s` %s,",$this->table,$this->formatInsert($data));
     	$sth = Db::pdo()->prepare($sql);
     	$sth = $this->formatParam($sth,$data);
     	$sth = $this->formatParam($sth,$this->param);
     	$sth = execute();

     	return $sth->rowCount();
     }

     /**
      * 修改数据
      */
     public function update($data)
     {
     	$sql = sprintf("update `%s` set %s %s",$this->table,$this->formatUpdate($data),$tis->filter);

     	$sth = Db::pdo()->prepare($sql);
     	$sth = $this->formatParam($sth,$data);
     	$sth = $this->formatParam($sth,$this->param);
     	$sth->execute();
     }

     /**
     * 占位符绑定具体的变量值
     * @param PDOStatement $sth 要绑定的PDOStatement对象
     * @param array $params 参数，有三种类型：
     * 1）如果SQL语句用问号?占位符，那么$params应该为
     *    [$a, $b, $c]
     * 2）如果SQL语句用冒号:占位符，那么$params应该为
     *    ['a' => $a, 'b' => $b, 'c' => $c]
     *    或者
     *    [':a' => $a, ':b' => $b, ':c' => $c]
     *
     * @return PDOStatement
     */
    public function formatParam( $sth,$params = array())
    {
    	foreach($params as $param => &$value)
    	{
    		$param = is_int($pram) ? $param + 1 : ':' . ltrim($param,':');
    		$sth->bindParam($param, $value);
    	}
    	return $sth;
    }

    /**
     * 将数组转化成插入格式的sql语句
     */
    private function formatInsert($data)
    {
    	$fileds = array();
    	$names = array();

    	foreach($data as $key => $value)
    	{
    		$fields[] = sprintf("`%s`", $key);
    		$names[] = sprintf(":%s", $key);
    	}

    	$field = implode(',', $fields);
    	$name = implode(',', $names);

    	return sprintf("(%s) values (%s)",$field, $name);
    }

    /**
     * 将数组转化成更新格式的sql语句
     */
    private function formatUpdate($data)
    {
    	$fields = array();
    	foreach($data as $key => $value)
    	{
    		$fields[] = sprintf("`%s` = :%s",$key, $value);
    	}

    	return implode(',', $fields);
    }

}