<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<h2>
    <?php echo $title;?>
    <span class="material-icons close">clear</span>
</h2>

<div class="formContainer">
    <div class="register_form">

        <div class="search_box">
            <form id="bom_formupdate">
                <select name="seq" class="form_input">
                    <option value="COMPONENT" <?php echo ($seq == "COMPONENT")?"selected":"";?>>자재코드</option>
                    <option value="COMPONENT_NM" <?php echo ($seq == "COMPONENT_NM")?"selected":"";?>>자재명</option>
                    <option value="SPEC" <?php echo ($seq == "SPEC")?"selected":"";?>>규격</option>
                </select>
                <input hidden="hidden" />
                <input type="text" autocomplete="off" name="set" value="<?php echo $set;?>" class="form_input">
                <button type="button" class="search_submit blue_btnx"><i class="material-icons">search</i></button>
            </form>
        </div>

        <div class="update_text">
            <span></span>
            <span style="float:right">total : <?php echo number_format($this->data['cnt']);?> 건</span>
        </div>

        <div class="tbl-content" style="max-height:500px;">

            <form name="">
                <input type="hidden" name="item_hidx" value="<?= $hidx;?>">
                <input type="hidden" name="item_cidx" value="<?= $cidx;?>">
                <input type="hidden" name="item_l2idx" value="<?= $l2idx;?>">
                <input type="hidden" name="item_gjgb" value="<?= $gjgb;?>">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th>선택</th>
                            <th>자재코드</th>
                            <th>자재명</th>
                            <th>단위</th>
                            <th>단가</th>
                            <th>REEL단위</th>
                        </tr>
                    </thead>
                    <tbody id="bom_ajaxList">
                        <?php
				foreach($materialList as $i=>$row){
				?>

                        <tr>
                            <td class="cen"><input type="checkbox" class="setHidx" name="idx[]"
                                    <?php echo ($row->CHKBOM > 0)?"checked":"";?> value="<?php echo $row->IDX; ?>"></td>
                            <td><?php echo $row->COMPONENT; ?></td>
                            <td><?php echo $row->COMPONENT_NM; ?></td>
                            <td class="cen"><?php echo $row->UNIT; ?></td>
                            <td class="right"><?php echo number_format($row->PRICE); ?></td>
                            <td class="right"><?php echo number_format($row->REEL_CNT); ?></td>
                        </tr>

                        <?php
				}
				if(empty($materialList)){
				?>

                        <tr>
                            <td colspan="6" class="list_none">자재정보가 없습니다.</td>
                        </tr>

                        <?php
				}	
				?>

                    </tbody>
                </table>

                <!--div class="bcont">
				<button type="button" class="submitBtn blue_btn">선택</button>
			</div-->

            </form>

        </div>



    </div>
</div>




<script>
$(".search_submit").on("click", function() {
    var formData = new FormData($("#bom_formupdate")[0]);
    var $this = $(this);

    formData.append("idx", $("input[name='item_idx']").val());
    formData.append("gjgb", $("input[name='item_gjgb']").val());



    if ($("#bom_formupdate input[name='set']").val() == "") {
        //alert("검색어를 입력하세요");
        //$("input[name='set']").focus();
        //return false;
    }


    $.ajax({
        url: "<?php echo base_url('bom/ajax_level3BomWriteform')?>",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            //$this.hide();
            //$("#loading").show();
            $(".ajaxContent").html('');
        },
        success: function(data) {


            $(".ajaxContent").html(data);

            /*
            if(jsonData.status == "ok"){
            
            	setTimeout(function(){
            		//alert(jsonData.msg);
            		//$(".ajaxContent").html('');
            		//$("#pop_container").fadeOut();
            		//$(".info_content").css("top","-50%");
            		//$("#loading").hide();
            		//location.reload();

            	},1000);

            }*/
        },
        error: function(xhr, textStatus, errorThrown) {
            alert(xhr);
            alert(textStatus);
            alert(errorThrown);
        }
    });
});




var _throttleTimer = null;
var _throttleDelay = 100;
var $window = $(".register_form .tbl-content");
var $document = $("#bom_ajaxList");
var setPage = 1;

$document.ready(function() {

    $window
        .off('scroll', ScrollHandler)
        .on('scroll', ScrollHandler);


});


