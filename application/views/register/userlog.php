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

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<div style="float:left;">
				<form id="items_formupdate">
                    <?php date_default_timezone_set('Asia/Seoul');?>
                    <label for="login">로그인 날짜</label>
					<input type="text" class="calendar" name="login" id="login" value="<?php echo ($str['login']!="")?$str['login']:date("Y-m-d",time())?>" />
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
		</header>
        
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>IP</th>
						<th>아이디</th>
						<th>접속 기록</th>
						<th>활동 기록</th>
						<th>상태</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($userlog as $i=>$row){ ?>
				<tr>
					<td class="cen"><?php echo $row->ip; ?></td>
					<td class="cen"><?php echo $row->user_id; ?></td>
					<td class="cen"><?php echo $row->login_time; ?></td>
					<td class="cen"><?php echo $row->logout_time; ?></td>
					<td class="cen"><?php echo $row->state; ?></td>
				</tr>
		

				<?php
				}
				if(empty($userlog)){
				?>

					<tr>
						<td colspan="5" class="list_none">회원정보가 없습니다.</td>
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


<script>
    $(".calendar").datetimepicker({
        format:'Y-m-d',
        timepicker:false,
        lang:'ko-KR'
    });
    $(".limitset select").on("change",function(){
        var qstr = "<?php echo $qstr ?>";
        location.href="<?php echo base_url('smt/s5/')?>"+qstr+"&perpage="+$(this).val();
        
    });
</script>