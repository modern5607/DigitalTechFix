<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpi_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}


	public function equip_chart() 
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
	
	public function fair_chart() 
	{

	}

	public function equip_list() 
	{

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