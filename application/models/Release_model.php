<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Release_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}


	public function get_release_list($param,$start=0,$limit=20)
	{
		$JOINwhere='';
		$where='';

		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$where.="AND TA.GJ_GB = '{$param['GJ_GB']}'";
		}

		if((!empty($param['TRANS_SDATE']) && $param['TRANS_SDATE'] != "") && !empty($param['TRANS_EDATE']) && $param['TRANS_EDATE'] != ""){
			$JOINwhere.="AND TIT.TRANS_DATE BETWEEN '{$param['TRANS_SDATE']} 00:00:00' AND '{$param['TRANS_EDATE']} 23:59:59'";
		}
		

		$sql=<<<SQL
			SELECT
				TA.*,
				( SELECT A.NAME FROM T_COCD_D AS A WHERE A.H_IDX = 11 AND A.CODE = TA.M_LINE ) AS MLINE,
				COUNT( TIT.IDX ) AS XNUM,
				MAX( TIT.IDX ) AS TIDX,
				SUM( TIT.OUT_QTY ) AS OUT_QTY,
				SUM( TIT.RE_QTY ) AS RE_QTY,
				MAX( TIT.CG_DATE ) AS CG_DATE,
				MAX( TIT.RE_DATE ) AS RE_DATE,
				( TA.QTY - IFNULL( SUM( TIT.OUT_QTY ), 0 ) ) AS XXX 
			FROM
				T_ACTPLN AS TA
				LEFT JOIN T_ITEMS_TRANS AS TIT ON TIT.ACT_IDX = TA.IDX
				{$JOINwhere}
			WHERE
				TA.FINISH = 'Y'
				{$where}
			GROUP BY
				TA.IDX 
			ORDER BY
				TIT.TRANS_DATE DESC,
				TA.BL_NO ASC 
			LIMIT $start,$limit
SQL;
		
		$query = $this->db->query($sql);
		// echo nl2br($this->db->last_query());
		return $query->result();
	}

	public function get_release_cut($param)
	{
		$where = "";
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$where .= " AND TA.GJ_GB = '{$param['GJ_GB']}'";
		}
		if((!empty($param['TRANS_SDATE']) && $param['TRANS_SDATE'] != "") && !empty($param['TRANS_EDATE']) && $param['TRANS_EDATE'] != ""){
			$where .= " AND TIT.TRANS_DATE BETWEEN '{$param['TRANS_SDATE']} 00:00:00' AND '{$param['TRANS_EDATE']} 23:59:59'";
		}

		$sql=<<<SQL
			SELECT
				COUNT( AA.IDX ) AS cut 
			FROM
			(SELECT
				`TA`.*
			FROM
				`T_ACTPLN` AS `TA`
			LEFT JOIN `T_ITEMS_TRANS` AS `TIT` ON `TIT`.`ACT_IDX` = `TA`.`IDX` 
			WHERE
				`TA`.`FINISH` = 'Y' 
				{$where} 
			GROUP BY
				`TA`.`IDX` 
				) as AA
