<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>

<div id="pageTitle">
    <h1><?php echo $title;?></h1>
</div>

<div class="bdcont_40" style="width: 30%;">
    <div class="bc__box100" >
        <header>
            <div style="float:left;">
                <form id="items_formupdate">
                    <label for="date">등록일</label>
                    <input type="text" class="calendar" name="sta1"
                        value="<?php echo ($str['sta1']!="")?$str['sta1']:date("Y-m-d",strtotime("-3 day"))?>" />~
                    <input type="text" class="calendar" name="sta2"
                        value="<?php echo ($str['sta2']!="")?$str['sta2']:date("Y-m-d",time())?>" />


                    <button class="search_submit"><i class="material-icons">search</i></button>
                </form>
            </div>
        </header>
        <div class="tbl-content" style="overflow:scroll;overflow-x:hidden; overflow-y: hidden; height:auto;">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>등록일</th>
                        <th>건수</th>
                        <th>불량건수</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
				foreach($List as $i=>$row){
					$num = $i+1;
				?>
                    <tr id="poc_<?=$num?>" class="pocbox" data-idx="<?=$num?>">
                        <td class="cen"><?php echo $num;?></td>
                        <td class="mod_items mlink cen" data-idxdate=<?=$row->DATE?>>
                            <strong><?php echo $row->DATE; ?></strong></td>
                        <td class="cen"><?= number_format($row->CNT);?></td>
                        <td><?=$row->E_CNT?></td>
                    </tr>

                    <?php
				}
				if(empty($List)){
				?>
                    <tr>
                        <td colspan="12" class="list_none">제품정보가 없습니다.</td>
                    </tr>
                    <?php
				}	
				?>
                </tbody>
            </table>
        </div>


    </div>
</div>




<div class="bdcont_70" style="margin-top: 70px;">
    <div class="tbl-content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <thead>
                <th>ID</th>
                <th>Flux time</th>
                <th>Flux weight</th>
                <th>Solder time</th>
                <th>Preheat time</th>
                <th>Solder temp</th>
                <th>Tact time</th>
                <th>Product time</th>
            </thead>
            <tbody>
                <?php
        foreach($soldList as $row)
        { ?>
                <tr>
                    <td class="cen"><?= $row->ID ?></td>
                    <td class="right"><?= number_format($row->FLUX_TIME,3) ?></td>
                    <td class="right"><?= number_format($row->FLUX_WEIGHT,3) ?></td>
                    <td class="right"><?= number_format($row->SOLDER_TIME,3) ?></td>
                    <td class="right"><?= number_format($row->PREHEAT_TIME,3) ?></td>
                    <td class="right"><?= number_format($row->SOLDER_TEMP,3) ?></td>
                    <td class="right"><?= number_format($row->TACT_TIME,3) ?></td>
                    <td><?= $row->PRODUCT_TIME ?></td>
                </tr>
                <?php } ?>

            </tbody>
        </table>

        <div class="pagination">
    <?php echo $this->data['pagenation'];?>
    <?php
			if($this->data['cnt'] > 20){
			?>
    <div class="limitset">
        <select name="per_page" data-idx=<?=$idx?>>
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





<script>
$(document).ready(function() {
    ajax_containerselect(0);
    //ajax_containerTemp(0);
});



$(".mod_items").on("click", function() {
    var idx = $(this).data("idxdate");
    var sta1 = $("input[name='sta1']").val();
    var sta2 = $("input[name='sta2']").val();

    var str = "idx=" + idx + "&sta1=" + sta1 + "&sta2=" + sta2;
    console.log(str);

    $(window).unbind("beforeunload");
    var qstr = "<?php echo $qstr ?>";

    var pp = $("select[name='per_page']").val();
    var perpage = (pp != "") ? "&perpage=" + pp : "";
    location.href = "<?php echo base_url('kpi/fair2?')?>" + str;



});



$(document).on("click", ".items_ajax", function() {
    //alert("asdf");
    var idx = $(this).parent().data("idx");
    var idxdate = $(this).data("idxdate");
    console.log(idxdate);
    $(".pocbox").removeClass("over");
    $("#poc_" + idx).addClass("over");

    ajax_containerselect(idxdate);
});

function ajax_containerselect(idx) {
    // console.log("ajax_container"+idx);
    $.ajax({
        url: "<?php echo base_url('kpi/sold_select_ajax')?>",
        type: "post",
        data: {
            idx: idx
        },
        dataType: "html",
        success: function(data) {
            $(".ajax_select").html(data);
        }

    });
}



$(".limitset select").on("change", function() {
    var idx = $(this).data("idx");
    $(window).unbind("beforeunload");
    var qstr = "<?php echo $qstr ?>";
    location.href = "<?php echo base_url('kpi/fair2/')?>" + qstr + "&perpage=" + $(this).val()+"&idx="+idx;

});

//제이쿼리 수신일 입력창 누르면 달력 출력
$("#actdate").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});

//제이쿼리 수신일 입력창 누르면 달력 출력
$(".calendar").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});
</script>