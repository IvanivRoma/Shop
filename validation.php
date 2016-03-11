<?php
include "db_conect.php";
class Valid
{
	protected $is_Error;
	protected $Error_arr;
	
	public function __construct ()
	{
		$this->is_Error=false;
		$this->Error_arr=  array();
	}
    public function Phone_valid($user_phone)
	{
		if (!preg_match('/^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/',$user_phone)|| empty($user_phone))
		{
			$this->is_Error= true;
			array_push($this->Error_arr ,"wrong value phone example: 0631111111");
		}
	}
	public function Name_valid($user_name)
	{
		if (!preg_match('/^[a-zA-Z]+\s{1}[a-zA-Z]+$/ui',$user_name)|| empty($user_name))
		{
			$this->is_Error= true;
			array_push($this->Error_arr ,"wrong value name example: Ivaniv Roman");
		}
	}
	public function  Login_valid($user_log)
	{
		if (!preg_match('/^[a-zA-Z]+[0-9]*$/ui',$user_log)|| empty($user_log))
			{
				$this->is_Error= true;
				array_push($this->Error_arr ,"wrong value login example: Ivaniv111 or ivaniv");
			}
        $pdo= new Db_conect();
		$query= $pdo->prepare("Select COUNT(ID_Customer) from customers where Login='".$user_log."'");
		$query->execute();
		$rows=$query->fetch(PDO::FETCH_ASSOC);
		if (!empty($rows['COUNT(ID_Customer)']))
		{
			array_push($this->Error_arr ,"this login exist");
				$this->is_Error= true;
		}
	}
	
	public function Password_valid ($user_password)
	{
		if (!preg_match('/^[a-zA-Z0-9]+[0-9]*$/ui',$user_password)|| empty($user_password))
			{
				$this->is_Error= true;
				array_push($this->Error_arr ,"wrong value password example: mypassword111 or mypa1ssword");
			}
	}
	public function Address_valid($user_addess)
	{
		if ( empty($user_addess))
			{
				$this->is_Error= true;
				array_push($this->Error_arr ,"wrong value address");
			}
	}
	public function Get_Error_stat ()
	{
		return $this->is_Error;
	}
	public function Get_Error_arr ()
	{
		return $this->Error_arr;
	}
}


?>
