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

<div class="bdcont_40">
    <div class="bc__box100">
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
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>INSERT_DATE</th>
                        <th>건수</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
				foreach($xList as $i=>$row){
					$num = $pageNum+$i+1;
				?>
                    <tr id="poc_<?=$num?>" class="pocbox" data-idx="<?=$num?>">
                        <td class="cen"><?php echo $num;?></td>
                        <td class="items_ajax mlink cen" data-idxdate=<?=$row->DATE?>>
                            <strong><?php echo $row->DATE; ?></strong>
                        </td>
                        <td><?php echo $row->CNT;?></td>
                    </tr>

                    <?php
				}
				if(empty($xList)){
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
    <div class="bc__box100">
        <div class="ajax_select">
        </div>

        <div class="ajax_container">
        </div>
    </div>
</div>


<script>
$(document).ready(function(){
    ajax_containerselect(0);
    ajax_containerTemp(0);
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
    console.log("ajax_container"+idx);
    $.ajax({
        url: "<?php echo base_url('ass/sold_select_ajax')?>",
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

function ajax_containerTemp(idx="") {
    $.ajax({
        url: "<?php echo base_url('ass/sold_ajax')?>",
        type: "post",
        data: {
            idx: idx
        },
        dataType: "html",
        success: function(data) {
            $(".ajax_container").html(data);
        }
    });

}


$(".limitset select").on("change", function() {
    $(window).unbind("beforeunload");
var qstr = "<?php echo $qstr ?>";
    location.href = "<?php echo base_url('ass/asslist3/')?>" + qstr + "&perpage=" + $(this).val();

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