SQL;
		$query = $this->db->query($sql);
		return $query->row()->cut;
	}



	
	/*
		클래임등록 리스트
	*/
	public function get_itemtrans_list_xx($param,$start=0,$limit=20)
	{
		$where ='';
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			// $this->db->where("TA.GJ_GB",$param['GJ_GB']);
			$where.="AND TA.GJ_GB = '{$param['GJ_GB']}'";
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			// $this->db->like("TA.BL_NO",$param['BL_NO']);
			$where.="AND TA.BL_NO LIKE '%{$param['BL_NO']}%'";
		}

		if((!empty($param['RE_DATE']) && $param['RE_DATE'] != "") && (!empty($param['RE_DATE_END']) && $param['RE_DATE_END'] != "")){
			// $this->db->where("TIT.RE_DATE BETWEEN '{$param['RE_DATE']} 00:00:00' AND '{$param['RE_DATE_END']} 23:59:59'");
			$where.="AND TIT.RE_DATE BETWEEN '{$param['RE_DATE']} 00:00:00' AND '{$param['RE_DATE_END']} 23:59:59'";
		}

		$sql=<<<SQL
			SELECT
				TA.*,TIT.IDX AS TIDX,TIT.OUT_QTY,TIT.CG_DATE,TIT.RE_DATE,TIT.CG_YN,TIT.RE_YN,TIT.RE_DATE,TIT.RE_QTY
			FROM
				T_ITEMS_TRANS AS TIT LEFT JOIN T_ACTPLN AS TA ON TIT.ACT_IDX = TA.IDX
			WHERE
				1
				{$where}
				AND TIT.CG_YN = 'N' 
				AND TIT.RE_YN = 'Y' 
			ORDER BY
				TIT.CG_DATE DESC,
				TA.BL_NO ASC 
				LIMIT $start , $limit
