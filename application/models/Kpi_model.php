<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpi_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}


	public function equip_chart($params,$start=0,$limit=20) 
	{
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}
		if(!empty($params['CHART'])){
			$this->db->order_by("INSERT_DATE","ASC");
		}else{
			$this->db->order_by("INSERT_DATE","DESC");
		}
		
		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";
		$this->db->SELECT("*,78 AS ACT, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,6,2) AS MO , SUBSTR(INSERT_DATE,9,2) AS DA");
		$this->db->where("KPI_CODE",'SB');
		$this->db->limit($limit,$start);

		$query = $this->db->get('T_KPI');
//		 echo  $this->db->last_query();
		return $query->result();
	}
	
	public function fair_chart($params,$start=0,$limit=20) 
	{
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}
		if(!empty($params['CHART'])){
			$this->db->order_by("INSERT_DATE","ASC");
		}else{
			$this->db->order_by("INSERT_DATE","DESC");
		}

		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";
		$this->db->SELECT("*,800 AS ACT, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,6,2) AS MO , SUBSTR(INSERT_DATE,9,2) AS DA");
		$this->db->where("KPI_CODE",'GJ');
		$this->db->limit($limit,$start);

		$query = $this->db->get('T_KPI');
		// echo  $this->db->last_query();
		return $query->result();
	}

	public function equip_cut($params) 
	{
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}else{
			$this->db->where("INSERT_DATE > DATE_ADD(now(), INTERVAL -1 month)");
		}
		
		
		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("T_KPI");
		$this->db->where("KPI_CODE",'SB');

		$query = $this->db->get();
		return $query->row()->CUT;
	}

	public function fair_cut($params) 
	{
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}else{
			$this->db->where("INSERT_DATE > DATE_ADD(now(), INTERVAL -1 month)");
		}
		
		
		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("T_KPI");
		$this->db->where("KPI_CODE",'GJ');

		$query = $this->db->get();
		return $query->row()->CUT;
	}




	public function equip_list($params) 
	{
		$where = '';
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$where .= " AND DATE_FORMAT( A.END_DATE, '%Y-%m-%d' ) BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'";
		}

		$where2 = '';
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$where2 .= " AND INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'";
		}


		$sql=<<<SQL
			SELECT 
				AA.*
			FROM 
				(
					SELECT
						A.PLN_NO,
						A.BL_NO,
						ROUND( ( A.QTY * A.PT ) / 60 ) AS ACT_TIME,
						A.QTY,
						A.PT,
						ROUND( ( ( ( A.QTY * A.PT ) / 60 ) / B.ACT_TIME ) * 100 ) AS AC_TIME,
						A.END_DATE,
						( SELECT NAME FROM T_COCD_D WHERE CODE = A.M_LINE ) AS M_LINE,
						'1' AS SEQ 
					FROM
					T_ACTPLN A, 
					T_ACT_ILBO B
					WHERE
						DATE_FORMAT( A.END_DATE, '%Y-%m-%d' ) = DATE_FORMAT( B.ORDER_DATE, '%Y-%m-%d' ) 
						{$where}
				) as AA
			UNION
			SELECT 
				'',
				'일일 평균' AS BL_NO, 
				'',
				'',
				'',
				AC_KPI,INSERT_DATE,
				'전체',
				'2' AS SEQ
			FROM 
				T_KPI
			WHERE 
				KPI_CODE ="SB"
				{$where2}
			ORDER BY  DATE_FORMAT(END_DATE,'%Y-%m-%d') DESC, M_LINE , SEQ
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->result();
	}
	
	public function equip_mean($params) 
	{
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		
		$this->db->select("'전체 평균' AS TEXT, SUM(AC_KPI) / COUNT(*) AS AV_CNT");
		$this->db->where('KPI_CODE ="SB"');
		$this->db->from("T_KPI");

		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}
	

	public function fair_list_cnt($param)
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
			
			GROUP BY
				DATE
			ORDER BY 
				DATE DESC
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->num_rows();

	}
	public function get_sold_All($params,$start,$limit)
	{
		$sql= <<<SQL
			SELECT
			ID,FLUX_TIME,FLUX_WEIGHT,SOLDER_TIME,PREHEAT_TIME,SOLDER_TEMP,TACT_TIME, DATE_FORMAT(PRODUCT_TIME,"%Y-%m-%d") AS PRODUCT_TIME
			FROM
				`T_SOLD_HISTORY`
			WHERE
				INSERT_DATE BETWEEN '{$params['INSERT_DATE']} 00:00:00' AND '{$params['INSERT_DATE']} 23:59:59'
			ORDER BY
				ID DESC
			LIMIT
			{$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->result();
	}
	/* 공정불량률 리스트 */
	public function fair_list($param,$start=0,$limit=20) 
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
	public function get_sold_All_cut($params)
	{
		$sql= <<<SQL
			SELECT
			ID,FLUX_TIME,FLUX_WEIGHT,SOLDER_TIME,PREHEAT_TIME,SOLDER_TEMP,TACT_TIME, DATE_FORMAT(PRODUCT_TIME,"%Y-%m-%d") AS PRODUCT_TIME
			FROM
				`T_SOLD_HISTORY`
			WHERE
				INSERT_DATE BETWEEN '{$params['INSERT_DATE']} 00:00:00' AND '{$params['INSERT_DATE']} 23:59:59'
			ORDER BY
				ID DESC
SQL;
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
}