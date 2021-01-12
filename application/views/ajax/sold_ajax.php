<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<div class="tbl-write01">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tbody>
            <tr>
                <th class="w120">ID</th>
                <td colspan="3"><input readonly type="text" name="ID" value="<?=empty($soldinfo)?"":$soldinfo->ID?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Flux time</th>
                <td colspan="3"><input readonly type="text" name="fluxtime" value="<?=empty($soldinfo)?"":$soldinfo->FLUX_TIME." sec"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Flux weight</th>
                <td colspan="3"><input readonly type="text" name="fluxweight" value="<?=empty($soldinfo)?"":$soldinfo->FLUX_WEIGHT." g"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Solder time</th>
                <td colspan="3"><input readonly type="text" name="soldertime" value="<?=empty($soldinfo)?"":$soldinfo->SOLDER_TIME." sec"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Preheat time</th>
                <td colspan="3"><input readonly type="text" name="preheattime" value="<?=empty($soldinfo)?"":$soldinfo->PREHEAT_TIME." sec"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Barcode time</th>
                <td colspan="3"><input readonly type="text" name="barcodetime" value="<?=empty($soldinfo)?"":$soldinfo->BARCODE_TIME." sec"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Solder temp</th>
                <td colspan="3"><input readonly type="text" name="soldertemp" value="<?=empty($soldinfo)?"":$soldinfo->SOLDER_TEMP." ℃"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Wave power</th>
                <td colspan="3"><input readonly type="text" name="wavepower" value="<?=empty($soldinfo)?"":$soldinfo->WAVE_POWER." %"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Wave height</th>
                <td colspan="3"><input readonly type="text" name="waveheight" value="<?=empty($soldinfo)?"":$soldinfo->WAVE_HEIGHT." mm"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Cal. Offset</th>
                <td colspan="3"><input readonly type="text" name="caloffset" value="<?=empty($soldinfo)?"":$soldinfo->CAL_OFFSET." %"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">O2 (ppm)</th>
                <td colspan="3"><input readonly type="text" name=o2 value="<?=empty($soldinfo)?"":$soldinfo->O2_PPM." ppm"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Tact time</th>
                <td colspan="3"><input readonly type="text" name="tacttime" value="<?=empty($soldinfo)?"":$soldinfo->TACT_TIME." sec"?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">Product time</th>
                <td colspan="3"><input readonly type="text" name="producttime" value="<?=empty($soldinfo)?"":$soldinfo->PRODUCT_TIME?>" class="form_input input_100"></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
$("#INSERT_DATE").datetimepicker({
    format: 'Y-m-d H:i:00',
    lang: 'ko-KR'
});


$("#LINE2").on("change", function() {
    if ($(this).val() == "") {
        $("input[name='2ND_P_T']").val('');
    }
});

$("#LINE3").on("change", function() {
    if ($(this).val() == "") {
        $("input[name='3ND_P_T']").val('');
    }
});





$('#REMARK').summernote({
    height: 100,
    lang: 'ko-KR',
    toolbar: false,
    dialogsFade: true,
    disableDragAndDrop: true, //드래그앤드랍true:비활성
    callbacks: {
        onImageUpload: function(files, editor, welEditable) {
            console.log('SummerNote image upload : ', files);
            //sendFile(files, editor, welEditable, '#summernote');
        },
        onMediaDelete: function($target, editor, welEditable) {
            /*const path = $target.attr("src");
            console.log(path);
            $.post("<?php echo base_url('acon/delete_editor_image')?>",{path},function(data){
				if(data != "error"){
					alert(data);
				}
            });*/
        }
    }
});
</script>