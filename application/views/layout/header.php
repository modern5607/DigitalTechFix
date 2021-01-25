<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="imagetoolbar" content="no">
	<title><?php echo $siteTitle?></title>
	<!--link rel="stylesheet" href="<?php echo base_url('/_static/css/bootstrap.css?ver=20200725'); ?>"-->
	<link rel="stylesheet" href="<?php echo base_url('/_static/css/default_smart.css?ver=20200725'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('/_static/css/form.css?ver=20200725'); ?>">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<script src="<?php echo base_url('/_static/js/jquery-1.12.4.min.js'); ?>"></script>
	<script src="<?php echo base_url('/_static/js/common.js'); ?>"></script>
</head>
<body>


<div id="smart_container">

	<div class="menu_Content">
        <div class="scroll">
            <div class="mControl">
                <span class="mhide"><i class="material-icons">close</i></span>
            </div>
            <div class="mControl_show">
                <span class="mshow"><i class="material-icons">menu</i></span>
            </div>
            <div class="mcont_hd">
                <a href="<?php echo base_url('')?>">
                <img src="<?php echo base_url("_static/img/logo_dg4.png");?>" width="140">
                </a>
				<div class="login_b">
				
				<?php
				if(!empty($this->session->userdata('user_id'))){
				?>
					<a href="<?php echo base_url('register/logout')?>" class="l_btn">로그아웃</a>
				<?php
				}else{	
				?>
					<a href="<?php echo base_url('register/login')?>" class="l_btn">로그인</a>
				<?php
				}	
				?>
				</div>
            </div>
            
            <div class="mcont_bd">
            
                <ul id="menuContent">
<?php 
    if(!empty($_SESSION['user_level'])){ 
        if(!empty($_SESSION['user_level']) && $_SESSION['user_level'] >= 3){  
?>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('mdm')?>" class="menu_a <?php echo ($this->data['pos'] == "mdm")?"on":"";?>">
                        <i class="material-icons">add_business</i>
                        기준정보</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "mdm")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('mdm')?>" class="<?php echo ($this->data['subpos'] == NULL || $this->data['subpos'] == 'index')?"on":"";?>">공통코드등록</a></li>
                            <li><a href="<?php echo base_url('mdm/item')?>" class="<?php echo ($this->data['subpos'] == "item")?"on":"";?>">품목등록</a></li>
                            <li><a href="<?php echo base_url('mdm/biz')?>" class="<?php echo ($this->data['subpos'] == "biz")?"on":"";?>">업체등록</a></li>
                            <li><a href="<?php echo base_url('mdm/infoform')?>" class="<?php echo ($this->data['subpos'] == "infoform")?"on":"";?>">인사정보등록/조회</a></li>
                        </ul>
                    </li>
<?php   } 
        if(!empty($_SESSION['user_level']) && $_SESSION['user_level'] >= 3){  
?>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('bom')?>" class="menu_a <?php echo ($this->data['pos'] == "bom")?"on":"";?>">
                        <i class="material-icons">bubble_chart</i>
                        BOM</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "bom")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('bom')?>" class="<?php echo ($this->data['subpos'] == NULL)?"on":"";?>">BOM-ITEMS</a></li>
                            <li><a href="<?php echo base_url('bom/stock')?>" class="<?php echo ($this->data['subpos'] == "stock")?"on":"";?>">BOM-자재</a></li>
                            <li><a href="<?php echo base_url('bom/insert')?>" class="<?php echo ($this->data['subpos'] == "insert")?"on":"";?>">BOM등록</a></li>
                            <li><a href="<?php echo base_url('bom/level2')?>" class="<?php echo ($this->data['subpos'] == "level2")?"on":"";?>">2 Level BOM등록</a></li>
                            <li><a href="<?php echo base_url('bom/level3')?>" class="<?php echo ($this->data['subpos'] == "level3")?"on":"";?>">3 Level BOM등록</a></li>
                            <li><a href="<?php echo base_url('bom/trans')?>" class="<?php echo ($this->data['subpos'] == "trans")?"on":"";?>">기간별 자재소모현황</a></li>
                        </ul>
                    </li>
<?php   } 
        if(!empty($_SESSION['user_level']) && $_SESSION['user_level'] >= 2){  
?>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('act')?>" class="menu_a <?php echo ($this->data['pos'] == "act")?"on":"";?>" >
                        <i class="material-icons">list_alt</i>
                        주문/계획</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "act")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('act')?>" class="<?php echo ($this->data['subpos'] == NULL)?"on":"";?>">수주등록</a></li>
                            <li><a href="<?php echo base_url('act/a1')?>" class="<?php echo ($this->data['subpos'] == "a1")?"on":"";?>">수주현황</a></li>
                            <li><a href="<?php echo base_url('act/a2')?>" class="<?php echo ($this->data['subpos'] == "a2")?"on":"";?>">수주대비 진행현황</a></li>
                            <li><a href="<?php echo base_url('act/a3')?>" class="<?php echo ($this->data['subpos'] == "a3")?"on":"";?>">납기지연예상</a></li>
                        </ul>
                    </li>
