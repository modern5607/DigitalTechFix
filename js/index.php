<? include_once("./head.php"); ?>

    <div id="main-content">
        <div class="container-fluid">           
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2>수주현황</h2>
                            <ul class="header-dropdown dropdown">                                
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-frame"></i></a></li>                                
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table m-b-0 table-striped table-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">PJT No</th>
                                            <th scope="col">발주번호</th>
                                            <th scope="col">SEQ</th>
                                            <th scope="col">품명</th>
                                            <th scope="col">단중</th>
                                            <th scope="col">중량</th>
                                            <th scope="col">수량</th>
                                            <th scope="col">납기일</th>                                           
                                            <th scope="col">진행상태</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
<?
	$sql="SELECT PJT_NO,POR_NO,POR_SEQ,MCCSDESC,UNITW,WEIGHT,PO_QTY,PORRQDA,(SELECT REC_CD_NM FROM T_COCD00_D WHERE REC_TYPE = 'ACT' AND REC_CD = PROC_GBN)PROC_STATUS FROM t_jact WHERE PO_DATE  BETWEEN '$st' AND '$et' $add_sql ";
	//echo $sql;
	$stmt = $conn->prepare($sql); 
    $stmt->execute(); 
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
                                        <tr>
                                            <td><?= $row['PJT_NO']?></td>
                                            <td><?= $row['POR_NO']?></td>
                                            <td><?= $row['POR_SEQ']?></td>
                                            <td><?= substr($row['MCCSDESC'],1,20)?></td>
                                            <td><?= $row['UNITW']?></td>
                                            <td><?= $row['WEIGHT']?></td>
                                            <td><?= $row['PO_QTY']?></td>
                                            <td><?= $row['PORRQDA']?></td>                                            
                                            <td><?= $row['PROC_STATUS']?></td>
                                        </tr>
<? } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>                            
            </div>
        </div>
    </div>
    
</div>

<? include_once("./tail.php"); ?>