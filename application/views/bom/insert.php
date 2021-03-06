<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div id="pageTitle">
    <h1><?= $title;?></h1>
</div>

<div class="searchBox">
    <div>
        <!--Bom의 BLNO를 선택후 나오는 정보가 출력된 상태에서 조회하면 그대로 남는 문제를 고치기 위해 action 옵션값을 넣어줌-->
        <form id="items_formupdate" action="<?= base_url("bom/insert")?>">

            <label for="">BL_NO</label>
            <input type="text" name="bno" value="<?= $str['bno']?>" size="15" />
            <label for="">품명</label>
            <input type="text" name="iname" value="<?= $str['iname']?>" size="15" />

            <?php
			if(!empty($MSAB)){
			?>
            <label for="">MSAB</label>
            <select name="mscode" style="padding:4px 10px; border:1px solid #ddd;">
                <option value="">ALL</option>
                <?php
				foreach($MSAB as $row){
					$selected1 = ($str['mscode'] == $row->D_NAME)?"selected":"";
				?>
                <option value="<?= $row->D_CODE?>" <?= $selected1;?>><?= $row->D_NAME;?></option>
                <?php
				}	
				?>
            </select>
            <?php
			}else{
				echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 HSAB를 등록하세요</a>";
			}
			?>

            <?php
			if(!empty($GJ_GB)){
			?>
            <label for="">공정구분</label>
            <select name="gjcode" style="padding:4px 10px; border:1px solid #ddd;">
                <option value="">ALL</option>
                <?php
				foreach($GJ_GB as $row){
					$selected8 = ($str['gjcode'] == $row->D_CODE)?"selected":"";
				?>
                <option value="<?= $row->D_CODE?>" <?= $selected8;?>><?= $row->D_NAME;?></option>
                <?php
				}
				?>
            </select>
            <?php
			}
			?>

            <?php
			if(!empty($M_LINE)){
			?>
            <label for="">생산라인</label>
            <select name="mline" style="padding:4px 10px; border:1px solid #ddd;">
                <option value="">ALL</option>
                <?php
				foreach($M_LINE as $row){
					$selected1 = ($str['mline'] == $row->D_CODE)?"selected":"";
				?>
                <option value="<?= $row->D_CODE?>" <?= $selected1;?>><?= $row->D_NAME;?></option>
                <?php
				}
				?>
            </select>
            <?php
			}
			?>

            <?php
			if (!empty($CUSTOMER)) {
			?>
				<label for="">고객명</label>
				<select name="customer"  style="padding:4px 10px; border:1px solid #ddd;">
					<option value="">전체</option>
					<?php
					foreach ($CUSTOMER as $row) {
						$selected6 = ($str['customer'] == $row->CUST_NM) ? "selected" : "";
					?>
						<option value="<?= $row->CUST_NM; ?>" <?= $selected6; ?>><?= $row->CUST_NM; ?></option>
					<?php
					}
					?>
				</select>
			<?php
			} else {
				echo "<a href='" . base_url('biz') . "' class='none_code'>업체를 등록하세요</a>";
			}
			?>

            <!--
			<label for="">사용유무</label>
			<input type="checkbox" name="use" value="Y" <?= ($str['use'] == "Y")?"checked":"";?> />
			-->
			<button class="search_submit"><i class="material-icons">search</i></button>
		
		
			<?php if(!empty($idx)){ ?>
            <span style="margin-top:6px; float: right;" class="btn print write_xlsx" data-idx="<?= $idx;?>" data-gjgb="<?= $gjgb;?>"><i class="material-icons">get_app</i>엑셀업로드</span>
			<span style="margin:6px;" class="btn_right add add_bom" data-idx="<?= $idx;?>" data-gjgb="<?= $gjgb;?>"><i class="material-icons">add</i>등록/제거</span>
            <input style="float:right; background:#fff" type="text" name="bnoidx" value="<?=$_GET['bnoidx'] ?>" size="15" disabled />

			<?php } ?>
			
		</form>
		
	</div>

	
	<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
	<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
</div> 

<div class="bdcont_40">
    <div class="bc__box">

        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <thead>
                    <tr>
                        <th>no</th>
                        <th>B/L NO</th>
                        <th>품명</th>
                        <th>MSAB</th>
                        <th>생산라인</th>
                        <th>하위자재</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
				foreach($bomList as $i=>$row){
					$num = $pageNum+$i+1;
				?>

					<tr id="poc_<?= $row->IDX;?>" class="<?= ($idx == $row->IDX)?"over":""; ?>">
						<td class="cen"><?= $num;?></td>
						<td style="max-width:140px;"><span class="mod_items mlink" data-bno="<?= $row->BL_NO; ?>" data-idx="<?= $row->IDX;?>" data-gjgb="<?= $row->GJ_GB;?>"><?= $row->BL_NO; ?></span></td>
						<td style="max-width:140px;"><?= $row->ITEM_NAME; ?></td>
						<td class="cen"><?= $row->MSAB; ?></td>
						<td><?= $row->M_LINE; ?></td>
                        <td class="cen">
                            <?= ($row->C_COUNT > 0)?"<strong>".$row->C_COUNT."</strong>":"<span class='gray'>-</span>";?><!--button type="button" class="mod mod_items" data-idx="<?= $row->IDX;?>">선택</button--></td>
					</tr>

				<?php
				}
				if(empty($bomList)){
				?>

                    <tr>
                        <td colspan="7" class="list_none">제품정보가 없습니다.</td>
                    </tr>

                    <?php
				}	
				?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <?= $this->data['pagenation'];?>
            <?php
			if($this->data['cnt'] > 20){
			?>
            <div class="limitset">
                <select name="per_page">
                    <option value="20" <?= ($perpage == 20)?"selected":"";?>>20</option>
                    <option value="50" <?= ($perpage == 50)?"selected":"";?>>50</option>
                    <option value="80" <?= ($perpage == 80)?"selected":"";?>>80</option>
                    <option value="100" <?= ($perpage == 100)?"selected":"";?>>100</option>
                </select>
            </div>
            <?php
			}	
			?>
        </div>

    </div>
