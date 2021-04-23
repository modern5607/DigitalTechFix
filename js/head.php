<? 
include_once "./oracle_tns.php";
?>
<!doctype html>
<html lang="ko">

<head>
<title>장성테크 MES</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Oculux Bootstrap 4x admin is super flexible, powerful, clean &amp; modern responsive admin dashboard with unlimited possibilities.">
<meta name="author" content="GetBootstrap, design by: puffintheme.com">

<link rel="icon" href="favicon.ico" type="image/x-icon">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="assets/css/site.min.css">

</head>
<body class="theme-cyan font-montserrat">

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
        <div class="bar4"></div>
        <div class="bar5"></div>
    </div>
</div>

<!-- Theme Setting -->


<!-- Overlay For Sidebars -->
<div class="overlay"></div>

<div id="wrapper">

    <nav class="navbar top-navbar">
        <div class="container-fluid">

            <div class="navbar-left">
                <div class="navbar-btn">
                    <a href="index.html"><img src="./images/logo.png" alt="장성테크" class="img-fluid logo"></a>
                    <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
                </div>                
            </div>
            
            <div class="navbar-right">
                <div id="navbar-menu">
                    <ul class="nav navbar-nav">
                        <li><a href="javascript:void(0);" class="search_toggle icon-menu" title="Search Result"><i class="icon-magnifier"></i></a></li>                       
                    </ul>
                </div>
            </div>
        </div>
        <div class="progress-container"><div class="progress-bar" id="myBar"></div></div>
    </nav>
    
    <div class="search_div">
        <div class="card">
            <div class="body">
                <form id="navbar-search" class="navbar-form search-form" name="frm_search" method="post" >                						
                    <? 
						if($_SERVER["PHP_SELF"]=="/js/index.php")
						{
							if($_POST["start_date"]==""){
								$timestamp = strtotime("Now"); 
								$timestamp = strtotime("-1 months"); 
								$st=date("Y-m-d",$timestamp);
							} else {
								$st=$_POST["start_date"];
							}
							if($_POST["end_date"]==""){
								$et=date("Y-m-d");
							} else {
								$et=$_POST["end_date"];
							}
							if($_POST["search_word"]!="") {
								$search_word=$_POST["search_word"];
								$add_sql=" and MCCSDESC like '%$search_word%' ";
							} else {
								$add_sql="";
							}
					?>
							<div style="margin: 6px 0;">검색시작일</div> 
							<input type="text" name="start_date" class="form-control" placeholder="검색시작일" value="<?=$st?>">
							<div style="margin: 6px 0;">검색종료일</div> 
							<input type="text" name="end_date" class="form-control" placeholder="검색종료일" value="<?=$et?>">
							<div style="margin: 6px 0;">품목</div> 
							<div class="input-group mb-0">
								<input type="text" name="search_word" class="form-control" value="<?=$search_word?>" >
								<div class="input-group-append">
									<span class="input-group-text" onClick="frm_search.submit();"><i class="icon-magnifier"></i></span>
									<a href="javascript:void(0);" class="search_toggle btn btn-danger"><i class="icon-close"></i></a>
								</div>
							</div>
					<?
						} else if($_SERVER["PHP_SELF"]=="/js/index1.php")
						{							
							if($_POST["search_word"]=="") {
								$search_word="12월 물량";																
							}
					?>							
							<div style="margin: 6px 0;">검색물량</div> 
							<div class="input-group mb-0">
								<input type="text" name="search_word" class="form-control" value="<?=$search_word?>" >
								<div class="input-group-append">
									<span class="input-group-text" onClick="frm_search.submit();"><i class="icon-magnifier"></i></span>
									<a href="javascript:void(0);" class="search_toggle btn btn-danger"><i class="icon-close"></i></a>
								</div>
							</div>
					<?
						} else if($_SERVER["PHP_SELF"]=="/js/index2.php")
						{							
							if($_POST["search_word"]=="") {
								$search_word="2019";																
							}
					?>							
							<div style="margin: 6px 0;">년도</div> 
							<div class="input-group mb-0">
								<input type="text" name="search_word" class="form-control" value="<?=$search_word?>" >
								<div class="input-group-append">
									<span class="input-group-text" onClick="frm_search.submit();"><i class="icon-magnifier"></i></span>
									<a href="javascript:void(0);" class="search_toggle btn btn-danger"><i class="icon-close"></i></a>
								</div>
							</div>
					<?
						} else if($_SERVER["PHP_SELF"]=="/js/index3.php")
						{
							if($_POST["start_date"]==""){
								$timestamp = strtotime("Now"); 
								$timestamp = strtotime("-1 months"); 
								$st=date("Y-m-d",$timestamp);
							} else {
								$st=$_POST["start_date"];
							}
							if($_POST["end_date"]==""){
								$et=date("Y-m-d");
							} else {
								$et=$_POST["end_date"];
							}
							if($_POST["search_word"]!="") {
								$search_word=$_POST["search_word"];
								$add_sql=" and MCCSDESC like '%$search_word%' ";
							} else {
								$add_sql="";
							}							
					?>
							<div style="margin: 6px 0;">검색시작일</div> 
							<input type="text" name="start_date" class="form-control" placeholder="검색시작일" value="<?=$st?>">
							<div style="margin: 6px 0;">검색종료일</div> 
							<input type="text" name="end_date" class="form-control" placeholder="검색종료일" value="<?=$et?>">
							<div style="margin: 6px 0;">품목</div> 
							<div class="input-group mb-0">
								<input type="text" name="search_word" class="form-control" value="<?=$search_word?>" >
								<div class="input-group-append">
									<span class="input-group-text" onClick="frm_search.submit();"><i class="icon-magnifier"></i></span>
									<a href="javascript:void(0);" class="search_toggle btn btn-danger"><i class="icon-close"></i></a>
								</div>
							</div>					
					<?
						} else if($_SERVER["PHP_SELF"]=="/js/index4.php")
						{
										
					?>
							<div class="input-group mb-0">
								<input type="text" name="search_word" class="form-control" value="<?=$search_word?>" >
								<div class="input-group-append">
									<span class="input-group-text" onClick="frm_search.submit();"><i class="icon-magnifier"></i></span>
									<a href="javascript:void(0);" class="search_toggle btn btn-danger"><i class="icon-close"></i></a>
								</div>
							</div>					
					<?
						} else if($_SERVER["PHP_SELF"]=="/js/index5.php")
						{							
							if($_POST["start_date"]==""){
								$st=date("Y-m-d");
							} else {
								$st=$_POST["start_date"];
							}
							if($_POST["search_word"]!="") {
								$search_word=$_POST["search_word"];
								$add_sql=" and NAME like '%$search_word%' ";
							} else {
								$add_sql="";
							}							
					?>
							<div style="margin: 6px 0;">날짜</div> 
							<input type="text" name="start_date" class="form-control" placeholder="검색시작일" value="<?=$st?>">							
							<div style="margin: 6px 0;">직원명</div> 
							<div class="input-group mb-0">
								<input type="text" name="search_word" class="form-control" value="<?=$search_word?>" >
								<div class="input-group-append">
									<span class="input-group-text" onClick="frm_search.submit();"><i class="icon-magnifier"></i></span>
									<a href="javascript:void(0);" class="search_toggle btn btn-danger"><i class="icon-close"></i></a>
								</div>
							</div>					
					<? 
						}
					?>
                </form>
            </div>            
        </div>        
    </div>

    

    

    <div id="left-sidebar" class="sidebar">
        <div class="navbar-brand">
            <a href="index.html"><img src="./images/logo.png" alt="장성테크" class="img-fluid logo"><span>장성테크</span></a>
            <button type="button" class="btn-toggle-offcanvas btn btn-sm float-right"><i class="lnr lnr-menu icon-close"></i></button>
        </div>
        <div class="sidebar-scroll">
            <div class="user-account">               
                <div class="dropdown">
                    <span>관리자님 환영합니다.</span>
                </div>                
            </div>  
            <nav id="left-sidebar-nav" class="sidebar-nav">
                <ul id="main-menu" class="metismenu">
                    <li class="header">Main</li>                   
                    <li><a href="index.php"><i class="icon-speedometer"></i><span>수주현황</span></a></li>
                    <!--<li><a href="index1.php"><i class="icon-diamond"></i><span>작업진행현황</span></a></li>-->
                    <li><a href="index2.php"><i class="icon-rocket"></i><span>계획vs 생산실적</span></a></li>
                    <li><a href="index3.php"><i class="icon-badge"></i><span>납기지연예상물량</span></a></li>
                    <li><a href="index4.php"><i class="icon-cursor"></i><span>실적처리</span></a></li>
                    <li><a href="index5.php"><i class="icon-bubbles"></i><span>출근인원현황</span></a></li>                   
                </ul>
            </nav>     
        </div>
    </div>