SQL;

		$query = $this->db->query($sql);
				
		// $subquery = "(SELECT B.REMARK FROM T_CLAIM as B WHERE B.H_IDX = TIT.IDX AND B.A_IDX = TA.IDX) as REMARK";
		// $this->db->select("TA.*, TIT.IDX AS TIDX, TIT.H_IDX, TIT.OUT_QTY, TIT.CG_DATE, TIT.RE_DATE, TIT.CG_YN, TIT.RE_YN, TIT.RE_DATE, TIT.RE_QTY, {$subquery}");
		// $this->db->where("TIT.CG_YN","Y");
		// $this->db->where("TIT.RE_YN","Y");
		// $this->db->from("T_ACTPLN AS TA");
		// $this->db->join("T_ITEMS_TRANS AS TIT","TIT.ACT_IDX = TA.IDX","right");
		
		// //$this->db->group_by("TA.IDX");
		// $this->db->order_by("TIT.CG_DATE","DESC");
		// $this->db->order_by("TA.BL_NO","ASC");
		// $this->db->limit($limit,$start);
		// $query = $this->db->get();
		// echo nl2br($this->db->last_query());
		return $query->result();
	}


	public function get_itemtrans_list_r2($param,$start=0,$limit=20)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->where("TA.GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("TA.BL_NO", $param['BL_NO']);
		}

		if(!empty($param['CUSTOMER']) && $param['CUSTOMER'] != ""){
			$this->db->like("TA.CUSTOMER",$param['CUSTOMER']);
		}

		if((!empty($param['CG_DATE']) && $param['CG_DATE'] != "") && (!empty($param['CG_DATE_END']) && $param['CG_DATE_END'] != "")){
			$this->db->where("TIT.CG_DATE BETWEEN '{$param['CG_DATE']} 00:00:00' AND '{$param['CG_DATE_END']} 23:59:59'");
		}
		
		$subquery = "(SELECT B.REMARK FROM T_CLAIM as B WHERE B.H_IDX = TIT.IDX AND B.A_IDX = TA.IDX) as REMARK";
		$this->db->select("TA.*, TIT.IDX AS TIDX, TIT.H_IDX, TIT.OUT_QTY, TIT.CG_DATE, TIT.RE_DATE, TIT.CG_YN, TIT.RE_YN, TIT.RE_DATE, TIT.RE_QTY, {$subquery}");
		$this->db->where("TIT.CG_YN","Y");
		$this->db->where("TIT.KIND = 'OT' OR TIT.KIND = 'OTM'");
		$this->db->from("T_ACTPLN AS TA");
		$this->db->join("T_ITEMS_TRANS AS TIT","TIT.ACT_IDX = TA.IDX","right");
		
		//$this->db->group_by("TA.IDX");
		$this->db->order_by("TIT.CG_DATE","DESC");
		$this->db->order_by("TA.BL_NO","ASC");
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		//echo nl2br($this->db->last_query());
		return $query->result();
	}


	
	public function get_itemtrans_list($param,$start=0,$limit=20)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->where("TA.GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("TA.BL_NO",$param['BL_NO']);
		}

		if((!empty($param['CG_DATE']) && $param['CG_DATE'] != "") OR (!empty($param['CG_DATE_END']) && $param['CG_DATE_END'] != "")){
			$this->db->where("TIT.CG_DATE BETWEEN '{$param['CG_DATE']} 00:00:00' AND '{$param['CG_DATE']} 23:59:59'");
		}

		if((!empty($param['RE_DATE']) && $param['RE_DATE'] != "")){
			$this->db->where("TIT.RE_DATE BETWEEN '{$param['RE_DATE']} 00:00:00' AND '{$param['RE_DATE']} 23:59:59'");
		}

		$subquery = "(SELECT B.REMARK FROM T_CLAIM as B WHERE B.H_IDX = TIT.IDX AND B.A_IDX = TA.IDX) as REMARK";
		$this->db->select("TA.*, TIT.IDX AS TIDX, TIT.H_IDX, TIT.OUT_QTY, TIT.CG_DATE, TIT.RE_DATE, TIT.CG_YN, TIT.RE_YN, TIT.RE_DATE, TIT.RE_QTY, {$subquery}");
		$this->db->where("TIT.CG_YN","Y");
		$this->db->from("T_ACTPLN AS TA");
		$this->db->join("T_ITEMS_TRANS AS TIT","TIT.ACT_IDX = TA.IDX","right");
		
		//$this->db->group_by("TA.IDX");
		$this->db->order_by("TIT.CG_DATE","DESC");
		$this->db->order_by("TA.BL_NO","ASC");
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		
		// echo nl2br($this->db->last_query());
		return $query->result();
	}

	public function get_itemtrans_cut_xx($param)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->where("TA.GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("TA.BL_NO",$param['BL_NO']);
		}

		if((!empty($param['CG_DATE']) && $param['CG_DATE'] != "") && (!empty($param['CG_DATE_END']) && $param['CG_DATE_END'] != "")){
			$this->db->where("TIT.CG_DATE BETWEEN '{$param['CG_DATE']} 00:00:00' AND '{$param['CG_DATE']} 23:59:59'");
		}

		$this->db->select("COUNT(TIT.IDX) as cut");
		$this->db->where("TIT.RE_YN","Y");
		$this->db->from("T_ACTPLN AS TA");
		$this->db->join("T_ITEMS_TRANS AS TIT","TIT.ACT_IDX = TA.IDX","rigth");
		$query = $this->db->get();
		//echo $this->last_query();
		
		return $query->row()->cut;
	}


	public function get_itemtrans_cut($param)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->where("TA.GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("TA.BL_NO",$param['BL_NO']);
		}

		if((!empty($param['CG_DATE']) && $param['CG_DATE'] != "") OR (!empty($param['CG_DATE_END']) && $param['CG_DATE_END'] != "")){
			$this->db->where("TIT.CG_DATE BETWEEN '{$param['CG_DATE']} 00:00:00' AND '{$param['CG_DATE']} 23:59:59'");
		}

		$this->db->select("COUNT(TIT.IDX) as cut");
		$this->db->from("T_ACTPLN AS TA");
		$this->db->join("T_ITEMS_TRANS AS TIT","TIT.ACT_IDX = TA.IDX","rigth");
		$query = $this->db->get();
		//echo $this->last_query();
		
		return $query->row()->cut;
	}

