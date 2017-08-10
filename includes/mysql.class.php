<?php

class MySQL
{  
	private $link_id = 0;  
	private $reporterror = 0;
	private $query_count = 0;
	private $query_dump = array();
	private $pdo;
	private static $instance;
	private $database='';
	private $lasterr = 0;  
	private $lasterrmsg = '';  
	private $showerr = true;  
	public static function gI() {
		if (self::$instance === null) {
		self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct() {}

	private function __clone() {}
	function GetLastError()	{
		return $this->lasterr;
	}
	function GetLastErrorMsg()	{
		return $this->lasterrmsg;
	}
	function SetLogPoint($pnt)	{
		$this->query_dump[]=array($pnt,'');
		return true;
	}

	function connect($db_host, $db_name, $db_user, $db_pass)//PDO
	{
		if ($this->link_id == 0)
		{   
			try {
 				$this->pdo = new PDO('mysql:host='.$db_host.';dbname='.$db_name, $db_user, $db_pass);
 				$this->link_id=1;
				} catch (PDOException $e) {
				header('HTTP/1.1 503 Service temporary down');
    			die ("Error!: " . $e->getMessage() . "<br/>");
				} 
			$this->link_id=1; 
			$this->database=$db_name;
			$this->charset('UTF8');	
		}
	}
	function SetErrMsgOn($on=true){
		$this->showerr=$on;
		return $this->showerr;
	}
	function charset($charset)//pdo
	{
		/*$query_id = mysql_query("SET NAMES '".$charset."'");SET CHARACTER SET
		if (!$query_id)
		{
			$this->show_error("Error SET NAMES: ".mysql_error());
		}
		*/
		$res = $this->pdo->query("SET NAMES '".$charset."'");
		if ($res===FALSE)
		{
			$this->show_error("Error selecting charset: ".$charset,$this->pdo->errorInfo());
		}
		return $res;  
	}

	function count($tbl_name, $id = '-1'){//PDO
		$result = array();
		if($id == '-1'){
			$result = $this->get_rows("select COUNT(*) as cnt from ".$tbl_name);
		}
		else{
			$res = array();
			if(is_array($id)){
				$params = $id;
				foreach($params as $id=>$value){
					$o_name[] = ''.$id.'='.$this->pdo->quote($value);
				}
				$o_name_ex = implode(' && ',$o_name);
				$result = $this->get_rows("select COUNT(*)  as cnt from ".$tbl_name." where ".$o_name_ex);
			}
			else{
				$o_name_ex =  "`id` = ".$this->pdo->quote($id);
				$result = $this->get_rows("select COUNT(id) as cnt from ".$tbl_name." where ".$o_name_ex);
			}
		}
		return isset($result[0])?$result[0]['cnt']:0;
	}
    
	function get($tbl_name, $id = '-1', $sort = '-1'){//pdo

		if($sort == -1)	{
			$sort_str = '';
		} else {
			foreach($sort as $sort_param=>$sort_n){
				$s_name[] = ''.$sort_param.' '.$sort_n;
			}
			$sort_str = " ORDER BY ".implode(', ',$s_name);
		}
		if ($id == '-1') {
			$rows = $this->get_rows("select * from ".$tbl_name.$sort_str.""); 
			return $rows;
		} else {
			$res = array();
			if (is_array($id)) {
				$params = $id;
				foreach ($params as $id=>$value){
					$o_name[] = ''.$id.'='.$this->pdo->quote($value);
                    //echo '<br>', $value;
				}
				$o_name_ex = implode(' && ',$o_name);

                //echo '<br>', "select * from ".$tbl_name." where ".$o_name_ex.$sort_str."";

				$res = $this->get_rows("select * from ".$tbl_name." where ".$o_name_ex.$sort_str."");
			} else {
				$o_name_ex =  "`id` = ".$this->pdo->quote($id);
				$res = $this->get_rows("select * from ".$tbl_name." where ".$o_name_ex.$sort_str."");
				if (count($res)>0) $res=$res[0]; else $res=array();
			}
		}
		return $res;
	}  

	function get_rows($query)//pdo
	{
		$t = 0;
		$rows = Array();
		if (defined('SQLLOG'))
			$q_start = microtime(true);
		$res=$this->pdo->query($query);
		if (defined('SQLLOG'))
				$this->query_dump[] = array($query, sprintf('%.5f', microtime(true) - $q_start));
		$this->query_count++;
		if ($res===false) {
			$this->show_error("Error get_rows: ".$query,$this->pdo->errorInfo());
		} else $rows=$res->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}
  
	function insert($tbl_name, $params){//pdo
		foreach($params as $id=>$value){
			$o_name[] = ''.$id;
		}
		$o_name_ex = implode(', ',$o_name);
		foreach($params as $id=>$value){
			$o_value[] = $this->pdo->quote(trim($value));
		}
		$o_value_ex = implode(', ',$o_value);
		$query = "INSERT INTO ".$tbl_name." (".$o_name_ex.") VALUES(".$o_value_ex.")";
		//echo $query;
		if (defined('SQLLOG'))
			$q_start = microtime(true);
		$res=$this->pdo->exec($query);
		$this->query_count++;
		if (defined('SQLLOG'))
				$this->query_dump[] = array($query, sprintf('%.5f', microtime(true) - $q_start));
		if ($res===FALSE){
			$this->show_error("Error insert: {$tbl_name} ".print_r($params,true),$this->pdo->errorInfo());
		}
		return $res;  

	}

	function clear($tbl_name){//PDO
		$query = "TRUNCATE TABLE ".$tbl_name."";
		if (defined('SQLLOG'))
			$q_start = microtime(true);
		$res=$this->pdo->exec($query);
		$this->query_count++;
		if (defined('SQLLOG'))
				$this->query_dump[] = array($query, sprintf('%.5f', microtime(true) - $q_start));
		if($res===FALSE){
			$this->show_error("Error clear: ".$tbl_name,$this->pdo->errorInfo());
		}
		return $res;
	}
  
	function update_notrim($tbl_name, $pID, $params){//PDO
		if(is_array($pID)){
			$paramsa = $pID;
			foreach($paramsa as $id=>$value){
				$o_names[] = ''.$id.'='.$this->pdo->quote($value);
			}
			$o_name_exn = implode(' && ',$o_names);
		}
		else{
			$o_name_exn = "id = ".$this->pdo->quote($pID)."";
		}
		foreach($params as $id=>$value){
			$o_name[] = ''.$id.'='.$this->pdo->quote($value);
		}
		$o_name_ex = implode(', ',$o_name);
		$query     = "UPDATE ".$tbl_name." SET ".$o_name_ex." WHERE ".$o_name_exn."";
		if (defined('SQLLOG'))
			$q_start = microtime(true);
		$res=$this->pdo->exec($query);
		$this->query_count++;
		if (defined('SQLLOG'))
				$this->query_dump[] = array($query, sprintf('%.5f', microtime(true) - $q_start));
		if($res===FALSE){
			$this->show_error("Error update: {$tbl_name} ".$query,$this->pdo->errorInfo());
		}
		return $res;
	}
	function update($tbl_name, $pID, $params){//PDO
		if(is_array($pID)){
			$paramsa = $pID;
			foreach($paramsa as $id=>$value){
				$o_names[] = ''.$id.'='.$this->pdo->quote($value);
			}
			$o_name_exn = implode(' && ',$o_names);
		}
		else{
			$o_name_exn = "id = ".$this->pdo->quote($pID)."";
		}
		foreach($params as $id=>$value){
			$o_name[] = ''.$id.'='.$this->pdo->quote(trim($value));
		}
		$o_name_ex = implode(', ',$o_name);
		$query     = "UPDATE ".$tbl_name." SET ".$o_name_ex." WHERE ".$o_name_exn."";
		if (defined('SQLLOG'))
			$q_start = microtime(true);
		$res=$this->pdo->exec($query);
		$this->query_count++;
		if (defined('SQLLOG'))
				$this->query_dump[] = array($query, sprintf('%.5f', microtime(true) - $q_start));
		if($res===FALSE){
			$this->show_error("Error update: ".$query,$this->pdo->errorInfo());
		}
		return $res;
	}

	function delete($tbl_name,$params)//pdo
	{
		foreach ($params as $id=>$value)
		{
			$o_name[] = ''.$id.'='.$this->pdo->quote($value);
		} 
		$o_name_ex = implode(' && ',$o_name);
		$query = "DELETE FROM ".$tbl_name." WHERE ".$o_name_ex."";
		if (defined('SQLLOG'))
			$q_start = microtime(true);
		$res=$this->pdo->exec($query);
		$this->query_count++;
		if (defined('SQLLOG'))
				$this->query_dump[] = array($query, sprintf('%.5f', microtime(true) - $q_start));
		if($res===FALSE){
			$this->show_error("Error delete: ".$query,$this->pdo->errorInfo());
		}
		return $res;
	}  
  
	//Функция запроса к базе
	function query($query)//pdo
	{
		if (defined('SQLLOG'))
			$q_start = microtime(true);
		$res = $this->pdo->query($query);
		$this->query_count++;
		if (defined('SQLLOG'))
				$this->query_dump[] = array($query, sprintf('%.5f', microtime(true) - $q_start));
		if ($res===false){
			$this->show_error("Ошибка в SQL запросе: ".$query,$this->pdo->errorInfo());
		}
		return $res;
	}

	function exec($query)//pdo
	{
		if (defined('SQLLOG'))
			$q_start = microtime(true);
		$res = $this->pdo->exec($query);
		$this->query_count++;
		if (defined('SQLLOG'))
				$this->query_dump[] = array($query, sprintf('%.5f', microtime(true) - $q_start));
		if ($res===false){
			$this->show_error("Ошибка в SQL запросе: ".$query,$this->pdo->errorInfo());
		}
		return $res;
	}
	
	function write_dump()
	{
		$key=array('id'=>3,'select_type'=>7,'table'=>18,'type'=>7,'possible_keys'=>20,'key'=>10,'key_len'=>5,'ref'=>10,'rows'=>5,'Extra'=>18);
		$cl=array('system'=>'#003900',
'const'=>'#334d31',
'eq_ref'=>'#334d31',
'ref'=>'#6a7144',
'fulltext'=>'#8d8647',
'ref_or_null'=>'#8d8647',
'index_merge'=>'#8d8647',
'unique_subquery'=>'#8d8647',
'index_subquery'=>'#8f653a',
'range'=>'#9e6561',
'index'=>'#a66c59',
'ALL'=>'#cf3030');
		$h='';
		foreach($key as $ky => $val){
			$h.="<th style=\"width:{$val}%\">{$ky}</th>";
		}
		$res='';
		if(SQLLOG)
		{
$res='<style type="text/css">
.sql_log table{background-color: #1C0E0E;border-spacing:0; border-collapse: collapse;width:100%}
.sql_log th,.sql_log td {color: #f2feff;text-align:left;border:1px solid #750000;font-weight:400;}
.sql_log th{font-weight:700;color: #55996c;} .sql_log a{text-decoration:none;color:#9e0105}
.sql_log div{background-color: #1C0E0E;padding:0px 10px;}
</style>';
			$res.='<div class="sql_log" style="padding:5px 0px; color:#ff0; font-weight:bold; background:#E9DD9C; font-family:Tahoma; width:100%;">';
			$res.='<a onclick="LogSH(this);return false;" id="mhtl">SQL Log</a><div style="display:none">';
			foreach($this->query_dump as $id=>$value){
				$scol='color:#00ff00;';
				if ($value[1]>0.00025) $scol='color:#09fff2;';
				$res.='<br>['.$id.']&nbsp;{'.$value[1].'}&nbsp;<span style="'.$scol.'">'.htmlspecialchars($value[0],ENT_COMPAT | ENT_XHTML,'UTF-8')."</span><br>";
				if (preg_match('/^ *select/im',$value[0])>0){
					$rows = $this->get_rows("EXPLAIN ".$value[0]);
					$dd='';
					if (empty($rows[0]['key'])) {
						$dd='<span style="color:#f00;">';
					} else{
						if (isset($rows[0]['rows']) && $rows[0]['rows']>100) $dd='<span style="color:#f0f;">';
					}
					$res.=$dd;
					$t="<table><tr>{$h}</tr>";
					foreach($rows as $id1 => $row){
						$s='';
						foreach($key as $ky => $val){
							$bg='';
							if ($ky=='key' && empty($row[$ky])) $bg='background-color:#c80000';
							if ($ky=='rows' && $row[$ky]>100) $bg='background-color:#c80000';
							if ($ky=='type' && !empty($row[$ky])) $bg="background-color:{$cl[$row[$ky]]}";
							$tmp=htmlspecialchars($row[$ky],ENT_COMPAT | ENT_XHTML,'UTF-8');
							$s.="<td style=\"width:{$val}%;{$bg}\">{$tmp}</td>";
						}
						$t.="<tr>{$s}</tr>";
					}
					$res.=$t."</table>";
					if ($dd>' ') $res.='</span>';
				}
			}
			$res.='</div></div><script>function LogSH(el){
				$($(el).next("div")[0]).toggle();
			}</script>';
		}
		return $res;
	}
	
/*	function tables(){//PDO
		$q = $this->get_rows("SHOW TABLES");
		if(!$q){
			header('HTTP/1.1 503 Service temporary down');
			die('Таблицы не найдены. ('. mysql_error().')');
		}
		$tbl = array();
		foreach($q as  $val){
			if(strpos(array_values($val)[0], TABLE_PREFIX) === 0){
				$tbl[] = array_values($val)[0];
			}
		}
		return $tbl;
	}
*/	
	function check_column($column, $table_name){//PDO
		$rows = $this->get_rows("SHOW columns FROM ".$table_name."");
		foreach($rows as $row){
			if($row['Field'] == $column) return true;
		}
		return false;
	}

	function query_count(){
		return $this->query_count;
	}

	//Функция перебора строк запроса
	function fetch_array($query_id =false, $type = PDO::FETCH_BOTH){//PDO
		$res=array();
		if (!($query_id===FALSE)){
			$res = $query_id->fetch($type);
		} 
		return $res;
	}

	//Функция очистки памяти от запроса
	function free_result($res){//PDO
		if(!($res===FALSE)){
			return $res->closeCursor();
		}
	}
  
	//Функция возвращает первую строку запроса (использует query и fetch_array)
	function query_first($query_string){//PDO
		$query_id = $this->query($query_string);
		$returnarray = $this->fetch_array($query_id);
		$this->free_result($query_id);
		return $returnarray;
	}
  
	//Функция возвращает кол-во строк в запросе
	function num_rows($res=false){//PDO
		if($res ===FALSE) return 0;
		else return $res->rowCount();
	}

	//Функция возвращает последний автоиндекс
	function insert_id()//PDO
	{
		return $this->pdo->lastInsertId();
	}
	function beginTransaction()//PDO
	{
		return $this->pdo->beginTransaction();
	}
	function commit()//PDO
	{
		return $this->pdo->commit();
	}
	function rollBack ()//PDO
	{
		return $this->pdo->rollBack ();
	}
    
	//Функция вывода ошибок при работе с базой
	function show_error($msg,$terr=array(0,0,''))
	{
		$this->lasterr=$terr[0];
		$this->lasterrmsg=$terr[2];
		if ($this->showerr){
			$message = '<div style="color:#fff">'.$msg."</div>";
			$message .= '<div style="color:#fff">PDO error: '.$terr[0].'</div>';
			$message .= '<div style="color:#fff">PDO error number: '.$terr[1].'</div>';
			$message .= '<div style="color:#fff">PDO error message: '.$terr[2].'</div>';
			$message .= '<div style="color:#fff">Дата: '.date("d.m.Y H:i:s").'</div>';
			$message .= '<div style="color:#fff">Скрипт: '.$_SERVER['REQUEST_URI'].'</div>';
			if(!empty($_SERVER['HTTP_REFERER'])){
				$message .= "Ошибка при переходе со страницы: ".$_SERVER['HTTP_REFERER']."\n";
			}
			echo '<div style="border:2px solid #bbb;background:#000;width:600px">'.$message.'</div>';
		}
	}
	
	function get_queries()
	{
		return $this->query_dump;
	}
	function get_databasename()
	{
		return $this->database;
	}

	function escape_string($string)
	{
		return $this->pdo->quote($string);  
	}
}
?>