<?php   } 
        if(!empty($_SESSION['user_level']) && $_SESSION['user_level'] >= 1){  
?>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('smt/s1')?>" class="menu_a <?php echo ($this->data['pos'] == "smt")?"on":"";?>" >
                        <i class="material-icons">memory</i>
                        SMT생산관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "smt")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('smt/s1')?>" class="<?php echo ($this->data['subpos'] == 's1')?"on":"";?>">생산계획 등록</a></li>
                            <li><a href="<?php echo base_url('smt/s2')?>" class="<?php echo ($this->data['subpos'] == 's2')?"on":"";?>">생산계획 조회</a></li>
                            <li><a href="<?php echo base_url('smt/s3')?>" class="<?php echo ($this->data['subpos'] == 's3')?"on":"";?>">계획대비실적현황</a></li>
                            <li><a href="<?php echo base_url('smt/s4')?>" class="<?php echo ($this->data['subpos'] == 's4')?"on":"";?>">작업지시등록</a></li>
                            <li><a href="<?php echo base_url('smt/s_print')?>" class="<?php echo ($this->data['subpos'] == 's_print')?"on":"";?>">Line별 작업지시</a></li>
                            <li><a href="<?php echo base_url('smt/barcode')?>" class="<?php echo ($this->data['subpos'] == 'barcode')?"on":"";?>">선별라벨발행</a></li>
                            <li><a href="<?php echo base_url('smt/smtlist1')?>" class="<?php echo ($this->data['subpos'] == 'smtlist1')?"on":"";?>">자재투입실적수신</a></li>
                            <li><a href="<?php echo base_url('smt/smtlist2')?>" class="<?php echo ($this->data['subpos'] == 'smtlist2')?"on":"";?>">제작완료실적수신</a></li>
                            <li><a href="<?php echo base_url('smt/smtlist3')?>" class="<?php echo ($this->data['subpos'] == 'smtlist3')?"on":"";?>">검사정보실적수신</a></li>
                            <li><a href="<?php echo base_url('smt/s5')?>" class="<?php echo ($this->data['subpos'] == 's5')?"on":"";?>">작업일보</a></li>
                            <li><a href="<?php echo base_url('smt/s6')?>" class="<?php echo ($this->data['subpos'] == 's6')?"on":"";?>">생산진행현황</a></li>
                        </ul>
                    </li>
<?php   } 
        if(!empty($_SESSION['user_level']) && $_SESSION['user_level'] >= 1){  
?>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('ass/s1')?>" class="menu_a <?php echo ($this->data['pos'] == "ass")?"on":"";?>">
                        <i class="material-icons">engineering</i>
                        조립생산관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "ass")?"style='display:block'":"";?>>
							<li><a href="<?php echo base_url('ass/s1')?>" class="<?php echo ($this->data['subpos'] == 's1')?"on":"";?>">생산계획 등록</a></li>
                            <li><a href="<?php echo base_url('ass/s2')?>" class="<?php echo ($this->data['subpos'] == 's2')?"on":"";?>">생산계획 조회</a></li>
                            <li><a href="<?php echo base_url('ass/s3')?>" class="<?php echo ($this->data['subpos'] == 's3')?"on":"";?>">계획대비실적현황</a></li>
                            <li><a href="<?php echo base_url('ass/s4')?>" class="<?php echo ($this->data['subpos'] == 's4')?"on":"";?>">작업지시등록</a></li>
                            <li><a href="<?php echo base_url('ass/s_print')?>" class="<?php echo ($this->data['subpos'] == 's_print')?"on":"";?>">Line별 작업지시</a></li>
                            <li><a href="<?php echo base_url('ass/barcode')?>" class="<?php echo ($this->data['subpos'] == 'barcode')?"on":"";?>">선별라벨발행</a></li>
                            <!--li><a href="">자재투입실적수신</a></li-->
                            <li><a href="<?php echo base_url('ass/asslist1')?>" class="<?php echo ($this->data['subpos'] == 'asslist1')?"on":"";?>">제작완료실적수신</a></li>
                            <li><a href="<?php echo base_url('ass/asslist2')?>" class="<?php echo ($this->data['subpos'] == 'asslist2')?"on":"";?>">검사정보실적수신</a></li>
                            <li><a href="<?php echo base_url('ass/s5')?>" class="<?php echo ($this->data['subpos'] == 's5')?"on":"";?>">작업일보</a></li>
                            <li><a href="<?php echo base_url('ass/s6')?>" class="<?php echo ($this->data['subpos'] == 's6')?"on":"";?>">생산진행현황</a></li>
                            <li><a href="<?php echo base_url('ass/asslist3')?>" class="<?php echo ($this->data['subpos'] == 'asslist3')?"on":"";?>">솔더실적관리</a></li>
                        </ul>
                    </li>
