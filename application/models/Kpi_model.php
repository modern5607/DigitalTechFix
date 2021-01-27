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

	public function fair_list() 
	{

	}



}