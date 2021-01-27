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
		
		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";
		$this->db->SELECT("*, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,6,2) AS MO , SUBSTR(INSERT_DATE,9,2) AS DA");
		$this->db->where("KPI_CODE",'SB');
		$this->db->order_by("INSERT_DATE","DESC");
		$this->db->limit($limit,$start);

		$query = $this->db->get('T_KPI');
		// echo  $this->db->last_query();
		return $query->result();
	}
	
	public function fair_chart($params,$start=0,$limit=20) 
	{
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}
		
		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";
		$this->db->SELECT("*, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,6,2) AS MO , SUBSTR(INSERT_DATE,9,2) AS DA");
		$this->db->where("KPI_CODE",'GJ');
		$this->db->order_by("INSERT_DATE","DESC");
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



}