<?php   } 
        if(!empty($_SESSION['user_level']) && $_SESSION['user_level'] >= 2){  
?>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('rel/r1')?>" class="menu_a <?php echo ($this->data['pos'] == "rel")?"on":"";?>">
                        <i class="material-icons">layers</i>
                        재고/수불관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "rel")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('rel/r1')?>" class="<?php echo ($this->data['subpos'] == 'r1')?"on":"";?>">출고등록</a></li>
                            <li><a href="<?php echo base_url('rel/r2')?>" class="<?php echo ($this->data['subpos'] == 'r2')?"on":"";?>">기간별/업체별 출고내역</a></li>
                            <li><a href="<?php echo base_url('rel/r3')?>" class="<?php echo ($this->data['subpos'] == 'r3')?"on":"";?>">재공품내역</a></li>
                            <li><a href="<?php echo base_url('rel/r4')?>" class="<?php echo ($this->data['subpos'] == 'r4')?"on":"";?>">클래임 등록</a></li>
                            <li><a href="<?php echo base_url('rel/r5')?>" class="<?php echo ($this->data['subpos'] == 'r5')?"on":"";?>">클래임 내역조회</a></li>
                            <li><a href="<?php echo base_url('rel/rview')?>" class="<?php echo ($this->data['subpos'] == 'rview')?"on":"";?>">생산현황판</a></li>
                        </ul>
                    </li>
<?php   } 
        if(!empty($_SESSION['user_level']) && $_SESSION['user_level'] >= 2){  
?>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('mat/matform')?>" class="menu_a <?php echo ($this->data['pos'] == "mat")?"on":"";?>">
                        <i class="material-icons">inbox</i>
                        자재관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "mat")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('mat/matform')?>" class="<?php echo ($this->data['subpos'] == 'matform')?"on":"";?>">자재입고등록</a></li>
                            <li><a href="<?php echo base_url('mat/materials')?>" class="<?php echo ($this->data['subpos'] == 'materials')?"on":"";?>">재고실사관리</a></li>
                            <li><a href="<?php echo base_url('mat/stocklist')?>" class="<?php echo ($this->data['subpos'] == 'stocklist')?"on":"";?>">재고현황</a></li>
                            <li><a href="<?php echo base_url('mat/m1')?>" class="<?php echo ($this->data['subpos'] == 'm1')?"on":"";?>">안전재고등록</a></li>
                            <li><a href="<?php echo base_url('mat/m2')?>" class="<?php echo ($this->data['subpos'] == 'm2')?"on":"";?>">안전재고현황</a></li>
                        </ul>
                    </li>
<?php   } 
        if(!empty($_SESSION['user_level']) && $_SESSION['user_level'] >= 3){  
?>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('register')?>" class="menu_a <?php echo ($this->data['pos'] == "register")?"on":"";?>">
                        <i class="material-icons">settings</i>
                        시스템관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "register")?"style='display:block'":"";?>>
                            <!--li><a href="">메뉴등록</a></li-->
                            <li><a href="<?php echo base_url('register')?>" class="<?php echo ($this->data['subpos'] == NULL)?"on":"";?>">사용자 등록</a></li>
                            <li><a href="<?php echo base_url('register/level')?>" class="<?php echo ($this->data['subpos'] == 'level')?"on":"";?>">사용자 권한등록</a></li>
                            <li><a href="<?php echo base_url('register/version')?>" class="<?php echo ($this->data['subpos'] == 'version')?"on":"";?>">버전관리</a></li>
                            <li><a href="<?php echo base_url('register/userlog')?>" class="<?php echo ($this->data['subpos'] == 'userlog')?"on":"";?>">접속기록</a></li>
                        </ul>
                    </li>
                    <?php }}else{ ?>
                            <li class="menu01_li">
                                <a href="<?php echo base_url('register/login')?>" class="menu_a <?php echo ($this->data['pos'] == "register")?"on":"";?>">
                                <i class="material-icons">assignment_ind</i>로그인</a>
                            </li>
                    <?php }?>
                </ul>
            
                
            </div>
        </div>
	</div>
    <input type="hidden" class="savehidden">
    <div class="body_">
	<div class="body_Content">

	
	
