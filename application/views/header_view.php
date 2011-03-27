<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?=$title?></title>
  <link rel="stylesheet" href='<?=base_url()?>css/basic.css' type="text/css" media="screen, projection" />    
</head>
<body>

	<div id="login">
	<form id="login-form">
	<label for="login_username">K&auml;ytt&auml;j&auml;tunnus:</label>
	<input name="login_username tabindex="1"" type="text">
	<label for="login_password">Salasana:</label>
	<input name="login_password tabindex="2"" type="password">
	<input type="submit" value="Kirjaudu sis&auml;&auml;n">
	</form>
	</div>
	<div id="content">
	
	<h1>Pauli Suurahon kuuluisa Kalorilaskuri</h1>
	
	<div id="nav">
	<ul>
		<li><?=anchor('diary','P&auml;iv&auml;kirja')?></li>
		<li><?=anchor('diary/foods','Ruoat')?></li>
		<li><?=anchor('diary/exercices','Liikunnat')?></li>
		<li><?=anchor('diary/register','Rekister&ouml;idy')?></li>
	</ul>
	</div>
	
	<div id="pagecontent">