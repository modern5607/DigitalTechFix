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
                <td><?= $row->ID ?></td>
                <td><?= $row->FLUX_TIME ?></td>
                <td><?= $row->FLUX_WEIGHT ?></td>
                <td><?= $row->SOLDER_TIME ?></td>
                <td><?= $row->PREHEAT_TIME ?></td>
                <td><?= $row->SOLDER_TEMP ?></td>
                <td><?= $row->TACT_TIME ?></td>
                <td><?= $row->PRODUCT_TIME ?></td>
            </tr>
            <?php } ?>

        </tbody>
    </table>
</div>

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

<script>

$(".limitset select").on("change",function(){
    var idx = $(this).data('idx');
    var perpage = $(this).val();
    console.log(idx);

	$(window).unbind("beforeunload");

    // location.href="<?php echo base_url('kpi/fair/')?>"+qstr+"&perpage="+$(this).val();
    $.ajax({
        url: "<?php echo base_url('kpi/sold_select_ajax')?>",
        type: "post",
        data: {
            idx: idx,
            perpage:perpage

        },
        dataType: "html",
        success: function(data) {
            $(".ajax_select").html(data);
        }
        
    });
	
});

</script>


