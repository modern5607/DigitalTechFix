<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


<div class="mheader">
	<!--span class="left material-icons">keyboard_arrow_left</span-->
	<span class="material-icons right">reorder</span>
	<?php echo $title;?>
	
</div>

<div class="mbody">
	<div class="bdcont_100">
		<div class="bc__box100">
		<h3><?php echo date("Y-m-d",time()); ?></h3>
		<?php
		foreach($viewList as $i=>$row){

		?>
			<div class="xm_box">
				<h2><?php echo $row->NAME;?></h2>
				<div>
					<p>금일생산시간 : <span><?php echo $row->ACT_TIME; ?></span></p>
					<p>생산실적 : <span><?php echo $row->SUM_PT; ?></span></p>
					<p>달성률(%) : <span><?php echo ($row->ACT1 != "")?$row->ACT1:"-"; ?></span></p>
					<p>생산효율(%) : <span><?php echo ($row->VAL != "")?$row->VAL:"-"; ?></span></p>
				</div>
			</div>

		<?php
		}
		if(empty($viewList)){
		?>

		<?php
		}	
		?>
			

		</div>
	</div>
</div>