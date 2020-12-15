<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="imagetoolbar" content="no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $siteTitle?></title>
	<!--link rel="stylesheet" href="<?php echo base_url('/_static/css/bootstrap.css?ver=20200725'); ?>"-->
	<link rel="stylesheet" href="<?php echo base_url('/_static/css/default_smart.css?ver=20200725'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('/_static/css/form.css?ver=20200725'); ?>">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<script src="<?php echo base_url('/_static/js/jquery-1.12.4.min.js'); ?>"></script>
	<script src="<?php echo base_url('/_static/js/common.js'); ?>"></script>
</head>
<body style="background:#fff;">

<style type="text/css">
#m_content{}
#m_content .mheader{ background:#fd6b6b; font-size: 1.3em; text-align: center; overflow:hidden; line-height:51px; }
#m_content .mheader .left{float:left; line-height:51px; padding:0 12px;}
#m_content .mheader .right{float:right; line-height:51px; padding:0 12px;}

#m_menu .menu_body{}
#m_menu .menu_body a{display:block; padding:15px 0 15px 15px; font-size:14px; border-bottom:1px solid #ddd;}

</style>


<div id="m_content">
	<div id="m_back" style="background-color:rgba(0,0,0,.7); position:fixed; top:0; left:0; height:100%; width:100%; display:none; z-index:20;">
		<span id="mback_close" class="left material-icons" style="color:#fff; font-size:32px; margin:10px;">close</span>
	</div>
	<div id="m_menu" style="position:fixed; top:0; right:-200px; width:200px; height:100%; background:#f8f8f8; z-index:30;">
		<div class="menu_header"></div>
		<div class="menu_body">
			<a href="<?php echo base_url('mobile/m1')?>">일일작업일지</a>
			<a href="<?php echo base_url('mobile/m2')?>">생산현황판</a>
			<a href="<?php echo base_url('mobile/m3')?>">계획대비실적</a>
			<a href="<?php echo base_url('mobile/m4')?>">납기지연예상내역</a>
		</div>
		<div class="menu_footer"></div>
	</div>

