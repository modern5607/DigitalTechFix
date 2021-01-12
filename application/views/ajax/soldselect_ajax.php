<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


<select name="sold" id="soldselect" style="margin-bottom: 10px; margin-top: 3px; padding:4px 20px; border:1px solid #ddd;">
            <?php
            if(!empty($soldID))
            {
            foreach($soldID as $row){?>
                <option data-idx=<?=$row->IDX?> value=<?=$row->ID?>><?=$row->ID?></option>
            <?php
            }
        }
        else{?>
            <option data-idx="" value="">없음</option>
            <?php }?>
</select>

<script>

$(document).ready(function(){
    var IDX = $("#soldselect option:selected").data("idx");
    ajax_container(IDX);
});

$("#soldselect").change(function(){
    var IDX = $("#soldselect option:selected").data("idx");
    //var ID = $("#soldselect option:selected").val();
    //console.log(IDX+"  "+ID);
    ajax_container(IDX);
});

function ajax_container(idx="") {
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

</script>