//1234
	public function get_itemtrans_cut_r2($param)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->where("TA.GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("TA.BL_NO", $param['BL_NO']);
		}

		if(!empty($param['CUSTOMER']) && $param['CUSTOMER'] != ""){
			$this->db->like("TA.CUSTOMER",$param['CUSTOMER']);
		}

		if((!empty($param['CG_DATE']) && $param['CG_DATE'] != "") && (!empty($param['CG_DATE_END']) && $param['CG_DATE_END'] != "")){
			$this->db->where("TIT.CG_DATE BETWEEN '{$param['CG_DATE']} 00:00:00' AND '{$param['CG_DATE_END']} 23:59:59'");
		}

		$this->db->select("COUNT(TIT.IDX) as cut");
		$this->db->where("TIT.KIND = 'OT' OR TIT.KIND = 'OTM'");
		$this->db->from("T_ACTPLN AS TA");
		$this->db->join("T_ITEMS_TRANS AS TIT","TIT.ACT_IDX = TA.IDX","rigth");
		$query = $this->db->get();
		//echo $this->last_query();
		
		return $query->row()->cut;
	}


	public function get_itemtrans_list_r3($param,$start=0,$limit=20)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$param['GJ_GB']);
		}

		if((!empty($param['PLN_DATE']) && $param['PLN_DATE'] != "") && (!empty($param['PLN_DATE_END']) && $param['PLN_DATE_END'] != "")){
			$this->db->where("PLN_DATE BETWEEN '{$param['PLN_DATE']} 00:00:00' AND '{$param['PLN_DATE_END']} 23:59:59'");
		}
		
		
		//$subquery = "(SELECT B.REMARK FROM T_CLAIM as B WHERE B.H_IDX = TIT.IDX AND B.A_IDX = TA.IDX) as REMARK";
		//$this->db->select("TA.*, TIT.IDX AS TIDX, TIT.H_IDX, TIT.OUT_QTY, TIT.CG_DATE, TIT.RE_DATE, TIT.CG_YN, TIT.RE_YN, TIT.RE_DATE, TIT.RE_QTY, {$subquery}");
		//$this->db->where("TIT.CG_YN","Y");
		//$this->db->from("T_ACTPLN AS TA");
		//$this->db->join("T_ITEMS_TRANS AS TIT","TIT.ACT_IDX = TA.IDX","right");
		
		$this->db->where("FINISH <>",'Y');
		$this->db->from("T_ACTPLN");
		
		//$this->db->group_by("TA.IDX");
		$this->db->order_by("PLN_DATE","DESC");
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		//echo nl2br($this->db->last_query());
		return $query->result();
	}


	public function get_itemtrans_cut_r3($param)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$param['GJ_GB']);
		}

		if((!empty($param['PLN_DATE']) && $param['PLN_DATE'] != "") && (!empty($param['PLN_DATE_END']) && $param['PLN_DATE_END'] != "")){
			$this->db->where("PLN_DATE BETWEEN '{$param['PLN_DATE']} 00:00:00' AND '{$param['PLN_DATE_END']} 23:59:59'");
		}

		$this->db->select("COUNT(IDX) as cut");
		$this->db->where("FINISH <>",'Y');
		$this->db->from("T_ACTPLN");
		$query = $this->db->get();
		// echo $this->last_query();
		return $query->row()->cut;
	}



	public function get_claim_list($param,$start=0,$limit=20)
	{
		$where='';
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$where.="AND TA.GJ_GB = '{$param['GJ_GB']}'";
		}
		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$where.="AND BL_NO '%{$param['BL_NO']}%'";
		}

		if((!empty($param['RE_DATE']) && $param['RE_DATE'] != "")){
			$where.="AND TIT.RE_DATE BETWEEN '{$param['RE_DATE']} 00:00:00' AND '{$param['RE_DATE']} 23:59:59'";
		}
		
		$sql=<<<SQL
		SELECT
			TA.BL_NO,
			TIT.RE_DATE,
			TIT.RE_YN,
			TA.QTY,
			TIT.RE_QTY,
			TA.CUSTOMER,
			CL.REMARK
		FROM
			T_ITEMS_TRANS AS TIT
			LEFT JOIN T_ACTPLN AS TA ON TIT.ACT_IDX = TA.IDX
			LEFT JOIN T_CLAIM AS CL ON CL.H_IDX = TIT.IDX 
			AND CL.A_IDX = TA.IDX
		WHERE 
		1
		{$where}
		LIMIT $start, $limit