var totalCount = <?php echo $this->data['cnt']?>;
var setCount = <?php echo $this->data['cnt']?>;

function ScrollHandler(e) {

    var idx = $("input[name='item_idx']").val();
    var gjgb = $("input[name='item_gjgb']").val();

    if (totalCount < 50 || setCount < 0) {
        return false;
    }

    clearTimeout(_throttleTimer);
    _throttleTimer = setTimeout(function() {
        //console.log('scroll');

        //do work

        if ($window.scrollTop() + $window.height() > $document.height() - 100) {



            setPage++;
            $("#item_load_msg").show();

            var seq = $("#bom_formupdate select[name='seq']").val();
            var set = $("#bom_formupdate input[name='set']").val();

            $.ajax({
                type: "POST",
                data: {
                    pageNum: setPage,
                    idx: idx,
                    gjgb: gjgb,
                    seq: seq,
                    set: set
                },
                url: "<?php echo base_url('bom/ajax_bomWriteform_list')?>",
                cache: false,
                async: true,
                dataType: "html",
                success: function(data) {


                    $("#bom_ajaxList").append(data);

                    setCount = setCount - 50;

                    //console.log($items);
                    if (setCount < 1) {
                        $("#bom_ajaxList").append("<tr><td colspan='8'>더이상 리스트가 없습니다.</td></tr>");
                    }



                    console.log("setCount = " + setCount);


                }
            });


        }

    }, _throttleDelay);
}

$(".setHidx").on("click", function() {
    var hidx = $("input[name='item_hidx']").val();
    var cidx = $("input[name='item_cidx']").val();
    var l2idx = $("input[name='item_l2idx']").val();
    var l3idx = $(this).val();

    console.log("%s %s %s %s", hidx, cidx, l2idx, l3idx);

    var chk = 0;
    var txt = "삭제";
    if ($(this).is(":checked")) {
        chk = 1;
        txt = "등록";
    }


    $.post("<?php echo base_url('bom/ajax_l3_bom_update')?>", {
        HIDX: hidx,
        CIDX: cidx,
        L2IDX:l2idx,
        L3IDX:l3idx,
        chk: chk
    }, function(data) {
        $(".update_text span").eq(0).text(txt + " " + data.msg);
        setTimeout(function() {
            $(".update_text span").eq(0).text('');
        }, 5000);
    }, "JSON");

    //}
});




$(document).on("click", ".setHidx", function(e) {
    e.stopPropagation();
    var hidx = $("input[name='item_idx']").val();
    var cidx = $(this).val();
    var chk = 0;
    var txt = "삭제";

    //console.log("%s %s %s", hidx, cidx, l2idx);

    if ($(this).is(":checked")) {
        chk = 1;
        txt = "등록";
    }

    

    // //if(confirm(txt+'처리 하시겠습니까?') !== false){

    // $.post("<?php echo base_url('bom/ajax_bom_update')?>", {
    //     HIDX: hidx,
    //     CIDX: cidx,
    //     chk: chk
    // }, function(data) {
    //     $(".update_text span").eq(0).text(txt + " " + data.msg);
    //     setTimeout(function() {
    //         $(".update_text span").eq(0).text('');
    //     }, 5000);
    // }, "JSON");

});





$(".submitBtn").on("click", function() {

    var com_idx = [];
    var item_idx = $("input[name='item_idx']").val();

    $("input:checkbox[name='idx[]']:checked").each(function() {
        com_idx.push($(this).val());
    });

    if (com_idx.length > 0) { //선택된 자재가 있는경우

        $.ajax({
            url: "<?php echo base_url('bom/ajax_bom_insertform')?>",
            type: "POST",
            dataType: "JSON",
            data: {
                comIdx: com_idx,
                itemIdx: item_idx
            },
            success: function(data) {
                if (data.ins_id > 0) {
                    alert(data.msg);
                    $(".ajaxContent").html('');
                    $("#pop_container").fadeOut();
                    $(".info_content").css("top", "-50%");
                    location.reload();
                } else {
                    $(".ajaxContent").html('');
                    $("#pop_container").fadeOut();
                    $(".info_content").css("top", "-50%");
                }
                //$(".ajaxContent").html(data);
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        });

    }

});
</script>