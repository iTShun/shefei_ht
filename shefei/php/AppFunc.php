<?php


function _Hint_($desc)
{
	echo "<script language='JavaScript'>alert('" . $desc . "');</script>";
}

function _Back_Up_()
{
	echo "<script>window.location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
}

function _Goto_($url)
{
	echo "<script>window.location.href='" . $url . "';</script>";
}

function _Reload_()
{
	echo "<script>window.location.reload();</script>";
}

function _IsSet_($arr, $key)
{
	if ($arr && is_array($arr) && isset($arr[$key]))
	{
		return $arr[$key];
	}
	return null;
}

function _Array_Valid_($arr, $key = null)
{
	if($arr && is_array($arr) && count($arr) > 0)
	{ 
		if(!is_null($key) && isset($arr[ $key ]))
		{ 
			return $arr[ $key ];
		}

		return $arr;
	}

	return null;
}

function _Array_Find_($arr, $needle, $index=0, $nodes_temp=array()){ 
	global $nodes_found;
	$index++;
	foreach ($arr as $key=>$value) { 
	    $nodes_temp[$index] = $key; 
	    if (is_array($value)){    
	      _Array_Find_($arr, $needle, $index, $nodes_temp); 
	    } 
	    else if ($value === $needle){ 
	      $nodes_found[] = $nodes_temp; 
	    } 
	} 
	return $nodes_found; 
} 

?>