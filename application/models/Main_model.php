<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();

			$this->load->model(array('act_model'));
	}



	public function set_temp_data($param,$BNO)
	{
		$data = array(
			'GJ_GB'        => $BNO,
			'LOT_NO'       => $param['item'][1],
			'BL_NO'        => $param['item'][2],
			'ST_DATE'      => $param['item'][3],
			'QTY'          => $param['item'][4],
			'UNIT'         => $param['item'][5],
			'STATE'        => $param['item'][6],
			'SASIZ'        => $param['item'][7],
			'PL_QTY'       => $param['item'][8],
			'GJ_CODE'      => $param['item'][9],
			'GJ_NAME'      => $param['item'][10],
			'GJ_QTY'       => $param['item'][11],
			'ACT_DATE'     => $param['item'][13],
			'PLN_DATE'     => $param['item'][14],
			'INSERT_DATE'  => date('Y-m-d H:i:s',time()),
			'INSERT_ID'    => $this->session->userdata('user_name'),
		);

		$this->db->insert($param['table'],$data);
	}

	
	/* T_COMPONENT_EX UPLOAD*/
	public function set_component_data($param)
	{
		$data = array(
			'GJ_GB'        => "SMT",
			'COMPONENT'    => $param['item'][0],
			'COMPONENT_NM' => $param['item'][1],
			'STOCK'        => $param['item'][2],
			'UNIT'         => $param['item'][3],
			'INTO_DATE'    => $param['item'][4],
			'REPL_DATE'    => $param['item'][5],
			'INSERT_DATE'  => date('Y-m-d H:i:s',time()),
			'INSERT_ID'    => $this->session->userdata('user_name')
		);	
		
		$this->db->insert($param['table'],$data);
	}



	/* T_COMPONENT_EX UPLOAD*/
	public function set_component_data_nx($param,$BNO)
	{
		$data = array(
			'GJ_GB'        => $BNO,
			'COMPONENT'    => $param['item'][0],
			'COMPONENT_NM' => $param['item'][1],
			'STOCK'        => $param['item'][2],
			'UNIT'         => $param['item'][3],
			'INTO_DATE'    => $param['item'][4],
			'REPL_DATE'    => $param['item'][5],
			'INSERT_DATE'  => date('Y-m-d H:i:s',time()),
			'INSERT_ID'    => $this->session->userdata('user_name')
		);	
		
		$this->db->insert($param['table'],$data);
	}


	public function get_gjgbinfo($bno)
	{
		$sql=<<<SQL
			SELECT
				GJ_GB
			FROM T_ITEMS
			WHERE
				BL_NO = '{$bno}'
SQL;
		$query = $this->db->query($sql);
		return $query->row();
	}


	public function get_gjgbinfo_comp($bno)
	{
		$sql=<<<SQL
			SELECT
				GJ_GB
			FROM T_COMPONENT
			WHERE
				COMPONENT = '{$bno}'
SQL;
		$query = $this->db->query($sql);
		return $query->row();
	}


	public function ajax_del_material($idx)
	{
		$this->db->where("IDX",$idx);
		$this->db->delete("T_ACTPLN");
		return $this->db->affected_rows();
	}



	public function set_matform_data($param,$BNO)
	{
		$data = array(
			'GJ_GB'        => $BNO,
			'IPGO_DATE'    => $param['item'][0],
			'COMPONENTNO'  => trim($param['item'][1]),
			'RANK'         => $param['item'][2],
			'NO'           => $param['item'][3],
			'LOT_NO'       => $param['item'][4],
			'QTY'          => $param['item'][5],
			'GUBUN'        => $param['item'][6],
			'STATE'        => $param['item'][7]
		);	
		
		$this->db->insert($param['table'],$data);
	}





	public function delete_component_ex()
	{
		$this->db->truncate("T_COMPONENT_EX");
	}


	public function delete_matform_ex()
	{
		$this->db->truncate("T_TEMP_COM");
	}


	public function delete_actpln_ex()
	{
		$this->db->truncate("T_ACTPLN_EX");
	}


	public function get_none_count()
	{
		$this->db->select("COUNT(*) AS XXX");
		$this->db->where("TC.COMPONENT",NULL);
		$this->db->from("T_COMPONENT as TC");
		$this->db->join("T_COMPONENT_EX as EX","EX.COMPONENT = TC.COMPONENT","right");
		$this->db->order_by("TC.STOCK","ASC");
		$query = $this->db->get();
				
		return $query->row()->XXX;

	}



	
	/* 공통코드 HEAD 등록 */
	public function codeHead_update($param)
	{

		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			
			$data = array(
				'CODE'        => $param['CODE'],
				'NAME'        => $param['NAME'],
				'REMARK'      => $param['REMARK'],
				'USE_YN'      => $param['USE_YN'],
				'UPDATE_ID'   => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_COCD_H",$data,array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());

			$data = array(
				'CODE'        => $param['CODE'],
				'NAME'        => $param['NAME'],
				'REMARK'      => $param['REMARK'],
				'USE_YN'      => $param['USE_YN'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->insert("T_COCD_H",$data);

			return $this->db->insert_id();

		}

		

	}


	/* 공통코드 Detail 등록 */
	public function codeDetail_update($param)
	{

		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			

			$data = array(
				'H_IDX'           => $param['H_IDX'],
				'S_NO'            => $param['S_NO'],
				'CODE'           => $param['CODE'],
				'NAME'           => $param['NAME'],
				'USE_YN'      => $param['USE_YN'],
				'REMARK'        => $param['REMARK'],
				'UPDATE_ID'    => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_COCD_D", $data, array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());
			
			$data = array(
				'H_IDX'       => $param['H_IDX'],
				'S_NO'        => $param['S_NO'],
				'CODE'        => $param['CODE'],
				'NAME'        => $param['NAME'],
				'USE_YN'      => $param['USE_YN'],
				'REMARK'      => $param['REMARK'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			
			$this->db->insert("T_COCD_D",$data);

			return $this->db->insert_id();

		}

		

	}



	/* 공통코드 HEAD 리스트 */
	public function get_cocdHead_list($params)
	{
		if(!empty($params['CODE']) && $params['CODE'] != ""){
			$this->db->like("CODE",$params['CODE']);
		}
		if(!empty($params['NAME']) && $params['NAME'] != "") {
			$this->db->like("NAME",$params['NAME']);
		}
		if(!empty($params['USE']) && $params['USE'] != "") {
			$this->db->where("USE_YN",$params['USE']);
		}

		$res = $this->db->get("T_COCD_H");
		return $res->result();

	}

	/* 공통코드 Detail 리스트 */
	public function get_cocdDetail_list($hid = "",$params)
	{
		$data = array();

		
		if($hid){
			
			$this->db->select("D.*,H.CODE as H_CODE");
			$this->db->from("T_COCD_D as D");
			$this->db->join("T_COCD_H as H","H.IDX = D.H_IDX");
			
			$this->db->where("D.H_IDX",$hid);

			if(!empty($params['D_CODE']) && $params['D_CODE'] != " "){
				$this->db->like("D.CODE",$params['D_CODE']);
			}
			if(!empty($params['D_NAME']) && $params['D_NAME'] != " ") {
				$this->db->like("D.NAME",$params['D_NAME']);
			}
			if(!empty($params['D_USE']) && $params['D_USE'] != " ") {
				$this->db->where("D.USE_YN",$params['D_USE']);
			}
			
			$this->db->order_by("S_NO","ASC");
			$res = $this->db->get();

			//echo $this->db->last_query();
			
			$data = $res->result();
			
		}
		
		return $data;

	}

	
	/* 공통코드 HEAD 상세정보 */
	public function get_cocdHead_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("T_COCD_H");
		return $res->row();
	}


	/* 공통코드 Detail 상세정보 */
	public function get_cocdDetail_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("T_COCD_D");
		return $res->row();
	}

	
	/*
	* 공통코드 헤드 삭제
	* 공통코드 디테일정보도 같이 삭제한다.
	*/
	public function delete_cocdHead($code)
	{
		$res = $this->db->delete("T_COCD_H",array('CODE'=>$code));
		$res1 = $this->db->delete("T_COCD_D",array('H_IDX'=>$code));

		return $this->db->affected_rows();
	}

	public function delete_cocdDetail($idx)
	{
		$res = $this->db->delete("T_COCD_D",array('IDX'=>$idx));
		return $this->db->affected_rows();
	}


	/* 코드중복검사 */
	public function ajax_cocdHaedchk($object,$code)
	{
		$this->db->where($object,$code);
        $query = $this->db->get('T_COCD_H');
         
        if($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
	}

	/* 코드중복검사 */
	public function ajax_cocdDetailchk($object,$code)
	{
		$this->db->where($object,$code);
        $query = $this->db->get('T_COCD_D');
         
        if($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
	}


	/*
	* 특정 공통코드의 디테일리스트를 호출
	*/
	public function get_selectInfo($fild,$set)
	{
		$where[$fild] = $set;
		$this->db->select("tch.IDX, tch.CODE, tch.NAME, tcd.CODE as D_CODE, tcd.NAME as D_NAME");
		$this->db->from("T_COCD_D as tcd");
		$this->db->join("T_COCD_H as tch","tch.IDX = tcd.H_IDX");
		
		$this->db->where("tcd.USE_YN","Y");
		$this->db->where("tch.USE_YN","Y");

		$this->db->where($where);
		$this->db->order_by("S_NO","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();

		return $query->result();		
	}



	/*
	* 특정 공통코드의 디테일리스트를 호출
	*/
	public function get_selectInfo_new($fild,$set,$gjgb)
	{
		$where[$fild] = $set;
		$this->db->select("tch.IDX, tch.CODE, tch.NAME, tcd.CODE as D_CODE, tcd.NAME as D_NAME");
		$this->db->from("T_COCD_D as tcd");
		$this->db->join("T_COCD_H as tch","tch.IDX = tcd.H_IDX");
		
		$this->db->where("tcd.USE_YN","Y");
		$this->db->where("tch.USE_YN","Y");

		$this->db->like("tcd.CODE",$gjgb);

		$this->db->where($where);
		$this->db->order_by("S_NO","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();		
	}




	public function barcode_scan_smt($params) 
	{
		$sysDate = date("Y-m-d",time());
		$todate = date("Y-m-d H:i:s",time());

		$userName = $this->session->userdata('user_name');
		$sql =<<<SQL
			SELECT COUNT(*) CNT FROM T_ACT_HIS WHERE BL_NO = '{$params['code']}' AND ACT_DATE = '{$sysDate}' AND GJ_GB = 'SMT'
SQL;
		$query = $this->db->query($sql);
		$CNT = $query->row()->CNT;
		
		if($CNT == 0){

			$sql=<<<SQL
				SELECT ITEM_NAME AS A, M_LINE AS B, MSAB AS C FROM T_ITEMS where BL_NO = "{$params['code']}" AND GJ_GB = 'SMT'
SQL;
			$query = $this->db->query($sql);
			$row1 = $query->row();
			
			$set = array(
				"GJ_GB"       => "SMT",
				"ACT_CD"      => "IN", 
				"ACT_NM"      => "자재투입",
				"ACT_DATE"    => $sysDate, 
				"BARCODE"     => $params['code'], 
				"BL_NO"       => $params['code'],
				"ITEM_NAME"   => $row1->A, 
				"M_LINE"      => $row1->B, 
				"MSAB"        => $row1->C, 
				"INSERT_ID"   => $userName,
				"INSERT_DATE" => $todate
			);
			$this->db->insert("T_ACT_HIS",$set);
			
           
           

		}elseif($CNT == 1){

			
			$sql=<<<SQL
				SELECT ITEM_NAME AS A, M_LINE AS B, MSAB AS C FROM T_ITEMS where BL_NO = "{$params['code']}" AND GJ_GB = 'SMT'
SQL;
			$query = $this->db->query($sql);
			$row1 = $query->row();
			
			$set = array(
				"GJ_GB"       => "SMT",
				"ACT_CD"      => "MAKE", 
				"ACT_NM"      => "제작완료",
				"ACT_DATE"    => $sysDate, 
				"BARCODE"     => $params['code'], 
				"BL_NO"       => $params['code'],
				"ITEM_NAME"   => $row1->A, 
				"M_LINE"      => $row1->B, 
				"MSAB"        => $row1->C, 
				"INSERT_ID"   => $userName,
				"INSERT_DATE" => $todate
			);

			$this->db->insert("T_ACT_HIS",$set);

		}
		else if($CNT == 2){

			$sql=<<<SQL
				SELECT ITEM_NAME AS A, M_LINE AS B, MSAB AS C FROM T_ITEMS where BL_NO = '{$params['code']}' AND GJ_GB = 'SMT'
SQL;
			$query = $this->db->query($sql);
			$row1 = $query->row();

			$set = array(
				"GJ_GB"       => "SMT",
				"ACT_CD"      => "END", 
				"ACT_NM"      => "검사완료",
				"ACT_DATE"    => $sysDate, 
				"BARCODE"     => $params['code'], 
				"BL_NO"       => $params['code'],
				"ITEM_NAME"   => $row1->A, 
				"M_LINE"      => $row1->B, 
				"MSAB"        => $row1->C, 
				"INSERT_ID"   => $userName,
				"INSERT_DATE" => $todate
			);
			$this->db->insert("T_ACT_HIS",$set);

			/* SMT 작업완료 start */
			$sql = "SELECT * FROM T_ACTPLN WHERE BL_NO = '{$params['code']}' AND (FINISH = '' OR FINISH IS NULL)";
			$NFdata = $this->db->query($sql)->row();	//No Finish data 
			if(!empty($NFdata))
			{
				$param['idx'] = $NFdata->IDX;
				$param['gjgb'] = "SMT";
				$param['userName'] = $this->session->userdata('user_name');
				$this->act_model->set_finish_actpln($param);
			}
			/* end */
			

		}elseif($CNT > 2){

			

		}
		

		


	}


	public function barcode_scan_ass($params) 
	{
		$sysDate = date("Y-m-d",time());
		$todate = date("Y-m-d H:i:s",time());

		$userName = $this->session->userdata('user_name');
		$sql =<<<SQL
			SELECT COUNT(*) CNT FROM T_ACT_HIS WHERE BL_NO = '{$params['code']}' AND ACT_DATE = '{$sysDate}' AND GJ_GB = 'ASS'
SQL;
		$query = $this->db->query($sql);
		$CNT = $query->row()->CNT;

		
		
		if($CNT == 0){

			$sql=<<<SQL
				SELECT ITEM_NAME AS A, M_LINE AS B, MSAB AS C FROM T_ITEMS where BL_NO = '{$params['code']}' AND GJ_GB = 'ASS'
SQL;
			$query = $this->db->query($sql);
			$row1 = $query->row();
			
			$set = array(
				"GJ_GB"       => "ASS",
				"ACT_CD"      => "MAKE", 
				"ACT_NM"      => "제작완료",
				"ACT_DATE"    => $sysDate, 
				"BARCODE"     => $params['code'], 
				"BL_NO"       => $params['code'],
				"ITEM_NAME"   => $row1->A, 
				"M_LINE"      => $row1->B,
				"MSAB"        => $row1->C,
				"INSERT_ID"   => $userName,
				"INSERT_DATE" => $todate
			);
			
           $this->db->insert("T_ACT_HIS",$set);
           

		}elseif($CNT == 1){

			
			$sql=<<<SQL
				SELECT ITEM_NAME AS A, M_LINE AS B, MSAB AS C FROM T_ITEMS where BL_NO = '{$params['code']}' AND GJ_GB = 'ASS'
SQL;
			$query = $this->db->query($sql);
			$row1 = $query->row();
			
			$set = array(
				"GJ_GB"       => "ASS",
				"ACT_CD"      => "END", 
				"ACT_NM"      => "검사완료",
				"ACT_DATE"    => $sysDate, 
				"BARCODE"     => $params['code'], 
				"BL_NO"       => $params['code'],
				"ITEM_NAME"   => $row1->A, 
				"M_LINE"      => $row1->B, 
				"MSAB"        => $row1->C, 
				"INSERT_ID"   => $userName,
				"INSERT_DATE" => $todate
			);

			$this->db->insert("T_ACT_HIS",$set);

			/* ASS 작업완료  start*/
			$sql = "SELECT * FROM T_ACTPLN WHERE BL_NO = '{$params['code']}' AND (FINISH = '' OR FINISH IS NULL)";
			$NFdata = $this->db->query($sql)->row();	//No Finish data 
			if(!empty($NFdata))
			{
				$param['idx'] = $NFdata->IDX;
				$param['gjgb'] = "ASS";
				$param['userName'] = $this->session->userdata('user_name');
				$this->act_model->set_finish_actpln($param);
			}
			/* end */
		
		}elseif($CNT > 2){

		}

	}

	/*
	* 업체 호출
	*/
	public function get_accountlist()
	{
		$this->db->select("IDX,CUST_NM");
		$this->db->from("T_BIZ_REG");
		
		$this->db->where("USE_YN","Y");

		$this->db->order_by("IDX","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();

		return $query->result();		
	}


}