SQL;
		// $this->db->select("TC.*,TA.LOT_NO, TA.NAME");
		
		// $this->db->from("T_ACTPLN AS TA");
		// $this->db->join("T_ITEMS_TRANS as TIT","TIT.ACT_IDX = TA.IDX");
		// $this->db->join("T_CLAIM AS TC","TC.A_IDX = TA.IDX","right");
		// //$this->db->group_by("TA.IDX");
		// $this->db->order_by("TC.RE_DATE","DESC");
		// $this->db->limit($limit,$start);
		$query = $this->db->query($sql);


		// echo $this->db->last_query();
		return $query->result();
	}


	public function get_claim_cut($param)
	{
		$where='';
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$where.="AND TA.GJ_GB = '{$param['GJ_GB']}'";
		}
		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$where.="AND BL_NO '%{$param['BL_NO']}%'";
		}

		if((!empty($param['RE_DATE']) && $param['RE_DATE'] != "")){
			$where.="AND TIT.RE_DATE BETWEEN '{$param['RE_DATE']} 00:00:00' AND '{$param['RE_DATE']} 23:59:59'";
		}
		
		$sql=<<<SQL
		SELECT
			TA.BL_NO,
			TIT.RE_DATE,
			TIT.RE_YN,
			TA.QTY,
			TIT.RE_QTY,
			TA.CUSTOMER,
			CL.REMARK
		FROM
			T_ITEMS_TRANS AS TIT
			LEFT JOIN T_ACTPLN AS TA ON TIT.ACT_IDX = TA.IDX
			LEFT JOIN T_CLAIM AS CL ON CL.H_IDX = TIT.IDX 
			AND CL.A_IDX = TA.IDX
		WHERE 
		1
		{$where}
