<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Act_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}


	public function get_temp_list()
	{
		$query = $this->db->get("T_ACTPLN_EX");
		$data['list'] = $query->result();
		$data['num']  = $query->num_rows();
		return $data;
	}


	public function ajax_temp_update($params)
	{
		$this->db->insert('T_ACTPLN',$params);

	}


	public function get_actplan_info($idx)
	{
		$this->db->where("IDX",$idx);
		$query = $this->db->get("T_ACTPLN");
		return $query->row();
	}


	public function ajax_mline_info($param)
	{
		$this->db->select("M_LINE, 2ND_LINE as M_LINE2, 3ND_LINE as M_LINE3, P_T, 2ND_P_T as P_T2, 3ND_P_T as P_T3");
		$this->db->where("BL_NO",$param['blno']);
		$query = $this->db->get("T_ITEMS");
		//echo $query;
		return $query->row();

	}

	public function get_smtlist1_list($param,$start=0,$limit=20) //자재투입실적수신
	{
		//echo $param['ACT_DATE'];
		/*
		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}
		*/

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}
		
		if((!empty($param['STA1']) && $param['STA1'] != "") && (!empty($param['STA2']) && $param['STA2'] != "")){
			$this->db->where("ACT_DATE BETWEEN '{$param['STA1']} 00:00:00' AND '{$param['STA2']} 23:59:59'");
		}
		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			$this->db->where("M_LINE",$param['M_LINE']);
		}


		$this->db->select("LOT_NO, BL_NO, ITEM_NAME, M_LINE, MSAB, ACT_NM, ACT_DATE, ACT_REMARK, BARCODE");
		$this->db->where(array("GJ_GB" => 'SMT', "ACT_CD" => "IN"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->result();


	}
	public function get_smtlist1_cut($param)
	{

		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->where(array("GJ_GB" => 'SMT', "ACT_CD" => "IN"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->row()->CUT;
	}

	public function get_smtlist2_list($param,$start=0,$limit=20) //제작완실적
	{
		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}
		if((!empty($param['STA1']) && $param['STA1'] != "") && (!empty($param['STA2']) && $param['STA2'] != "")){
			$this->db->where("ACT_DATE BETWEEN '{$param['STA1']} 00:00:00' AND '{$param['STA2']} 23:59:59'");
		}
		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			$this->db->where("M_LINE",$param['M_LINE']);
		}
		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}

		$this->db->select("LOT_NO, BL_NO, ITEM_NAME, M_LINE, MSAB, ACT_NM, ACT_DATE, ACT_REMARK, BARCODE");
		$this->db->where(array("GJ_GB" => 'SMT', "ACT_CD" => "MAKE"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->result();


	}
	public function get_smtlist2_cut($param)
	{

		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->where(array("GJ_GB" => 'SMT', "ACT_CD" => "MAKE"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->row()->CUT;
	}

	public function get_smtlist3_list($param,$start=0,$limit=20)
	{
		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}
		if((!empty($param['STA1']) && $param['STA1'] != "") && (!empty($param['STA2']) && $param['STA2'] != "")){
			$this->db->where("ACT_DATE BETWEEN '{$param['STA1']} 00:00:00' AND '{$param['STA2']} 23:59:59'");
		}
		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			$this->db->where("M_LINE",$param['M_LINE']);
		}
		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}

		$this->db->select("LOT_NO, BL_NO, ITEM_NAME, M_LINE, MSAB, ACT_NM, ACT_DATE, ACT_REMARK, BARCODE");
		$this->db->where(array("GJ_GB" => 'SMT', "ACT_CD" => "END"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->result();


	}
	public function get_smtlist3_cut($param)
	{

		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->where(array("GJ_GB" => 'SMT', "ACT_CD" => "END"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->row()->CUT;
	}

	public function get_asslist1_list($param,$start=0,$limit=20)
	{
		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}
		if((!empty($param['STA1']) && $param['STA1'] != "") && (!empty($param['STA2']) && $param['STA2'] != "")){
			$this->db->where("ACT_DATE BETWEEN '{$param['STA1']} 00:00:00' AND '{$param['STA2']} 23:59:59'");
		}
		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			$this->db->where("M_LINE",$param['M_LINE']);
		}
		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}

		$this->db->select("LOT_NO, BL_NO, ITEM_NAME, M_LINE, MSAB, ACT_NM, ACT_DATE, ACT_REMARK, BARCODE");
		$this->db->where(array("GJ_GB" => 'ASS', "ACT_CD" => "MAKE"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->result();


	}
	public function get_asslist1_cut($param)
	{

		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->where(array("GJ_GB" => 'ASS', "ACT_CD" => "MAKE"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->row()->CUT;
	}

	public function get_asslist2_list($param,$start=0,$limit=20)
	{
		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}
		if((!empty($param['STA1']) && $param['STA1'] != "") && (!empty($param['STA2']) && $param['STA2'] != "")){
			$this->db->where("ACT_DATE BETWEEN '{$param['STA1']} 00:00:00' AND '{$param['STA2']} 23:59:59'");
		}
		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			$this->db->where("M_LINE",$param['M_LINE']);
		}
		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}

		$this->db->select("LOT_NO, BL_NO, ITEM_NAME, M_LINE, MSAB, ACT_NM, ACT_DATE, ACT_REMARK, BARCODE");
		$this->db->where(array("GJ_GB" => 'ASS', "ACT_CD" => "END"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->result();


	}
	public function get_asslist2_cut($param)
	{

		if(!empty($param['ACT_DATE']) && $param['ACT_DATE'] != ""){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT_DATE']} 00:00:00' AND '{$param['ACT_DATE']} 23:59:59'");
		}
		

		$this->db->select("COUNT(*) as CUT");
		$this->db->where(array("GJ_GB" => 'ASS', "ACT_CD" => "END"));
		$query = $this->db->get("T_ACT_HIS");
		return $query->row()->CUT;
	}

	public function get_asslist3_list($param,$start=0,$limit=20)
	{
		$where='';
		if((!empty($param['STA1']) && $param['STA1'] != "") && (!empty($param['STA2']) && $param['STA2'] != "")){
			// $this->db->where("INSERT_DATE BETWEEN '{$param['STA1']} 00:00:00' AND '{$param['STA2']} 23:59:59'");
			$where .="AND INSERT_DATE BETWEEN '{$param['STA1']} 00:00:00' AND '{$param['STA2']} 23:59:59'";
		}
		
		$sql=<<<SQL
			SELECT
				DATE_FORMAT( INSERT_DATE, '%Y-%m-%d' ) AS DATE,
			CASE
				COL1 
				WHEN 'Y' THEN
				COUNT( * ) ELSE 0 
				END AS CNT,
			CASE
				COL1 
				WHEN 'N' THEN
				COUNT( * ) ELSE 0 
				END AS E_CNT 
			FROM
				T_SOLD_HISTORY
			WHERE
				1
				{$where}
			GROUP BY
				DATE
			ORDER BY 
				DATE DESC
			LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();


	}
	public function get_asslist3_cut($param)
	{
		if((!empty($param['STA1']) && $param['STA1'] != "") && (!empty($param['STA2']) && $param['STA2'] != "")){
			$sql = "SELECT COUNT(*) AS CUT
			FROM (
				SELECT COUNT(*)
				FROM T_SOLD_HISTORY
				WHERE INSERT_DATE BETWEEN '{$param['STA1']} 00:00:00' AND '{$param['STA1']} 23:59:59'
				GROUP BY DATE_FORMAT(INSERT_DATE,'%Y-%m-%d')
			) AS T";
		}
		else
		{
			$sql = "SELECT COUNT(*) AS CUT
			FROM (
				SELECT COUNT(*)
				FROM T_SOLD_HISTORY
				GROUP BY DATE_FORMAT(INSERT_DATE,'%Y-%m-%d')
			) AS T";
		}
		$query= $this->db->query($sql);
		//echo $this->db->last_query();
		return $query->row()->CUT;
	}

	//솔더실적관리 INSERT_DATE클릭시 날짜에 해당하는 
	public function get_sold_ID($params)
	{
		$sql ="SELECT IDX,ID,INSERT_DATE 
		FROM T_SOLD_HISTORY 
		WHERE INSERT_DATE BETWEEN '{$params['INSERT_DATE']} 00:00:00' AND '{$params['INSERT_DATE']} 23:59:59'
		ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function sold_ajax_info($params)
	{
		$sql="SELECT * FROM T_SOLD_HISTORY WHERE IDX = {$params['IDX']}";
		$query = $this->db->query($sql);
		//var_dump($query->result());
		return $query->row();
	}

	
	

	
	public function ajax_s1_update($param) 
	{
		$this->db->set($param['MODE'],$param['VAL']);
		$this->db->where("IDX",$param['IDX']);
		$this->db->update("T_ACTPLN");
		return $this->db->affected_rows();
	}
	

	public function get_actplan_list($param,$start=0,$limit=20)
	{
		
		$this->db->select("*, (SELECT A.NAME FROM T_COCD_D as A WHERE H_IDX = 11 AND A.CODE = M_LINE) as M_LINE");
		
		//if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
		$this->db->where("GJ_GB",$param['GJ_GB']);
		//}

		if(!empty($param['LOT_NO']) && $param['LOT_NO'] != ""){
			$this->db->like("LOT_NO",$param['LOT_NO']);
		}

		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			if($param['M_LINE'] != ""){
				$this->db->where("M_LINE",$param['M_LINE']);
			}else{
				
				$this->db->order_by("M_LINE","DESC");
				$this->db->order_by("IDX","DESC");
				$this->db->order_by("STA_DATE","DESC");
			}
		}

		// if(!empty($param['FINISH']) && $param['FINISH'] != ""){
		// 	if($param['FINISH'] == "N"){
		// 		$this->db->where("FINISH <>","Y");
		// 	}else{
		// 		$this->db->where("FINISH",$param['FINISH']);
		// 	}
		// }

		if(!empty($param['MSAB']) && $param['MSAB'] != ""){
			$this->db->where("MSAB",$param['MSAB']);
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}

		if(!empty($param['CUSTOMER']) && $param['CUSTOMER'] != ""){
			$this->db->like("CUSTOMER",$param['CUSTOMER']);
		}
		
		if((!empty($param['INSERT1']) && $param['INSERT1'] != "") && (!empty($param['INSERT2']) && $param['INSERT2'] != "")){
			$this->db->where("INSERT_DATE BETWEEN '{$param['INSERT1']} 00:00:00' AND '{$param['INSERT2']} 23:59:59'");
		}

		if((!empty($param['PLN1']) && $param['PLN1'] != "") && (!empty($param['PLN2']) && $param['PLN2'] != "")){
			$this->db->where("PLN_DATE BETWEEN '{$param['PLN1']} 00:00:00' AND '{$param['PLN2']} 23:59:59'");
		}

		if((!empty($param['ST1']) && $param['ST1'] != "") && (!empty($param['ST2']) && $param['ST2'] != "")){
			$this->db->where("ST_DATE BETWEEN '{$param['ST1']} 00:00:00' AND '{$param['ST2']} 23:59:59'");
		}

		if(!empty($param['ACT_DATE'])){
			$chkToday = date("Y-m-d H:i:s",time());
			$this->db->where("ACT_DATE < '{$chkToday}'");
			$this->db->where("FINISH <> '1'");
		}
		// var_dump($param['ST_DATE']);
		// $enddate = $param['ST_DATE'];
		// $enddate = date('Y-m-d',strtotime($enddate."-1000 day"));	//테스트를 위해 1000일전 변수를 선언해놓음
		// echo $enddate;
		if(!empty($param['ST_DATE'])){
			$this->db->where("ST_DATE BETWEEN '{$param['ST_DATE']} 00:00:00' AND '{$param['ST_DATE']} 23:59:59'");
		}

		//$this->db->where("FINISH <> 'Y'");

		$this->db->order_by("INSERT_DATE","DESC");
		$this->db->order_by("BL_NO","DESC");

		
		$this->db->limit($limit,$start);
		$query = $this->db->get('T_ACTPLN');

		// echo  $this->db->last_query();
		return $query->result();
	}

	public function get_actplan_cut($param)
	{
		//if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
		$this->db->where("GJ_GB",$param['GJ_GB']);
		//}

		if(!empty($param['LOT_NO']) && $param['LOT_NO'] != ""){
			$this->db->like("LOT_NO",$param['LOT_NO']);
		}

		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			$this->db->where("M_LINE",$param['M_LINE']);			
		}

		if(!empty($param['FINISH']) && $param['FINISH'] != ""){
			if($param['FINISH'] == "N"){
				$this->db->where("FINISH <>","Y");
			}else{
				$this->db->where("FINISH",$param['FINISH']);
			}
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}

		if(!empty($param['CUSTOMER']) && $param['CUSTOMER'] != ""){
			$this->db->like("CUSTOMER",$param['CUSTOMER']);
		}
		
		if((!empty($param['INSERT1']) && $param['INSERT1'] != "") && (!empty($param['INSERT2']) && $param['INSERT2'] != "")){
			$this->db->where("INSERT_DATE BETWEEN '{$param['INSERT1']} 00:00:00' AND '{$param['INSERT2']} 23:59:59'");
		}

		if((!empty($param['PLN1']) && $param['PLN1'] != "") && (!empty($param['PLN2']) && $param['PLN2'] != "")){
			$this->db->where("PLN_DATE BETWEEN '{$param['PLN1']} 00:00:00' AND '{$param['PLN2']} 23:59:59'");
		}

		if((!empty($param['ST1']) && $param['ST1'] != "") && (!empty($param['ST2']) && $param['ST2'] != "")){
			$this->db->where("ST_DATE BETWEEN '{$param['ST1']} 00:00:00' AND '{$param['ST2']} 23:59:59'");
		}
		if(!empty($param['ST_DATE'])){
			$this->db->where("ST_DATE BETWEEN '{$param['ST_DATE']} 00:00:00' AND '{$param['ST_DATE']} 23:59:59'");
		}

		if(!empty($param['ACT_DATE'])){
			$chkToday = date("Y-m-d H:i:s",time());
			$this->db->where("ACT_DATE < '{$chkToday}'");
			$this->db->where("FINISH <> '1'");
		}

		$this->db->order_by("INSERT_DATE","DESC");
		$this->db->order_by("BL_NO","DESC");


		$this->db->select("COUNT(IDX) as cut");
		$data = $this->db->get('T_ACTPLN');
		return $data->row()->cut;
	}


	public function get_actplan_list_finish($param,$start=0,$limit=20)
	{
		
		$this->db->select("*, (SELECT A.NAME FROM T_COCD_D as A WHERE H_IDX = 11 AND A.CODE = M_LINE) as M_LINE");
		
		//if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
		$this->db->where("GJ_GB",$param['GJ_GB']);
		//}

		if(!empty($param['LOT_NO']) && $param['LOT_NO'] != ""){
			$this->db->like("LOT_NO",$param['LOT_NO']);
		}

		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			if($param['M_LINE'] != ""){
				$this->db->where("M_LINE",$param['M_LINE']);
			}else{
				
				$this->db->order_by("M_LINE","DESC");
				$this->db->order_by("IDX","DESC");
				$this->db->order_by("STA_DATE","DESC");
			}
		}

		if(!empty($param['FINISH']) && $param['FINISH'] != ""){
			$this->db->where("FINISH",$param['FINISH']);
		}

		if(!empty($param['MSAB']) && $param['MSAB'] != ""){
			$this->db->where("MSAB",$param['MSAB']);
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}

		if(!empty($param['CUSTOMER']) && $param['CUSTOMER'] != ""){
			$this->db->like("CUSTOMER",$param['CUSTOMER']);
		}
		
		if((!empty($param['INSERT1']) && $param['INSERT1'] != "") && (!empty($param['INSERT2']) && $param['INSERT2'] != "")){
			$this->db->where("INSERT_DATE BETWEEN '{$param['INSERT1']} 00:00:00' AND '{$param['INSERT2']} 23:59:59'");
		}

		if((!empty($param['PLN1']) && $param['PLN1'] != "") && (!empty($param['PLN2']) && $param['PLN2'] != "")){
			$this->db->where("PLN_DATE BETWEEN '{$param['PLN1']} 00:00:00' AND '{$param['PLN2']} 23:59:59'");
		}

		if((!empty($param['ST1']) && $param['ST1'] != "") && (!empty($param['ST2']) && $param['ST2'] != "")){
			$this->db->where("ST_DATE BETWEEN '{$param['ST1']} 00:00:00' AND '{$param['ST2']} 23:59:59'");
		}

		if(!empty($param['STA']) && $param['STA'] != ""){
			$this->db->where("ST_DATE BETWEEN '{$param['STA']} 00:00:00' AND '{$param['STA']} 23:59:59'");
		}

		if(!empty($param['ACT_DATE'])){
			$chkToday = date("Y-m-d H:i:s",time());
			$this->db->where("ACT_DATE < '{$chkToday}'");
			$this->db->where("FINISH <> '1'");
		}

		if(!empty($param['ST_DATE'])){
			$this->db->where("ST_DATE BETWEEN '{$param['ST_DATE']} 00:00:00' AND '{$param['ST_DATE']} 23:59:59'");
		}

		$this->db->where("FINISH <> 'Y'");

		
		$this->db->limit($limit,$start);
		$query = $this->db->get('T_ACTPLN');

		// echo  $this->db->last_query();
		return $query->result();
	}

	public function get_actplan_cut_finish($param)
	{
		$this->db->select("*, (SELECT A.NAME FROM T_COCD_D as A WHERE H_IDX = 11 AND A.CODE = M_LINE) as M_LINE");
		
		//if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
		$this->db->where("GJ_GB",$param['GJ_GB']);
		//}

		if(!empty($param['LOT_NO']) && $param['LOT_NO'] != ""){
			$this->db->like("LOT_NO",$param['LOT_NO']);
		}

		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			if($param['M_LINE'] != ""){
				$this->db->where("M_LINE",$param['M_LINE']);
			}else{
				
				$this->db->order_by("M_LINE","DESC");
				$this->db->order_by("IDX","DESC");
				$this->db->order_by("STA_DATE","DESC");
			}
		}

		if(!empty($param['FINISH']) && $param['FINISH'] != ""){
			$this->db->where("FINISH",$param['FINISH']);
		}

		if(!empty($param['MSAB']) && $param['MSAB'] != ""){
			$this->db->where("MSAB",$param['MSAB']);
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}

		if(!empty($param['CUSTOMER']) && $param['CUSTOMER'] != ""){
			$this->db->like("CUSTOMER",$param['CUSTOMER']);
		}
		
		if((!empty($param['INSERT1']) && $param['INSERT1'] != "") && (!empty($param['INSERT2']) && $param['INSERT2'] != "")){
			$this->db->where("INSERT_DATE BETWEEN '{$param['INSERT1']} 00:00:00' AND '{$param['INSERT2']} 23:59:59'");
		}

		if((!empty($param['PLN1']) && $param['PLN1'] != "") && (!empty($param['PLN2']) && $param['PLN2'] != "")){
			$this->db->where("PLN_DATE BETWEEN '{$param['PLN1']} 00:00:00' AND '{$param['PLN2']} 23:59:59'");
		}

		if((!empty($param['ST1']) && $param['ST1'] != "") && (!empty($param['ST2']) && $param['ST2'] != "")){
			$this->db->where("ST_DATE BETWEEN '{$param['ST1']} 00:00:00' AND '{$param['ST2']} 23:59:59'");
		}

		if(!empty($param['STA']) && $param['STA'] != ""){
			$this->db->where("ST_DATE BETWEEN '{$param['STA']} 00:00:00' AND '{$param['STA']} 23:59:59'");
		}

		if(!empty($param['ACT_DATE'])){
			$chkToday = date("Y-m-d H:i:s",time());
			$this->db->where("ACT_DATE < '{$chkToday}'");
			$this->db->where("FINISH <> '1'");
		}

		if(!empty($param['ST_DATE'])){
			$this->db->where("ST_DATE BETWEEN '{$param['ST_DATE']} 00:00:00' AND '{$param['ST_DATE']} 23:59:59'");
		}

		$this->db->where("FINISH <> 'Y'");

		$query = $this->db->get('T_ACTPLN');
		return $query->num_rows();
	}



	
	public function get_actplan_list1($param,$start=0,$limit=20) 
	{
		
		$this->db->select("*, (SELECT A.NAME FROM T_COCD_D as A WHERE H_IDX = 11 AND A.CODE = M_LINE) as M_LINE");
		
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['LOT_NO']) && $param['LOT_NO'] != ""){
			$this->db->like("LOT_NO",$param['LOT_NO']);
		}

		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			if($param['M_LINE'] != ""){
				$this->db->where("M_LINE",$param['M_LINE']);
			}else{
				
				$this->db->order_by("M_LINE","DESC");
				$this->db->order_by("IDX","DESC");
				$this->db->order_by("STA_DATE","DESC");
			}
		}

		if(!empty($param['FINISH']) && $param['FINISH'] != ""){
			if($param['FINISH'] == "N"){
				$this->db->where("FINISH <>","Y");
			}else{
				$this->db->where("FINISH",$param['FINISH']);
			}
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}

		if(!empty($param['CUSTOMER']) && $param['CUSTOMER'] != ""){
			$this->db->like("CUSTOMER",$param['CUSTOMER']);
		}
		
		if((!empty($param['INSERT1']) && $param['INSERT1'] != "") && (!empty($param['INSERT2']) && $param['INSERT2'] != "")){
			$this->db->where("INSERT_DATE BETWEEN '{$param['INSERT1']} 00:00:00' AND '{$param['INSERT2']} 23:59:59'");
		}

		if((!empty($param['ACT1']) && $param['ACT1'] != "") && (!empty($param['ACT2']) && $param['ACT2'] != "")){
			$this->db->where("ACT_DATE BETWEEN '{$param['ACT1']} 00:00:00' AND '{$param['ACT2']} 23:59:59'");
		}

		if((!empty($param['PLN1']) && $param['PLN1'] != "") && (!empty($param['PLN2']) && $param['PLN2'] != "")){
			$this->db->where("PLN_DATE BETWEEN '{$param['PLN1']} 00:00:00' AND '{$param['PLN2']} 23:59:59'");
		}

		if((!empty($param['ST1']) && $param['ST1'] != "") && (!empty($param['ST2']) && $param['ST2'] != "")){
			$this->db->where("ST_DATE BETWEEN '{$param['ST1']} 00:00:00' AND '{$param['ST2']} 23:59:59'");
		}

		if((!empty($param['STA1']) && $param['STA1'] != "") && (!empty($param['STA2']) && $param['STA2'] != "")){
			$this->db->where("STA_DATE BETWEEN '{$param['STA1']} 00:00:00' AND '{$param['STA2']} 23:59:59'");
		}

		//print_r($param);

		if(!empty($param['ACT_DATE'])){
			$chkToday = date("Y-m-d H:i:s",time());
			$this->db->where("ST_DATE < '{$chkToday}'");
			$this->db->where("FINISH <> 'Y'");
		}

		if(!empty($param['ST_DATE'])){
			$this->db->where("ST_DATE BETWEEN '{$param['ST_DATE']} 00:00:00' AND '{$param['ST_DATE']} 23:59:59'");
		}

		$this->db->order_by("INSERT_DATE","DESC");
		$this->db->order_by("BL_NO","DESC");

		
		$this->db->limit($limit,$start);
		$query = $this->db->get('T_ACTPLN');

		//echo nl2br($this->db->last_query());
		return $query->result();
	}

	public function get_actplan_cut1($param)
	{
		$this->db->select("COUNT(IDX) as cut");
		
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['LOT_NO']) && $param['LOT_NO'] != ""){
			$this->db->like("LOT_NO",$param['LOT_NO']);
		}

		if(!empty($param['M_LINE']) && $param['M_LINE'] != ""){
			if($param['M_LINE'] != ""){
				$this->db->where("M_LINE",$param['M_LINE']);
			}else{
				
				$this->db->order_by("M_LINE","DESC");
				$this->db->order_by("IDX","DESC");
				$this->db->order_by("STA_DATE","DESC");
			}
		}

		if(!empty($param['FINISH']) && $param['FINISH'] != ""){
			if($param['FINISH'] == "N"){
				$this->db->where("FINISH <>","Y");
			}else{
				$this->db->where("FINISH",$param['FINISH']);
			}
		}

		if(!empty($param['BL_NO']) && $param['BL_NO'] != ""){
			$this->db->like("BL_NO",$param['BL_NO']);
		}

		if(!empty($param['CUSTOMER']) && $param['CUSTOMER'] != ""){
			$this->db->like("CUSTOMER",$param['CUSTOMER']);
		}
		
		if((!empty($param['INSERT1']) && $param['INSERT1'] != "") && (!empty($param['INSERT2']) && $param['INSERT2'] != "")){
			$this->db->where("INSERT_DATE BETWEEN '{$param['INSERT1']} 00:00:00' AND '{$param['INSERT2']} 23:59:59'");
		}

		if((!empty($param['PLN1']) && $param['PLN1'] != "") && (!empty($param['PLN2']) && $param['PLN2'] != "")){
			$this->db->where("PLN_DATE BETWEEN '{$param['PLN1']} 00:00:00' AND '{$param['PLN2']} 23:59:59'");
		}

		if((!empty($param['ST1']) && $param['ST1'] != "") && (!empty($param['ST2']) && $param['ST2'] != "")){
			$this->db->where("ST_DATE BETWEEN '{$param['ST1']} 00:00:00' AND '{$param['ST2']} 23:59:59'");
		}

		//print_r($param);

		if(!empty($param['ACT_DATE'])){
			$chkToday = date("Y-m-d H:i:s",time());
			$this->db->where("ST_DATE < '{$chkToday}'");
			$this->db->where("FINISH <> 'Y'");
		}

		if(!empty($param['ST_DATE'])){
			$this->db->where("ST_DATE BETWEEN '{$param['ST_DATE']} 00:00:00' AND '{$param['ST_DATE']} 23:59:59'");
		}

		$data = $this->db->get('T_ACTPLN');
		//echo $this->db->last_query();
		return $data->row()->cut;
	}




	public function set_finish_actpln($param)
	{
		$query = $this->db->where("IDX",$param['idx'])->get("T_ACTPLN");
		$actPln = $query->row();
		
		
				
		$data['error'] = false;

		$dateTime = date("Y-m-d H:i:s",time());

		if(!empty($actPln)){

			$this->db->trans_start();

			$this->db->where("IDX",$actPln->IDX);
			$this->db->set("FINISH","Y");
			$this->db->set("ACT_QTY",$actPln->PLN_QTY);
			$this->db->set("END_DATE",$dateTime);
			$this->db->set("FINISH_DATE",$dateTime);
			$this->db->set("UPDATE_ID",$param['userName']);
			$this->db->set("UPDATE_DATE",$dateTime);
			$this->db->update("T_ACTPLN");

			if($this->db->affected_rows() > 0){

				if($param['gjgb'] == "SMT"){

					$subquery1 = "(SELECT A.IDX FROM T_ITEMS as A WHERE A.BL_NO = '".$actPln->BL_NO."')";
					$this->db->set("H_IDX",$subquery1,false);
					$this->db->set("GJ_GB",$param['gjgb']);
					$this->db->set("TRANS_DATE",$dateTime);
					$this->db->set("KIND","IN");
					$this->db->set("ACT_IDX",$actPln->IDX);
					$this->db->set("IN_QTY",$actPln->QTY);
					$this->db->set("CG_YN","Y");
					$this->db->set("PT",$actPln->PT);
					$this->db->set("CG_DATE",$dateTime);
					$this->db->set("OUT_QTY",0);
					$this->db->set("M_LINE",$actPln->M_LINE);
					$this->db->set("INSERT_ID",$param['userName']);
					$this->db->set("INSERT_DATE",$dateTime);
					$this->db->insert("T_ITEMS_TRANS");
					
					$subquery = "(SELECT A.IDX FROM T_ITEMS as A WHERE A.BL_NO = '".$actPln->BL_NO."')";
					$this->db->set("H_IDX",$subquery,false);
					$this->db->set("GJ_GB",$param['gjgb']);
					$this->db->set("TRANS_DATE",$dateTime);
					$this->db->set("KIND","OT");
					$this->db->set("ACT_IDX",$actPln->IDX);
					$this->db->set("PT",$actPln->PT);
					$this->db->set("IN_QTY",0);
					$this->db->set("CG_YN","Y");
					$this->db->set("CG_DATE",$dateTime);
					$this->db->set("OUT_QTY",$actPln->QTY);
					$this->db->set("M_LINE",$actPln->M_LINE);
					$this->db->set("INSERT_ID",$param['userName']);
					$this->db->set("INSERT_DATE",$dateTime);
					$this->db->insert("T_ITEMS_TRANS");

				}else{

					$subquery1 = "(SELECT A.IDX FROM T_ITEMS as A WHERE A.BL_NO = '".$actPln->BL_NO."')";
					$this->db->set("H_IDX",$subquery1,false);
					$this->db->set("GJ_GB",$param['gjgb']);
					$this->db->set("TRANS_DATE",$dateTime);
					$this->db->set("KIND","IN");
					$this->db->set("ACT_IDX",$actPln->IDX);
					$this->db->set("IN_QTY",$actPln->QTY);
					$this->db->set("CG_YN","N");
					$this->db->set("PT",$actPln->PT);
					//$this->db->set("CG_DATE",$dateTime);
					$this->db->set("OUT_QTY",0);
					$this->db->set("M_LINE",$actPln->M_LINE);
					$this->db->set("INSERT_ID",$param['userName']);
					$this->db->set("INSERT_DATE",$dateTime);
					$this->db->insert("T_ITEMS_TRANS");

					$sql=<<<SQL
						UPDATE T_ITEMS SET STOCK = IFNULL(STOCK,0) + {$actPln->QTY}
						WHERE BL_NO = '{$actPln->BL_NO}'
SQL;
					$this->db->query($sql);

				}
				
				
				$this->db->where("BL_NO",$actPln->BL_NO);
				$qur = $this->db->get("T_BOM_V");
				$bom_query = $qur->result();
				if(!empty($bom_query)){
					
					$datax = array();
					foreach($bom_query as $bomV){

						$dataxx = array(
							"C_IDX"	=> $bomV->C_IDX,
							"TRANS_DATE" => $dateTime,
							"GJ_GB"      => $param['gjgb'],
							"KIND"       => "OT",
							"ACT_IDX"    => $actPln->IDX,
							"OUT_QTY"    => ($actPln->QTY * $bomV->POINT),
							"INSERT_ID"  => $param['userName'],
							"INSERT_DATE"=> $dateTime
						);
						array_push($datax,$dataxx);
						/*$this->db->set("C_IDX",$bomV->C_IDX);
						$this->db->set("TRANS_DATE",$dateTime);
						$this->db->set("KIND","OT");
						$this->db->set("ACT_IDX",$actPln->IDX);
						$this->db->set("OUT_QTY",($actPln->QTY * $bomV->POINT));*/
						
					}
					$this->db->insert_batch("T_COMPONENT_TRANS",$datax);

				}
			}

			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE)
			{
				$data['msg'] = "해당작업이 완료되었습니다.";

			}
			
		

		}else{

			$data['error'] = true;
			
		}
		
		die(json_encode($data));

		

		

	}

	

	public function set_actplan_barcode($param)
	{
		$this->db->set("BAR_CODE",$param['BAR_CODE']);
		$this->db->where("IDX",$param['IDX']);
		$this->db->update("T_ACTPLN");
	}


	public function ajax_mline_update($param)
	{
		$set = array(
			"M_LINE" => $param['LINE'],
			"PT"     => $param['PT']
		);

		$this->db->update("T_ACTPLN",$set,array("IDX"=>$param['IDX']));
		return $this->db->affected_rows();

	}



	public function get_rel_view($params)
	{
		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";
		$this->db->SELECT("*, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,6,2) AS MO , SUBSTR(INSERT_DATE,9,2) AS DA");

		$this->db->where("KPI_CODE",'SB');
		
		$this->db->order_by("INSERT_DATE","ASC");
	
		 

		$query = $this->db->get('T_KPI');
		//echo  $this->db->last_query();
		return $query->result();
			
	}
	public function get_rel_view1($params)
	{
		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";
		$this->db->SELECT("*, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,6,2) AS MO , SUBSTR(INSERT_DATE,9,2) AS DA");
		$this->db->where("KPI_CODE",'GJ');
		$this->db->order_by("INSERT_DATE","ASC");
	
		/*$sql=<<<SQL
			
			SELECT *, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,6,2) AS MO , SUBSTR(INSERT_DATE,9,2) AS DA
			FROM T_KPI
			WHERE ("KPI_CODE",$param['KPIGB'])
				ORDER BY INSERT_DATE ASC
SQL;
		$query = $this->db->query($sql);*/
		//echo nl2br($this->db->last_query());

		$query = $this->db->get('T_KPI');
		//echo  $this->db->last_query();
		return $query->result();
			
	}
	



}