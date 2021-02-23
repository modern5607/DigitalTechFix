<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<h2>
	프린트
	<span class="material-icons close">clear</span>
	<span class="btn printxx">print</span>
</h2>

<div class="formContainer">
	<div class="bdcont_100">
		<div class="bc__box100">
			
			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-bottom:1px solid #ddd; margin-bottom:10px; background:#fff;">
					<thead>
					<tr>
					<td style="line-height:100px; text-align:center; font-size:25px; font-weight:600; letter-spacing:-1px; position:relative;" rowspan="2">
					<?php 
						if($str['st1'] == $str['st2']){
					?>
							<?php echo $str['st1']?>일 라벨발행
					<?php
						}else{
					?>
							<?php echo $str['st1']?>일 ~ <?php echo $str['st2']?>일 라벨발행
					<?php
						}
					?>
					</td>

					</tr>
					</thead>
				</table>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #ddd; border-bottom:1px solid #ddd; font-size:12px; letter-spacing:-.9px;">
					<thead>
						<tr>
							<td colspan="4"></td>
							<th colspan="1">생산라인</th>
							<th colspan="1"><?php echo $M_TITLE?></th>
						</tr>
						<tr>
							<th>NO</th>
							<th>LOT NO</th>
							<th>BL NO</th>
							<?php if($M_TITLE == "전체"){
								echo "<th>생산라인</th>";
							}?>
							<th colspan="3">바코드</th>
						</tr>
					</thead>
					<tbody>
						
					<?php
					$totalQty = $totalPt = 0;
					foreach($actList as $i=>$row){
						$num = $i+1;

						$barcodeFile = FCPATH.'_static/barcode/barcode_'.$row->IDX.'.gif';
					
					if(file_exists($barcodeFile)){
						$barcodeImg = "<img src='".base_url('_static/barcode/barcode_'.$row->IDX.'.gif')."'>";
					}else{
						$barcodeImg = "";
					}
					?>

						<tr>
							<td class="cen"><?php echo $num;?></td>
							<td><?php echo $row->LOT_NO; ?></td>
							<td><?php echo $row->BL_NO; ?></td>
							<?php if($M_TITLE == "전체"){
								echo "<td>".$row->M_LINE."</td>";
							}?>
							<td class="imgtd" colspan="3">
								<?php echo $barcodeImg;?>
							</td>
						</tr>

					<?php
						$totalQty += $row->QTY;
						$totalPt  += $row->PT; 
					}
					if(empty($actList)){
					?>

						<tr>
							<td colspan="12" class="list_none" style="text-align:center;">작업지시정보가 없습니다.</td>
						</tr>

					<?php
					}	
					?>
					</tbody>
					<tfoot style="width:100%;">
						<tr>
							<td colspan="9">&nbsp;</td>
						</tr>
					</tfoot>
				</table>

			</div>

		</div>
	</div>
</div>