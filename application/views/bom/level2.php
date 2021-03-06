<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div id="pageTitle">
    <h1><?php echo $title;?></h1>
</div>

<div class="searchBox">
    <div>
        <!--Bom의 BLNO를 선택후 나오는 정보가 출력된 상태에서 조회하면 그대로 남는 문제를 고치기 위해 action 옵션값을 넣어줌-->
        <form id="items_formupdate" action="<?php echo base_url("bom/level2")?>">

            <label for="">2LV 품명</label>
            <input type="text"autocomplete="off" name="compname" value="<?php echo $str['compname']?>" size="15" />




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
                <option value="<?php echo $row->D_CODE?>" <?php echo $selected1;?>><?php echo $row->D_NAME;?></option>
                <?php
				}
				?>
            </select>
            <?php
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
                <option value="<?php echo $row->D_CODE?>" <?php echo $selected8;?>><?php echo $row->D_NAME;?></option>
                <?php
				}
				?>
            </select>
            <?php
			}
			?>
            
			<button class="search_submit"><i class="material-icons">search</i></button>
		
		
			<?php if(!empty($hidx)){ ?>
			<span style="margin:6px;" class="btn_right add add_bom" data-idx="<?php echo $hidx;?>" data-cidx="<?= $cidx;?>"><i class="material-icons">add</i>등록/제거</span>
            <input style="float:right; background:#fff" type="text" name="compnm" value="<?= $_GET['compnm']?>" size="15" disabled />
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
                        <th>1 LV</th>
                        <th>2 LV</th>
                        <th>공정구분</th>
                        <th>생산라인</th>
                        <th>하위자재</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
				foreach($bomList as $i=>$row){
					$num = $pageNum+$i+1;
				?>

					<tr id="poc_<?= $row->H_IDX."_".$row->C_IDX;?>" class="<?=($hidx == $row->H_IDX && $cidx == $row->C_IDX)?"over":""; ?>">
						<td class="cen"><?php echo $num;?></td>
						<td style="max-width:140px;"><span class="mod_items"><?php echo $row->BL_NO;?></span></td>
						<td style="max-width:140px;"><span class="mod_items mlink" data-hidx="<?=$row->H_IDX?>" data-cidx="<?=$row->C_IDX?>" data-compnm="<?php echo $row->COMPONENT_NM;?>" data-gjgb="<?=$row->GJ_CD?>"><?=$row->COMPONENT_NM?></span></td>
						<td class="cen"><?php echo $row->GJ_GB; ?></td>
						<td class="cen"><?php echo $row->M_LINE; ?></td>
                        <td class="cen">
                            <?php echo ($row->C_COUNT > 0)?"<strong>".$row->C_COUNT."</strong>":"<span class='gray'>-</span>";?><!--button type="button" class="mod mod_items" data-idx="<?php echo $row->C_IDX;?>">선택</button--></td>
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
            <?php echo $this->data['pagenation'];?>
            <?php
			if($this->data['cnt'] > 20){
			?>
            <div class="limitset">
                <select name="per_page">
                    <option value="20" <?php echo ($perpage == 20)?"selected":"";?>>20</option>
                    <option value="50" <?php echo ($perpage == 50)?"selected":"";?>>50</option>
                    <option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
                    <option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
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
				foreach($Rlist as $i=>$row){
				?>

                    <tr>
                        <td class="cen"><?php echo $i+1;?></td>
                        <td><?php echo $row->COMPONENT; ?></td>
                        <td><?php echo $row->COMPONENT_NM; ?></td>
                        <td class="cen"><?php echo $row->UNIT; ?></td>
                        <td class="right"><?php echo number_format($row->PRICE); ?></td>
                        <td class="right"><?php echo number_format($row->REEL_CNT); ?></td>
                        <td><input type="text" autocomplete="off"name="POINT" class="form_input" size="10" value="<?= $row->POINT; ?>" style="text-align: right; width:100%" /></td>
                        <td><button type="button" class="mod mod_bom" data-idx="<?php echo $row->BIDX;?>">수정</button>
                        </td>
                    </tr>

                    <?php
				}
				if(empty($Rlist)){
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
//var IDX = "<?php echo $hidx?>";

var hidx = '<?=$hidx?>';
var cidx = '<?=$cidx?>';

// console.log("hidx:"+hidx+"  cidx:"+cidx);

$(".mod_items").on("click",function(){
	var idx = $(this).data("idx");
	var hidx = $(this).data("hidx");
    var cidx = $(this).data("cidx");
    var compnm = $(this).data("compnm");
    var gjgb = $(this).data("gjgb");
	
    //console.log(gjgb);

	$(window).unbind("beforeunload");
    var qstr = "<?php echo $qstr ?>";
	qstr = qstr+"&compnm="+compnm

	var pp = $("select[name='per_page']").val();
	var perpage = (pp != "")?"&perpage="+pp:"";
	location.href="<?php echo base_url('bom/level2/')?>"+hidx+"/"+cidx+"/"+gjgb+qstr;



});


$(".limitset select").on("change", function() {
    $(window).unbind("beforeunload");
var qstr = "<?php echo $qstr ?>";
    location.href = "<?php echo base_url('bom/level2/')?>" + qstr + "&perpage=" + $(this).val();

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
    //alert("<?php echo base_url()?>");
    //location.href="<?php echo base_url('bom/insert/')?>";
});



$(".mod_bom").on("click", function() {
    var idx = $(this).data("idx");
    var point = $(this).parents("tr").find("input[name='POINT']").val();

    $.post("<?php echo base_url('bom/ajax_l2_bomlist_update')?>", {
        idx: idx,
        point: point
    }, function(data) {
        if (data > 0) {
            alert('변경되었습니다.');
            //location.reload();
        }
    });

});


//등록/제거
$(".add_bom").on("click", function() {

    var hidx = "<?=$hidx?>";
    var cidx = "<?=$cidx?>";
    var gjgb = "<?=$gjgb?>";
    //var compnm = $(this).data("compnm");

    console.log(hidx + cidx + gjgb);

    $(".ajaxContent").html('');

    $("#pop_container").fadeIn();
    $(".info_content").animate({
        top: "50%"
    }, 500);

    $.ajax({
        url: "<?php echo base_url('bom/ajax_level2BomWriteform')?>",
        type: "POST",
        dataType: "HTML",
        data: {
            mode: "add",
            gjgb:gjgb,
            hidx:hidx,
            cidx:cidx
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