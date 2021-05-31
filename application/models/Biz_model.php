<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biz_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}



	/* ��ü ����Ʈ */
	public function get_bizReg_list($params)
	{
		if(!empty($params['CUST_NM']) && $params['CUST_NM'] != ""){
			$this->db->like("CUST_NM",$params['CUST_NM']);
		}
		if(!empty($params['ADDRESS']) && $params['ADDRESS'] != "") {
			$this->db->like("ADDRESS",$params['ADDRESS']);
		}

		$res = $this->db->get("T_BIZ_REG");
		return $res->result();

	}

	

	/* ��ü ��� */
	public function bizReg_update($param)
	{

		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			
			$data = array(
				'CUST_NM'        => $param['CUST_NM'],
				'ADDRESS'        => $param['ADDRESS'],
				'TEL'                 => $param['TEL'],
				'CUST_NAME'    => $param['CUST_NAME'],
				'ITEM'               => $param['ITEM'],
				'REMARK'          => $param['REMARK'],
				'USE_YN'          => $param['USE_YN'],
				'UPDATE_ID'      => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_BIZ_REG",$data,array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());

			$data = array(
				'CUST_NM'        => $param['CUST_NM'],
				'ADDRESS'        => $param['ADDRESS'],
				'TEL'                 => $param['TEL'],
				'CUST_NAME'    => $param['CUST_NAME'],
				'ITEM'               => $param['ITEM'],
				'REMARK'          => $param['REMARK'],
				'USE_YN'          => $param['USE_YN'],
				'INSERT_ID'       => $param['INSERT_ID'],
				'INSERT_DATE'  => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);

			

			$this->db->insert("T_BIZ_REG",$data);

			return $this->db->insert_id();

		}

		

	}




	/* ��ü���� ������ */
	public function get_bizReg_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("T_BIZ_REG");
		return $res->row();
	}


	public function delete_bizReg($idx)
	{
		$res = $this->db->delete("T_BIZ_REG",array('IDX'=>$idx));
		return $this->db->affected_rows();
	}


	/*
	* Ư�� �����ڵ��� �����ϸ���Ʈ�� ȣ��
	*/
	public function get_selectInfo()
	{
		$this->db->select("CUST_NM");
		$this->db->group_by("CUST_NM");
		$query = $this->db->get("T_BIZ_REG");
		return $query->result();		
	}

	


}