<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h2>
	<?php echo $title;?>	
	<span class="material-icons close">clear</span>
</h2>
<div class="tbl-write01">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			
			<tbody>
				<tr>
					<th class="w120">LOT NO</th>
					<td colspan="3"><?php echo $ACTPLAN->LOT_NO;?></td>
				</tr>
				<tr>
					<th class="w120">생산라인</th>
					<td colspan="3"><?php echo $ACTPLAN->M_LINE;?></td>
				</tr>
				<tr>
					<th class="w120">B/L NO</th>
					<td colspan="3"><?php echo $ACTPLAN->BL_NO;?></td>
				</tr>
				<tr>
					<th class="w120">MSAB</th>
					<td colspan="3"><?php echo $ACTPLAN->MSAB;?></td>
				</tr>
				<tr>
					<th class="w120">수량</th>
					<td colspan="3"><?php echo $ACTPLAN->QTY;?></td>
				</tr>
				<tr>
					<th class="w120">완료수량</th>
					<td colspan="3"><?php echo $ACTPLAN->ACT_QTY;?></td>
				</tr>
				<tr>
					<th class="w120">PㆍT</th>
					<td colspan="3"><?php echo $ACTPLAN->PT;?></td>
				</tr>
				<tr>
					<th class="w120">시간</th>
					<td colspan="3"><?php echo number_format(round(($ACTPLAN->PT*$ACTPLAN->QTY)/60));?></td>
				</tr>

			
			</tbody>
		</table>
	</div>