<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?=$title?></title>
  <link rel="stylesheet" href='<?=base_url()?>css/basic.css' type="text/css" media="screen, projection" />    
</head>
<body>
<?php
	if ($islogged != true)
	{
?>
	<div id="login">
	<form id="login-form" action='<?=base_url().index_page().'/diary/login'?>' method="POST">
	<label for="username">K&auml;ytt&auml;j&auml;tunnus:</label>
	<input name="username" tabindex="1"" type="text">
	<label for="password">Salasana:</label>
	<input name="password" tabindex="2"" type="password">
	<input type="submit" value="Kirjaudu sis&auml;&auml;n">
	</form>
	</div>
<?php
	}
	else
	{
		echo "Tervetuloa ".$this->user_model->username()." - ". anchor('diary/logout','Kirjaudu ulos');
	}
?>

	<div id="content">
	
	<h1>Pauli Suurahon kuuluisa Kalorilaskuri</h1>
	
	<div id="nav">
	<ul>
		<li><?=anchor('diary','P&auml;iv&auml;kirja')?></li>
		<li><?=anchor('diary/foods','Ruoat')?></li>
		<li><?=anchor('diary/exercises','Liikunnat')?></li>
		<li><?=anchor('diary/stats','Tilastot')?></li>
		<li><?=anchor('diary/register','Rekister&ouml;idy')?></li>
	</ul>
	</div>
	
	<div id="pagecontent">