SQL;
		$query=$this->db->query($sql);
		return $query->num_rows();
	}






	public function ajax_trans_items($param)
	{
		$subquery = "(SELECT TI.IDX FROM T_ITEMS AS TI WHERE TI.BL_NO = TA.BL_NO AND TI.GJ_GB = TA.GJ_GB) AS HIDX";
		
		$this->db->select("TA.*,{$subquery}");
		$query = $this->db->where("TA.IDX",$param['idx'])->get("T_ACTPLN as TA");
		$info = $query->row();

		

		$userName = $this->session->userdata('user_name');
		$dateTime = date("Y-m-d H:i:s",time());

		$sql=<<<SQL
			INSERT INTO
				T_ITEMS_TRANS
			SET
				H_IDX      = '{$info->HIDX}',
				TRANS_DATE = '{$dateTime}',
				KIND       = 'OT',
				ACT_IDX    = '{$info->IDX}',
				OUT_QTY    = '{$param['OUT_QTY']}',
				M_LINE     = '{$info->M_LINE}',
				PT         = '{$info->PT}',
				GJ_GB      = '{$info->GJ_GB}',
				INSERT_ID  = '{$userName}',
				INSERT_DATE= '{$dateTime}',
				CG_YN      = 'Y',
				CG_DATE    = '{$dateTime}'
SQL;
		$this->db->query($sql);
		$data['chk'] = $this->db->affected_rows();

		
		$sql=<<<SQL
			UPDATE T_ITEMS SET STOCK = STOCK - {$param['OUT_QTY']}
			WHERE BL_NO = '{$info->BL_NO}'
SQL;
		$this->db->query($sql);

		


		return $data;

	}


	public function ajax_return_form($param)
	{
		$this->db->select("TIT.*,TA.BL_NO, TA.NAME");
		$this->db->where("TA.IDX",$param['idx']);
		$this->db->where("KIND",'OT');
		$this->db->where("CG_YN",'Y');
		$this->db->from("T_ITEMS_TRANS as TIT");
		$this->db->join("T_ACTPLN as TA","TA.IDX = TIT.ACT_IDX","left");
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}

	//반품
	public function ajax_returnNum_form($params)
	{
		$this->db->select("TIT.*,A.CUSTOMER, A.BL_NO,TIT.OUT_QTY");
		$this->db->where("TIT.IDX",$params['idx']);
		$this->db->from("T_ITEMS_TRANS as TIT");
		$this->db->join("T_ACTPLN AS A","A.IDX = TIT.ACT_IDX","LEFT");
		$query = $this->db->get();

		$data['chk'] = false;
		
		if($query->num_rows() > 0){
			$info = $query->row();
		
		// echo var_dump($info);
			

// 		$sql=<<<SQL
// 			DELETE FROM T_ITEMS_TRANS
// 			WHERE IDX = '{$info->IDX}'
// SQL;
// 		$query = $this->db->query($sql);
		
		$this->db->set("RE_YN","Y");
		$this->db->set("RE_DATE",date("Y-m-d H:i:s",time()));
		
		if(($info->OUT_QTY - $params['rNum']) + 0 > 0)
		{
			$this->db->set("OUT_QTY",($info->OUT_QTY - $params['rNum'])+0);
			$this->db->set("CG_YN",'Y');
		}
		else
		{
			$this->db->set("RE_QTY",$params['rNum']);
			$this->db->set("OUT_QTY",0);
			$this->db->set("CG_YN",'N');
		}
		$this->db->where("IDX",$info->IDX);
		$this->db->update("T_ITEMS_TRANS");


// 		$sql=<<<SQL
// 			UPDATE T_ACTPLN
// 			SET RE_DATE = NOW(),
// 			WHERE IDX = '{$info->ACT_IDX}'
// SQL;
// 		$query = $this->db->query($sql);

		
		
		if($this->db->affected_rows() > 0){

			$dateTime = date("Y-m-d H:i:s",time());
			
			$this->db->set("H_IDX",$info->IDX); //items idx
			$this->db->set("A_IDX",$info->ACT_IDX); //act idx
			$this->db->set("BL_NO",$info->BL_NO);
			$this->db->set("QTY",$params['rNum']);
			$this->db->set("CUSTOMER",$info->CUSTOMER);
			$this->db->set("RE_DATE",$dateTime);
			$this->db->set("INSERT_ID",$params['userName']);
			$this->db->set("INSERT_DATE",$dateTime);
			
			$this->db->insert("T_CLAIM");
			if($this->db->affected_rows() > 0){
				$data['chk'] = true;
				return $data;
			}
			
		}


		}else{
			$data['chk'] = false;
			return $data;
		}

	}


	public function claimform_update($param)
	{
		$this->db->set("REMARK",$param['REMARK']);
		$this->db->where("H_IDX",$param['H_IDX']);
		
		$this->db->update("T_CLAIM");
		return $this->db->affected_rows();
	}

	public function get_todate_num()
	{
		$sql=<<<SQL
			SELECT 
			CASE 
			WHEN TIMESTAMPDIFF(minute, DATE_FORMAT(NOW(), '%Y-%m-%d %09:%00:%00') , NOW()) < 180 
			THEN ROUND(TIMESTAMPDIFF(minute, DATE_FORMAT(NOW(), '%Y-%m-%d %09:%00:%00') , NOW()))
			ELSE ROUND((TIMESTAMPDIFF(minute, DATE_FORMAT(NOW(), '%Y-%m-%d %09:%00:%00') , NOW()) - 60))
			END AS VAL
			FROM DUAL
SQL;
		$query = $this->db->query($sql);
		return $query->row()->VAL;
	}


	public function get_left_data()
	{
		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";


		$sql=<<<SQL
			SELECT
				TAI.M_LINE, TAI.ACT_TIME, TCD.NAME			
			FROM
				T_COCD_D as TCD
				LEFT JOIN T_ACT_ILBO as TAI ON(TAI.M_LINE = TCD.CODE)
			WHERE
				TCD.H_IDX = 11 AND
				TAI.ORDER_DATE BETWEEN '{$toDate} 00:00:00' AND '{$toDate} 23:59:59'
			ORDER BY TCD.IDX ASC
SQL;
		$query = $this->db->query($sql);
		return $query->result();

	}

	public function get_rel_view()
	{
		$toDate = date("Y-m-d",time());
		//$toDate = "2021-01-22";


		$sql=<<<SQL
			SELECT
				TAI.*, TCD.NAME,
				SUM_PT, ROUND(((SUM_PT/TAI.ACT_TIME) * 100)) ACT1,
				CASE
					WHEN 
						TIMESTAMPDIFF(minute,TAI.ORDER_DATE, NOW()) < 180
					THEN
						ROUND((SUM_PT / TIMESTAMPDIFF(minute,TAI.ORDER_DATE, NOW())) * 100)
					ELSE
						ROUND((SUM_PT / (TIMESTAMPDIFF(minute, TAI.ORDER_DATE, NOW()) - 60)) * 100)
				END as VAL
					
			FROM
				T_COCD_D as TCD
				LEFT JOIN T_ACT_ILBO as TAI ON(TAI.M_LINE = TCD.CODE)
				LEFT JOIN (SELECT M_LINE,ROUND((PT * OUT_QTY)/60) as SUM_PT FROM T_ITEMS_TRANS WHERE TRANS_DATE BETWEEN '{$toDate} 00:00:00' AND '{$toDate} 23:59:59' AND KIND = 'OT' GROUP BY M_LINE) as T2 ON(T2.M_LINE = TAI.M_LINE)
			WHERE
				TCD.H_IDX = 11 AND
				TAI.ORDER_DATE BETWEEN '{$toDate} 00:00:00' AND '{$toDate} 23:59:59'
			ORDER BY TCD.IDX ASC
				
				
SQL;





		/*$sql=<<<SQL
			SELECT T1.M_LINE, T1.ACT_TIME, SUM_PT, ROUND(((SUM_PT/T1.ACT_TIME) * 100)) ACT1,
			CASE 
			WHEN TIMESTAMPDIFF(minute, T1.ORDER_DATE, NOW()) < 180 
			THEN ROUND((SUM_PT / TIMESTAMPDIFF(minute, T1.ORDER_DATE, NOW())) * 100)
			ELSE ROUND((SUM_PT / (TIMESTAMPDIFF(minute, T1.ORDER_DATE, NOW()) - 60)) * 100)
			END AS VAL
			FROM T_ACT_ILBO T1, 
			(SELECT M_LINE, SUM(OUT_QTY) SUM_QTY, ROUND((PT * OUT_QTY)/60) SUM_PT FROM T_ITEMS_TRANS
			 WHERE TRANS_DATE BETWEEN '{$toDate} 00:00:00' AND '{$toDate} 23:59:59' AND KIND = 'OT'
			  GROUP BY M_LINE) T2  
			WHERE T1.M_LINE = T2.M_LINE
			AND T1.ORDER_DATE BETWEEN '{$toDate} 00:00:00' AND '{$toDate} 23:59:59'
SQL;*/
		$query = $this->db->query($sql);
		//echo nl2br($this->db->last_query());

		return $query->result();
			
	}


	public function ajax_acttime_update($param)
	{
		$toDate = date("Y-m-d",time());
		$this->db->set("ACT_TIME",$param['ACT_TIME']);
		$this->db->where("ORDER_DATE",$toDate);
		$this->db->where("M_LINE",$param['M_LINE']);
		$this->db->update("T_ACT_ILBO");
		
		return $this->db->affected_rows();

	}



}