</div>


<div class="bdcont_60">
    <div class="bc__box">


        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <thead>
                    <tr>
                        <th>no</th>
                        <th>자재코드</th>
                        <th>자재명</th>
                        <th>단위</th>
                        <th>재료비</th>
                        <th>REEL단위</th>
                        <th>POINT</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
				foreach($insertBomList as $i=>$row){
				?>

                    <tr>
                        <td class="cen"><?= $i+1;?></td>
                        <td><?= $row->COMPONENT; ?></td>
                        <td><?= $row->COMPONENT_NM; ?></td>
                        <td class="cen"><?= $row->UNIT; ?></td>
                        <td class="right"><?= number_format($row->PRICE); ?></td>
                        <td class="right"><?= number_format($row->REEL_CNT); ?></td>
                        <td><input type="text" name="POINT" class="form_input" size="10"
                                value="<?= number_format($row->POINT); ?>" style="text-align: right; width:100%" /></td>
                        <td><button type="button" class="mod mod_bom" data-idx="<?= $row->BIDX;?>">수정</button>
                        </td>
                    </tr>

                    <?php
				}
				if(empty($insertBomList)){
				?>

                    <tr>
                        <td colspan="13" class="list_none">제품정보가 없습니다.</td>
                    </tr>

                    <?php
				}	
				?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<div id="pop_container">

    <div class="info_content" style="height:auto;">
        <div class="ajaxContent">

            <!-- 데이터 -->

        </div>
    </div>

</div>



<script>
var IDX = "<?= $idx?>";
var bno;

$(".form_input").on("change",function(){
    var point = $(this).val()
    if(point < 0 ){
        alert("POINT가 0보다 적을 수 없습니다.");
        $(this).focus();
        $(this).val(0);
    }
});

$(".write_xlsx").on("click",function(){
    var idx = $(this).data("idx");
    var gjgb = $(this).data("gjgb");

    $(".ajaxContent").html('');

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);


    $.ajax({
        url: "<?= base_url('bom/ajax_xlsxbomWriteform')?>",
        type: "POST",
        dataType: "HTML",
        data: {
            idx: idx,
            gjgb: gjgb
        },
        success: function(data) {
            $(".ajaxContent").html(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert(xhr);
            alert(textStatus);
            alert(errorThrown);
        }
    })
});


$(".mod_items").on("click",function(){
	var idx = $(this).data("idx");
	var gjgb = $(this).data("gjgb");
	var bno = $(this).data("bno");
	

	$(window).unbind("beforeunload");
    var qstr = "<?= $qstr ?>";
	qstr = qstr+"&bnoidx="+bno

	var pp = $("select[name='per_page']").val();
	var perpage = (pp != "")?"&perpage="+pp:"";
	location.href="<?= base_url('bom/insert/')?>"+idx+"/"+gjgb+qstr;



});


$(".limitset select").on("change", function() {
    $(window).unbind("beforeunload");
var qstr = "<?= $qstr ?>";
    location.href = "<?= base_url('bom/insert/')?>" + qstr + "&perpage=" + $(this).val();

});



$(".search_submit").on("click", function() {

    //var seq = $("select[name='seq']").val();
    //var set = $("input[name='set']").val();
    //if(set == ""){
    //alert("검색어를 입력하세요");
    //$("input[name='set']").focus();
    //return false;
    //seq = "all";
    //}
    //alert("<?= base_url()?>");
    //location.href="<?= base_url('bom/insert/')?>";
});



$(".mod_bom").on("click", function() {
    var idx = $(this).data("idx");
    var work = $(this).parents("tr").find("input[name='WORK_ALLO']").val();
    var pt = $(this).parents("tr").find("input[name='PT']").val();
    var point = $(this).parents("tr").find("input[name='POINT']").val();
    var reel = $(this).parents("tr").find("input[name='REEL_CNT']").val();

    $.post("<?= base_url('bom/ajax_bomlist_update')?>", {
        idx: idx,
        work: work,
        pt: pt,
        point: point,
        reel: reel
    }, function(data) {
        if (data > 0) {
            alert('변경되었습니다.');
            //location.reload();
        }
    });

});


//등록/제거
$(".add_bom").on("click", function() {

    var idx = $(this).data("idx");
    var gjgb = $(this).data("gjgb");

    $(".ajaxContent").html('');

    $("#pop_container").fadeIn();
    $(".info_content").animate({
        top: "50%"
    }, 500);

    $.ajax({
        url: "<?= base_url('bom/ajax_bomWriteform')?>",
        type: "POST",
        dataType: "HTML",
        data: {
            mode: "add",
            idx: idx,
            gjgb: gjgb
        },
        success: function(data) {
            $(".ajaxContent").html(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert(xhr);
            alert(textStatus);
            alert(errorThrown);
        }
    })

});

$(document).on("click", "h2 > span.close", function() {

    $(".ajaxContent").html('');
    $("#pop_container").fadeOut();
    $(".info_content").css("top", "-50%");
    location.reload();

});
</script>