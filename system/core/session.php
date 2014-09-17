<?php
class Session
{
	public static function init()
	{
		@session_start();
	}
	public static function set($key,$value = NULL)
	{
		if(is_array($key))
		{
			foreach ($key as $key => $subvalue) {
				$_SESSION[$key]=$subvalue;		
			}
		}else
		{
			$_SESSION[$key]=$value;
		}
	}
	public static function get($key)
	{
		if(isset($_SESSION[$key]))
		{
			return $_SESSION[$key];
		}else{
			return FALSE;
		}

	}
	public static function destroy()
	{
		//unset($_SESSION);
		session_destroy();
	}
}