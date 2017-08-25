<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

<?php

$parent_dir = __DIR__ . '/parent_dir';
$link_prefix = '/?';
$query_array = array();

if(isset($_REQUEST)){

	$query_array = get_query_array($_SERVER['QUERY_STRING']);
	$parent_dir .= '/'. implode('/', $query_array);

}

// Get element inside the current dir
$get_dir_list = get_same_dir($parent_dir);
// Create HTML for render
$html = get_folder_list($get_dir_list);
// Render HTML
echo "Path: $parent_dir";
echo $html;

function get_same_dir($get_dir_path){
	return scandir($get_dir_path);
}

function get_folder_list($get_dir){
	global $link_prefix;
	global $query_array;

	$html_link = '<li><a href="%s">%s</a></li>';
	$html_no_link = '<li>%2$s</li>';

	$add_query = '';
	if(strlen($_SERVER['QUERY_STRING']) > 1){
		$add_query = decode_server_var($_SERVER['QUERY_STRING']) . '&';

	}

	$html = "<ul style='list-style:none;'>";
	foreach ($get_dir as $key => $dir) {
		if($dir == '.' || (!$query_array && $dir == '..') ) continue;
		if($dir == '..'){
			// shift query array
			$prepare_url = implode('&', array_slice($query_array, 0,-1) );
			$html .= prepare_link_url($link_prefix, $prepare_url, '', $dir);
		}else{
			$html .= prepare_link_url($link_prefix, $add_query, $dir, $dir);
		}
	}
	$html .= "</ul>";

	return $html;
}

function get_query_array($query_str){
	return explode('&', decode_server_var($query_str) );
}

function decode_server_var($str){
	return urldecode($str);
}

function prepare_link_url($link_prefix, $add_query, $dir_postfix, $dir){

	$html_link = '<li><a href="%s">%s</a></li>';
	$html_no_link = '<li>%2$s</li>';

	$html = preg_match('/\d+/', $dir)?$html_no_link:$html_link;

	return sprintf($html, $link_prefix . $add_query . $dir_postfix, $dir);
}

?>
	
</body>
</html>