<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<style>
.main_header{
    display:flex;
    justify-content:space-between;
}

.m_nav_item{
    display:flex;
    flex-direction:column;
    align-items:center;
    font-family: 'apple sd gothic neo';
}
.m_nav_part{
    display:flex;
    justify-content:space-around;
    margin-top:32px;
}
.m_nav_part .material-icons{
    font-size:48px;
    padding:8px;
    margin-bottom:8px;
    background-color:#f7e7e7;
    border-radius:4px;
}

.notitem{
    opacity:0;
    pointer-events:none;
}


</style>
<div class="mheader main_header">
    <a href="/mobile">
    <span class="material-icons left home_icon">home</span>
    </a>
	<!--span class="left material-icons">keyboard_arrow_left</span-->
	<?php echo $title;?>
	<span class="material-icons right">reorder</span>
	
	
</div>



<div class="mbody">

	<div class="bdcont_100">
	
		<div class="bc__box100">
			<nav class="m_nav">
                <div class="m_nav_part">
                    <a href="/mobile/m1" class="m_nav_item">
                        <span class="material-icons">
                            date_range
                        </span>일일작업일지</a>
                    <a href="/mobile/m2" class="m_nav_item">
                        <span class="material-icons">
                            assignment
                        </span>생산현황</a>
                </div>
                <div class="m_nav_part">
                    <a href="/mobile/m3" class="m_nav_item">
                        <span class="material-icons">
                            chrome_reader_mode
                        </span>계획대비실적</a>
                    <a href="/mobile/m4" class="m_nav_item">
                        <span class="material-icons">
                            assessment
                        </span>납기지연내역</a>
                </div>
                
            </nav>	
		</div>
	</div>
</div>