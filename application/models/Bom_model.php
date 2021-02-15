<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bom_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}




	public function get_items_list($params,$start=0,$limit=20)
	{
		$where = "";
		if($params['BL_NO'] != ""){
			$where .= " AND BL_NO LIKE '%".$params['BL_NO']."%'";
		}
		if($params['ITEM_NAME'] != ""){
			$where .= " AND ITEM_NAME LIKE '%".$params['ITEM_NAME']."%'";
		}
		if($params['M_LINE'] != ""){
			$where .= " AND M_LINE = '".$params['M_LINE']."'";
		}
		if($params['GJ_GB'] != ""){
			$where .= " AND GJ_GB = '".$params['GJ_GB']."'";
		}
		if($params['USE_YN'] != ""){
			$where .= " AND USE_YN = '".$params['USE_YN']."'";
		}


		$sql=<<<SQL
			SELECT
				ti.BL_NO, ti.ITEM_NAME, ti.IDX, ti.M_LINE, ti.GJ_GB, ti.USE_YN,
				(SELECT tcd1.NAME FROM T_COCD_D as tcd1 WHERE tcd1.CODE = ti.1ST_CLASS) as CLASS1,
				(SELECT tcd2.NAME FROM T_COCD_D as tcd2 WHERE tcd2.CODE = ti.2ND_CLASS) as CLASS2,
				(SELECT tcd3.NAME FROM T_COCD_D as tcd3 WHERE tcd3.CODE = ti.MSAB) as MSAB,
				(SELECT tcd4.NAME FROM T_COCD_D as tcd4 WHERE tcd4.CODE = ti.STATE) as STATE,
				(SELECT COUNT(tb.IDX) FROM T_BOM as tb WHERE tb.H_IDX = ti.IDX) as C_COUNT
			FROM
				T_ITEMS as ti
			WHERE
				1
				{$where}
			ORDER BY BL_NO ASC, IDX DESC 
			LIMIT {$start},{$limit}
SQL;
		
		
		$query = $this->db->query($sql);

		// echo $this->db->last_query();
		
		return $query->result();
		
	}

	public function get_items_cut($params)
	{
		$where = "";
		if($params['BL_NO'] != ""){
			$where .= " AND BL_NO LIKE '%".$params['BL_NO']."%'";
		}
		if($params['ITEM_NAME'] != ""){
			$where .= " AND ITEM_NAME LIKE '%".$params['ITEM_NAME']."%'";
		}
		if($params['MSAB'] != ""){
			$where .= " AND MSAB = '".$params['MSAB']."'";
		}
		if($params['M_LINE'] != ""){
			$where .= " AND M_LINE = '".$params['M_LINE']."'";
		}
		if($params['GJ_GB'] != ""){
			$where .= " AND GJ_GB = '".$params['GJ_GB']."'";
		}
		if($params['USE_YN'] != ""){
			$where .= " AND USE_YN = '".$params['USE_YN']."'";
		}
		$sql=<<<SQL
			SELECT
				COUNT(ti.IDX) as cnt
			FROM
				T_ITEMS as ti
			WHERE
				1
				{$where}
SQL;
		$query = $this->db->query($sql);

		return $query->row()->cnt;
		
	}

	public function get_level2_items($params,$start=0,$limit=20)
	{
		$where = "";
		// if($params['BL_NO'] != ""){
		// 	$where .= " AND BL_NO LIKE '%".$params['BL_NO']."%'";
		// }
		if($params['COMPONENT_NM'] != ""){
			$where .= "AND (COMPONENT_NM LIKE '%ASSY%' OR COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%')";
		}
		else
			$where .= "AND COMPONENT_NM LIKE '%ASSY%'";
		if($params['M_LINE'] != ""){
			$where .= " AND M_LINE = '".$params['M_LINE']."'";
		}
		// if($params['USE_YN'] != ""){
		// 	$where .= " AND USE_YN = '".$params['USE_YN']."'";
		// }


		$sql=<<<SQL
			SELECT
				B.BL_NO AS BL_NO,
				C.COMPONENT_NM AS COMPONENT_NM,
				B.IDX AS H_IDX,
				C.IDX AS C_IDX,
				( SELECT NAME FROM T_COCD_D WHERE CODE = B.GJ_GB ) AS GJ_GB,
				( SELECT NAME FROM T_COCD_D WHERE CODE = B.M_LINE ) AS M_LINE,
				B.GJ_GB AS GJ_CD,
				( SELECT COUNT( IDX ) FROM T_2BOM WHERE H_IDX = B.IDX AND C_IDX = C.IDX ) AS C_COUNT 
			FROM
				T_BOM A,
				T_ITEMS B,
				T_COMPONENT C 
			WHERE
				A.H_IDX = B.IDX 
				AND B.GJ_GB = 'ASS' 
				AND A.C_IDX = C.IDX 
				{$where}
			ORDER BY BL_NO, COMPONENT_NM
			LIMIT {$start},{$limit}
SQL;
		
		
		$query = $this->db->query($sql);

		// echo $this->db->last_query();
		
		return $query->result();
		
	}

	public function get_level2_cut($params)
	{
		$where = "";
		if($params['BL_NO'] != ""){
			$where .= " AND BL_NO LIKE '%".$params['BL_NO']."%'";
		}
		if($params['COMPONENT_NM'] != ""){
			$where .= "AND (C.COMPONENT_NM LIKE '%ASSY%' OR C.COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%')";
		}
		else
			$where .= "AND C.COMPONENT_NM LIKE '%ASSY%'";
		if($params['M_LINE'] != ""){
			$where .= " AND M_LINE = '".$params['M_LINE']."'";
		}
		// if($params['USE_YN'] != ""){
		// 	$where .= " AND USE_YN = '".$params['USE_YN']."'";
		// }


		$sql=<<<SQL
			SELECT
				COUNT(B.BL_NO) cut
			FROM
				T_BOM A,
				T_ITEMS B,
				T_COMPONENT C 
			WHERE
				A.H_IDX = B.IDX 
				AND B.GJ_GB = 'ASS' 
				AND A.C_IDX = C.IDX 
				{$where}
			ORDER BY BL_NO, COMPONENT_NM
SQL;
		
		
		$query = $this->db->query($sql);

		// echo $this->db->last_query();
		
		return $query->row()->cut;

	}

	public function get_level2_Rlist($hidx,$cidx)
	{
		
		$where ='';

		$sql=<<<SQL
			SELECT
				A.IDX,
				B.IDX AS BIDX,
				A.COMPONENT,
				A.COMPONENT_NM,
				A.UNIT,
				A.PRICE,
				A.PT,
				A.REEL_CNT,
				B.POINT
			FROM
				T_2LVCOMP A,
				T_2BOM B 
			WHERE
				A.IDX = B.L2_IDX 
				AND B.H_IDX = '{$hidx}'
				AND B.C_IDX = '{$cidx}'
			ORDER BY
				COMPONENT

SQL;
		
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->result();

	}

	public function get_level3_items($params,$start=0,$limit=20)
	{
		$where = "";
		// if($params['BL_NO'] != ""){
		// 	$where .= "AND BL_NO LIKE '%".$params['BL_NO']."%'";
		// }
		if($params['COMPONENT_NM'] != ""){
			$where .= "AND D.COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%'";
		}
		if($params['M_LINE'] != ""){
			$where .= "AND M_LINE = '".$params['M_LINE']."'";
		}


		$sql=<<<SQL
			SELECT
				B.BL_NO AS BL_NO,
				C.COMPONENT_NM AS COMPONENT_NM,
				D.COMPONENT_NM AS L2COMP_NM,
				B.IDX AS H_IDX,
				C.IDX AS C_IDX,
				D.IDX AS L2_IDX,
				( SELECT NAME FROM T_COCD_D WHERE CODE = B.GJ_GB ) AS GJ_GB,
				B.GJ_GB AS GJ_CD,
				(SELECT NAME FROM T_COCD_D WHERE CODE = B.M_LINE ) AS M_LINE,
				(SELECT
					COUNT( IDX ) 
				FROM
					T_3BOM 
				WHERE
					H_IDX = B.IDX 
					AND C_IDX = C.IDX 
					AND L2_IDX = D.IDX 
				) AS C_COUNT 	
			FROM
				T_2BOM A,
				T_ITEMS B,
				T_COMPONENT C,
				T_2LVCOMP D 
			WHERE
				A.H_IDX = B.IDX 
				AND A.C_IDX = C.IDX 
				AND A.L2_IDX = D.IDX 
				AND B.GJ_GB = 'ASS' 
				{$where}
			ORDER BY
				BL_NO,
				COMPONENT_NM,
				L2COMP_NM
			LIMIT {$start},{$limit}
SQL;
		
		
		$query = $this->db->query($sql);

		//  echo $this->db->last_query();
		return $query->result();
		
	}


	public function get_level3_cut($params)
	{
		$where = "";
		// if($params['BL_NO'] != ""){
		// 	$where .= " AND BL_NO LIKE '%".$params['BL_NO']."%'";
		// }
		if($params['COMPONENT_NM'] != ""){
			$where .= " AND COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%'";
		}
		if($params['M_LINE'] != ""){
			$where .= " AND M_LINE = '".$params['M_LINE']."'";
		}


		$sql=<<<SQL
			SELECT
				COUNT(B.BL_NO) AS cut
			FROM
				T_2BOM A,
				T_ITEMS B,
				T_COMPONENT C,
				T_2LVCOMP D 
			WHERE
				A.H_IDX = B.IDX 
				AND A.C_IDX = C.IDX 
				AND A.L2_IDX = D.IDX 
				AND B.GJ_GB = 'ASS'
SQL;
		
		
		$query = $this->db->query($sql);

		//  echo $this->db->last_query();
		return $query->row()->cut;
	}


	public function get_level3_Rlist($hidx,$cidx,$l2idx)
	{


		$sql=<<<SQL
			SELECT
				A.COMPONENT,
				B.IDX AS BIDX,
				A.COMPONENT_NM,
				A.UNIT,
				A.PRICE,
				A.PT,
				A.REEL_CNT,
				B.POINT
			FROM
				T_3LVCOMP A,
				T_3BOM B 
			WHERE
				A.IDX = B.L3_IDX 
				AND B.L2_IDX = '{$l2idx}'
				AND B.H_IDX = '{$hidx}'
				AND B.C_IDX = '{$cidx}'
			ORDER BY
				COMPONENT
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->result();
	}

	public function get_level3Bom_material($params,$start=0,$limit=20)
	{
		$sql=<<<SQL
			SELECT
			tc.*,
				(
				SELECT 
					COUNT( tb.IDX ) 
				FROM 
					T_3BOM AS tb 
				WHERE 
					tb.H_IDX = "{$params['H_IDX']}" 
					AND tb.C_IDX = "{$params['C_IDX']}" 
					AND tb.L2_IDX = "{$params['L2_IDX']}"
					AND tb.L3_IDX = tc.IDX 
				) AS CHKBOM 
			FROM
				T_3LVCOMP AS tc 
			WHERE
				tc.GJ_GB = "ASS"
			ORDER BY
				CHKBOM DESC,
				tc.COMPONENT,
				tc.IDX DESC 
				LIMIT 0,50
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function get_level3Bom_material_cut($params)
	{
		$sql=<<<SQL
			SELECT
			COUNT(*) AS cut,
				(SELECT 
					COUNT( tb.IDX ) 
				FROM 
					T_3BOM AS tb 
				WHERE
					tb.H_IDX = "{$params['H_IDX']}" 
					AND tb.C_IDX = "{$params['C_IDX']}" 
					AND tb.L2_IDX = "{$params['L2_IDX']}"
					AND tb.L3_IDX = tc.IDX 
				) AS CHKBOM 
			FROM
				T_3LVCOMP AS tc 
			WHERE
				tc.GJ_GB = "ASS"
			ORDER BY
				CHKBOM DESC,
				tc.COMPONENT,
				tc.IDX DESC 
				LIMIT 0,50
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->cut;

	}



	public function get_testUpdate()
	{
		$this->db->insert("T_ACTPLN",array("BL_NO"=>"1AD-SA03811BA"));
	}

	public function get_items_list_item($params,$start=0,$limit=20)
	{
		$where = "";
		if($params['BL_NO'] != ""){
			$where .= " AND BL_NO LIKE '%".$params['BL_NO']."%'";
		}
		if($params['ITEM_NAME'] != ""){
			$where .= " AND ITEM_NAME LIKE '%".$params['ITEM_NAME']."%'";
		}
		if($params['MSAB'] != ""){
			$where .= " AND MSAB like '%".$params['MSAB']."%'";
		}
		if($params['M_LINE'] != ""){
			$where .= " AND M_LINE = '".$params['M_LINE']."'";
		}
		if($params['GJ_GB'] != ""){
			$where .= " AND GJ_GB = '".$params['GJ_GB']."'";
		}
		if($params['USE_YN'] != ""){
			$where .= " AND USE_YN = '".$params['USE_YN']."'";
		}


		$sql=<<<SQL
			SELECT
				ti.BL_NO, ti.ITEM_NAME, ti.IDX, ti.M_LINE, ti.GJ_GB, ti.USE_YN,
				(SELECT tcd1.NAME FROM T_COCD_D as tcd1 WHERE tcd1.CODE = ti.1ST_CLASS) as CLASS1,
				(SELECT tcd2.NAME FROM T_COCD_D as tcd2 WHERE tcd2.CODE = ti.2ND_CLASS) as CLASS2,
				(SELECT tcd3.NAME FROM T_COCD_D as tcd3 WHERE tcd3.CODE = ti.MSAB) as MSAB,
				(SELECT tcd4.NAME FROM T_COCD_D as tcd4 WHERE tcd4.CODE = ti.STATE) as STATE,
				(SELECT COUNT(tb.IDX) FROM T_BOM as tb WHERE tb.H_IDX = ti.IDX) as C_COUNT
			FROM
				T_ITEM_D as ti
			WHERE
				1
				{$where}
			ORDER BY BL_NO ASC, IDX DESC 
			LIMIT {$start},{$limit}
SQL;
		
		
		$query = $this->db->query($sql);
// echo $this->db->last_query();

		return $query->result();
		
	}


	public function get_items_cut_item($params)
	{
		$where = "";
		if($params['BL_NO'] != ""){
			$where .= " AND BL_NO LIKE '%".$params['BL_NO']."%'";
		}
		if($params['ITEM_NAME'] != ""){
			$where .= " AND ITEM_NAME LIKE '%".$params['ITEM_NAME']."%'";
		}
		if($params['MSAB'] != ""){
			$where .= " AND MSAB = '".$params['MSAB']."'";
		}
		if($params['M_LINE'] != ""){
			$where .= " AND M_LINE = '".$params['M_LINE']."'";
		}
		if($params['GJ_GB'] != ""){
			$where .= " AND GJ_GB = '".$params['GJ_GB']."'";
		}
		if($params['USE_YN'] != ""){
			$where .= " AND USE_YN = '".$params['USE_YN']."'";
		}
		$sql=<<<SQL
			SELECT
				COUNT(ti.IDX) as cnt
			FROM
				T_ITEM_D as ti
			WHERE
				1
				{$where}
SQL;
		$query = $this->db->query($sql);

		return $query->row()->cnt;
		
	}

	public function get_trans_list($params,$start=0,$limit=20)
	{
		$subquery = "(SELECT TI.ITEM_NAME FROM T_ITEMS AS TI WHERE TI.BL_NO = TA.BL_NO AND TI.GJ_GB = TA.GJ_GB) AS ITEM_NAME";
		$kind = "(SELECT K.NAME FROM T_COCD_D as K WHERE K.H_IDX = 17 AND K.CODE = TCT.KIND) as KIND";
		$this->db->select("TC.COMPONENT, TC.COMPONENT_NM, TC.USE_YN, {$kind}, TCT.OUT_QTY, TCT.TRANS_DATE, TCT.IDX as TIDX, TA.BL_NO, TA.QTY, TA.UNIT, {$subquery}");
		
		$this->db->from("T_COMPONENT_TRANS AS TCT");
		$this->db->join("T_COMPONENT AS TC","TC.IDX = TCT.C_IDX","left");
		$this->db->join("T_ACTPLN AS TA","TA.IDX = TCT.ACT_IDX","left");

		$this->db->where("(TCT.KIND = 'OT' OR TCT.KIND = 'MOT')");
		
		if($params['BL_NO'] != ""){
			$this->db->like("TA.BL_NO",$params['BL_NO']);
		}
		if($params['GJ_GB'] != ""){
			$this->db->where("TA.GJ_GB",$params['GJ_GB']);
		}
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("TCT.TRANS_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}
		if($params['ITEM'] != ""){
			$this->db->like("TC.COMPONENT",$params['ITEM']);
		}
		if($params['M_LINE'] != ""){
			$this->db->where("TA.M_LINE",$params['M_LINE']);
		}


		$this->db->limit($limit,$start);
		$this->db->order_by("TCT.TRANS_DATE","DESC");
		$query = $this->db->get();

		//echo $this->db->last_query();
		return $query->result();
	}


	public function get_trans_list_nolimit($params)
	{
		$subquery = "(SELECT TI.ITEM_NAME FROM T_ITEMS AS TI WHERE TI.BL_NO = TA.BL_NO AND TI.GJ_GB = TA.GJ_GB) AS ITEM_NAME";
		$kind = "(SELECT K.NAME FROM T_COCD_D as K WHERE K.H_IDX = 17 AND K.CODE = TCT.KIND) as KIND";
		$this->db->select("TC.COMPONENT, TC.COMPONENT_NM, TC.USE_YN, {$kind}, TCT.OUT_QTY, TCT.TRANS_DATE, TCT.IDX as TIDX, TA.BL_NO, TA.QTY, TA.UNIT, {$subquery}");
		
		$this->db->from("T_COMPONENT_TRANS AS TCT");
		$this->db->join("T_COMPONENT AS TC","TC.IDX = TCT.C_IDX","left");
		$this->db->join("T_ACTPLN AS TA","TA.IDX = TCT.ACT_IDX","left");

		$this->db->where("(TCT.KIND = 'OT' OR TCT.KIND = 'MOT')");
		
		if($params['BL_NO'] != ""){
			$this->db->like("TA.BL_NO",$params['BL_NO']);
		}
		if($params['GJ_GB'] != ""){
			$this->db->where("TA.GJ_GB",$params['GJ_GB']);
		}
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("TCT.TRANS_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}
		if($params['ITEM'] != ""){
			$this->db->like("TC.COMPONENT",$params['ITEM']);
		}
		if($params['M_LINE'] != ""){
			$this->db->where("TA.M_LINE",$params['M_LINE']);
		}


		
		$this->db->order_by("TCT.TRANS_DATE","DESC");
		$query = $this->db->get();

		
		return $query->result();
	}


	public function get_trans_cut($params)
	{
		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("T_COMPONENT_TRANS AS TCT");
		$this->db->join("T_COMPONENT AS TC","TC.IDX = TCT.C_IDX","left");
		$this->db->join("T_ACTPLN AS TA","TA.IDX = TCT.ACT_IDX","left");

		$this->db->where("(TCT.KIND = 'OT' OR TCT.KIND = 'MOT')");
		
		if($params['BL_NO'] != ""){
			$this->db->like("TA.BL_NO",$params['BL_NO']);
		}
		if($params['GJ_GB'] != ""){
			$this->db->where("TA.GJ_GB",$params['GJ_GB']);
		}
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("TCT.TRANS_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}
		if($params['ITEM'] != ""){
			$this->db->like("TC.COMPONENT",$params['ITEM']);
		}
		if($params['M_LINE'] != ""){
			$this->db->where("TA.M_LINE",$params['M_LINE']);
		}

		$query = $this->db->get();
		return $query->row()->CUT;
	}





	public function get_matform_list($param,$start=0,$limit=20)
	{
		
		if((!empty($param['TDATE1']) && $param['TDATE1'] != "") && (!empty($param['TDATE2']) && $param['TDATE2'] != "")){
			$this->db->where("TCT.TRANS_DATE BETWEEN '{$param['TDATE1']} 00:00:00' AND '{$param['TDATE2']} 23:59:59'");
		}

		$kind = "(SELECT K.NAME FROM T_COCD_D as K WHERE K.H_IDX = 17 AND K.CODE = TCT.KIND) as KIND";
		$this->db->select("TCT.*,TCT.IDX as TIDX, TC.COMPONENT, TC.COMPONENT_NM, {$kind}");
		$this->db->where("TCT.KIND","IN");
		$this->db->from("T_COMPONENT_TRANS AS TCT");
		$this->db->join("T_COMPONENT AS TC","TC.IDX = TCT.C_IDX","left");
		$this->db->order_by("TRANS_DATE","DESC");
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}



	public function get_matform_cut($param)
	{
		
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->like("TCT.GJ_GB",$param['GJ_GB']);
		}
		if((!empty($param['TDATE1']) && $param['TDATE1'] != "") && (!empty($param['TDATE2']) && $param['TDATE2'] != "")){
			$this->db->where("TCT.TRANS_DATE BETWEEN '{$param['TDATE1']} 00:00:00' AND '{$param['TDATE2']} 23:59:59'");
		}
		$this->db->where("TCT.KIND","IN");

		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("T_COMPONENT_TRANS AS TCT");
		$this->db->join("T_COMPONENT AS TC","TC.IDX = TCT.C_IDX","left");
		$query = $this->db->get();
		return $query->row()->CUT;
	}








	public function set_itemsInsert($params)
	{
		$this->db->insert("T_ITEMS",$params);
		return $this->db->insert_id();
	}

	public function set_itemsInsert_item($params)
	{
		$this->db->insert("T_ITEM_D",$params);
		return $this->db->insert_id();
	}

	public function set_itemsUpdate($params,$idx)
	{
		$this->db->update("T_ITEMS",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}


	public function set_itemsUpdate_item($params,$idx)
	{
		$this->db->update("T_ITEM_D",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}


	public function get_items_info($idx)
	{
		$this->db->select("*,1ST_CLASS as CLASS1, 2ND_CLASS as CLASS2, 2ND_LINE as LINE2, 3ND_LINE as LINE3, 2ND_P_T as PT2, 3ND_P_T as PT3 ");
		$this->db->where(array("IDX"=>$idx));
		$data = $this->db->get("T_ITEMS");
		// echo $this->db->last_query();
		
		return $data->row();
	}

	public function get_items_info_item($idx)
	{
		$this->db->select("*,1ST_CLASS as CLASS1, 2ND_CLASS as CLASS2, 2ND_LINE as LINE2, 3ND_LINE as LINE3, 2ND_P_T as PT2, 3ND_P_T as PT3 ");
		$this->db->where(array("IDX"=>$idx));
		$data = $this->db->get("T_ITEM_D");
		return $data->row();
	}



	/* marterials */
	public function get_material_list($params,$start=0,$limit=20)
	{
		
		if($params['COMPONENT'] != ""){
			$this->db->where("COMPONENT LIKE '%".$params['COMPONENT']."%'");
		}
		if($params['COMPONENT_NM'] != ""){
			$this->db->where("COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%'");
		}
		if($params['SPEC'] != ""){
			$this->db->where("SPEC",$params['SPEC']);
		}
		if($params['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$params['GJ_GB']);
		}

		if($params['USE_YN'] != ""){
			$this->db->where("USE_YN",$params['USE_YN']);
		}
		/*
		if($params['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$params['GJ_GB']);
		}
		if($params['USE_YN'] != ""){
			$this->db->where("USE_YN",$params['USE_YN']);
		}*/

		$this->db->order_by("COMPONENT_NM","ASC");
		$this->db->limit($limit,$start);
		$data = $this->db->get("T_COMPONENT");
		//echo $this->db->last_query();
		return $data->result();
		
	}


	//재고현황 /mat/stocklist
	public function get_material_list_nx($params,$start=0,$limit=20)
	{
		
		if($params['COMPONENT'] != ""){
			$this->db->where("COMPONENT LIKE '%".$params['COMPONENT']."%'");
		}
		if($params['COMPONENT_NM'] != ""){
			$this->db->where("COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%'");
		}
		if($params['SPEC'] != ""){
			$this->db->where("SPEC LIKE '%".$params['SPEC']."%'");
		}
		if($params['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$params['GJ_GB']);
		}
		/*
		if($params['USE_YN'] != ""){
			$this->db->where("USE_YN",$params['USE_YN']);
		}*/

		$this->db->order_by("COMPONENT","ASC");
		$this->db->limit($limit,$start);
		$data = $this->db->get("T_COMPONENT");
		//echo $this->db->last_query();
		return $data->result();
		
	}


	//재고출고현황 /mat/stockss
	public function get_material_ss($params,$start=0,$limit=20)
	{
		$where = '';
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$where .=" AND TCT.TRANS_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";
		}

		if($params['COMPONENT'] != ""){
			$where .= " AND COMPONENT LIKE '%{$params['COMPONENT']}%'";
		}
		if($params['COMPONENT_NM'] != ""){
			$where .= " AND COMPONENT_NM LIKE '%{$params['COMPONENT_NM']}%'";
		}
		if($params['GJ_GB'] != ""){
			$where .= " AND TCT.GJ_GB = '{$params['GJ_GB']}'";
		}
		if($params['BIZ_IDX'] != ""){
			$where .= " AND TCT.BIZ_IDX = '{$params['BIZ_IDX']}'";
		}
		



		$sql=<<<SQL
		SELECT 
			AA.*
		FROM 
			(
			SELECT  TC.COMPONENT, TC.COMPONENT_NM, TC.SPEC, TCT.OUT_QTY, TC.UNIT, TBR.CUST_NM, TCT.TRANS_DATE, TCT.GJ_GB, TCT.BIZ_IDX
			FROM T_BIZ_REG AS TBR 
				LEFT JOIN T_COMPONENT_TRANS AS TCT ON TCT.BIZ_IDX = TBR.IDX
				LEFT JOIN T_COMPONENT AS TC ON TC.IDX = TCT.C_IDX
			WHERE 
			TCT.OUT_QTY > 0
			{$where}
			LIMIT
			{$start},{$limit}
			) as AA
		UNION ALL
			SELECT '합계','','',SUM(OUT_QTY),COUNT(OUT_QTY),'','','',''
			FROM T_BIZ_REG AS TBR 
				LEFT JOIN T_COMPONENT_TRANS AS TCT ON TCT.BIZ_IDX = TBR.IDX
				LEFT JOIN T_COMPONENT AS TC ON TC.IDX = TCT.C_IDX
			WHERE 
			TCT.OUT_QTY > 0
			{$where}
SQL;

	$query = $this->db->query($sql);


	// echo $this->db->last_query();
		return $query->result();
		
		
	}

	public function get_material_cut_ss($params)
	{
		$where = '';
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$where .=" AND TCT.TRANS_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";
		}

		if($params['COMPONENT'] != ""){
			$where .= " AND COMPONENT LIKE '%{$params['COMPONENT']}%'";
		}
		if($params['COMPONENT_NM'] != ""){
			$where .= " AND COMPONENT_NM LIKE '%{$params['COMPONENT_NM']}%'";
		}
		if($params['GJ_GB'] != ""){
			$where .= " AND TCT.GJ_GB = '{$params['GJ_GB']}'";
		} 
		if($params['BIZ_IDX'] != ""){
			$where .= " AND TCT.BIZ_IDX = '{$params['BIZ_IDX']}'";
		}

		$sql=<<<SQL
			SELECT
				COUNT(OUT_QTY) as CUT
			FROM T_BIZ_REG AS TBR 
				LEFT JOIN T_COMPONENT_TRANS AS TCT ON TCT.BIZ_IDX = TBR.IDX
				LEFT JOIN T_COMPONENT AS TC ON TC.IDX = TCT.C_IDX
			WHERE 
				TCT.OUT_QTY > 0
				{$where}
SQL;
		$query = $this->db->query($sql);
		
		return $query->row()->CUT;
	}
	
	/* marterials */
	public function get_material_joinlist($params,$start=0,$limit=20)
	{
		$datetime = date("Y-m-d",time());
		//$subquery = ",(SELECT (A.STOCK - EX.STOCK) FROM T_COMPONENT as A WHERE A.IDX = TC.IDX) as STOCKNUM";
		$this->db->select("TC.IDX, EX.COMPONENT as E_COMPONENT, TC.COMPONENT, EX.COMPONENT_NM, EX.UNIT, TC.STOCK, EX.STOCK as E_STOCK, EX.INTO_DATE, EX.REPL_DATE, (IFNULL(TC.STOCK,0) - EX.STOCK) as STOCKNUM");
		$this->db->from("T_COMPONENT as TC");
		$this->db->join("T_COMPONENT_EX as EX","EX.COMPONENT = TC.COMPONENT AND EX.GJ_GB = TC.GJ_GB","right");
		
		$this->db->where("EX.INSERT_DATE BETWEEN '{$datetime} 00:00:00' AND '{$datetime} 23:59:59'");
		
		if(!empty($params['MATCOUNT']) && $params['MATCOUNT'] != ""){
			$this->db->having("STOCKNUM <> ",0);
		}

		$this->db->order_by("TC.STOCK","ASC");
		
		$this->db->limit($limit,$start);
		$data = $this->db->get();
		//echo $this->db->last_query();
		return $data->result();
		
	}



	public function get_component_mlist($param,$start=0,$limit=20)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->like("GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$this->db->like("COMPONENT",$param['COMPONENT']);
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$this->db->like("COMPONENT_NM",$param['COMPONENT_NM']);			
		}

		$this->db->order_by("COMPONENT","ASC");
		$this->db->limit($limit,$start);
		$query = $this->db->get("T_COMPONENT");
		//echo $this->db->last_query();
		return $query->result();
	}


	public function get_component_mlist2($param,$start=0,$limit=20)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->like("GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$this->db->like("COMPONENT",$param['COMPONENT']);
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$this->db->like("COMPONENT_NM",$param['COMPONENT_NM']);			
		}

		

		$this->db->select("*,IF((STOCK-SAVE_QTY) < 0,1,0) as QUICK");
		
		$this->db->order_by("COMPONENT","ASC");
		$this->db->limit($limit,$start);
		$this->db->order_by("QUICK","DESC");
		
		if(!empty($param['QUICK']) && $param['QUICK'] != ""){

			$this->db->having("QUICK > ",0);			
		}

		$query = $this->db->get("T_COMPONENT");
		//echo $this->db->last_query();
		return $query->result();
	}


	public function get_component_mcut($param)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->like("GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$this->db->like("COMPONENT",$param['COMPONENT']);
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$this->db->like("COMPONENT_NM",$param['COMPONENT_NM']);			
		}
		$this->db->select("*,IF((STOCK-SAVE_QTY) < 0,1,0) as QUICK");
		if(!empty($param['QUICK']) && $param['QUICK'] != ""){
			$this->db->having("QUICK > ",0);			
		}


		$query = $this->db->get("T_COMPONENT");
		return count($query->result());
	}


	public function ajax_saveqty_update($param)
	{
		$set = array(
			"SAVE_QTY"  => $param['SAVE_QTY'],
			"SAVE_DATE" => $param['SAVE_DATE']
		);
		$this->db->update("T_COMPONENT",$set,array("IDX"=>$param['IDX']));

		return $this->db->affected_rows();

	}


	public function ajax_component_setting($param)
	{
		$this->db->select("EX.*, TC.IDX as T_IDX, TC.COMPONENT as TC_COMP, (IFNULL(TC.STOCK,0) - EX.STOCK) as STOCKNUM");
		$this->db->from("T_COMPONENT as TC");
		$this->db->join("T_COMPONENT_EX as EX","EX.COMPONENT = TC.COMPONENT AND EX.GJ_GB = TC.GJ_GB","right");
		
		if($param['mode'] == "one"){
			$this->db->where("EX.COMPONENT",$param['component']);
		}
		
		$query = $this->db->get();
		$data = $query->result();
		//echo $this->db->last_query();
		
		

		$tCount = $query->num_rows();
		$successCount = 0;

		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata('user_name');


		foreach($data as $row){
			if($row->TC_COMP == NULL){
				
				
				$this->db->set("GJ_GB",$row->GJ_GB);
				$this->db->set("COMPONENT",$row->COMPONENT);
				$this->db->set("COMPONENT_NM",$row->COMPONENT_NM);
				$this->db->set("STOCK",$row->STOCK);
				$this->db->set("UNIT",$row->UNIT);
				$this->db->set("INTO_DATE",$row->INTO_DATE);
				$this->db->set("REPL_DATE",$row->REPL_DATE);
				$this->db->set("INSERT_DATE",$datetime);
				$this->db->set("INSERT_ID",$username);
				$this->db->insert("T_COMPONENT");
				
			}else{
				
				if($row->STOCKNUM < 0){
					
					$sql=<<<SQL
						INSERT INTO T_COMPONENT_TRANS
						SET
							
							C_IDX = '{$row->T_IDX}',
							TRANS_DATE = '{$datetime}',
							KIND = 'INM',
							IN_QTY = '{$row->STOCKNUM}',
							GJ_GB = '{$row->GJ_GB}',
							INSERT_ID = '{$username}',
							INSERT_DATE = '{$datetime}'
SQL;

					$this->db->query($sql);
					
				}elseif($row->STOCKNUM > 0){
					
					$sql=<<<SQL
						INSERT INTO T_COMPONENT_TRANS
						SET
							
							C_IDX = '{$row->T_IDX}',
							TRANS_DATE = '{$datetime}',
							KIND = 'OTM',
							OUT_QTY = '{$row->STOCKNUM}',
							GJ_GB = '{$row->GJ_GB}',
							INSERT_ID = '{$username}',
							INSERT_DATE = '{$datetime}'
SQL;

					$this->db->query($sql);
				}

				$this->db->set("STOCK",$row->STOCK);
				$this->db->set("INTO_DATE",$row->INTO_DATE);
				$this->db->set("REPL_DATE",$row->REPL_DATE);
				$this->db->set("UPDATE_DATE",$datetime);
				$this->db->set("UPDATE_ID",$username);
				$this->db->where("IDX",$row->T_IDX);
				$query = $this->db->update("T_COMPONENT");

				
				
			}
			
			if($this->db->affected_rows()){
				
				$this->db->where("COMPONENT",$row->COMPONENT);
				$this->db->delete("T_COMPONENT_EX");

				$successCount++;
			}
		}

		$data['tcount'] = $tCount;
		$data['scount'] = $successCount;

		return $data;

	}



	
	public function get_material_excut($params)
	{
		$this->db->select("(TC.STOCK - EX.STOCK) as STOCKNUM");
		$this->db->from("T_COMPONENT as TC");
		$this->db->join("T_COMPONENT_EX as EX","EX.COMPONENT = TC.COMPONENT AND EX.GJ_GB = TC.GJ_GB","right");
		
		if(!empty($params['MATCOUNT']) && $params['MATCOUNT'] != ""){
			$this->db->having("STOCKNUM <> ",0);
		}

		$query = $this->db->get();
		$data['totnum'] = count($query->result());
		
		return $data;
		
	}


	public function get_material_cut($params)
	{
		if($params['COMPONENT'] != ""){
			$this->db->where("COMPONENT LIKE '%".$params['COMPONENT']."%'");
		}
		if($params['COMPONENT_NM'] != ""){
			$this->db->where("COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%'");
		}
		if($params['SPEC'] != ""){
			$this->db->where("SPEC",$params['SPEC']);
		}
		if($params['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$params['GJ_GB']);
		}
		if($params['USE_YN'] != ""){
			$this->db->where("USE_YN",$params['USE_YN']);
		}
		//if(!empty($params['set']) && $params['set'] != "") $where = $this->db->where($params['seq']." LIKE '%".$params['set']."%'");
		$this->db->select("COUNT(IDX) as cut");
		$data = $this->db->get("T_COMPONENT");
		return $data->row()->cut;
		
	}


	/*
		
	*/
	public function get_material_cut_nx($params)
	{
		if($params['COMPONENT'] != ""){
			$this->db->where("COMPONENT LIKE '%".$params['COMPONENT']."%'");
		}
		if($params['COMPONENT_NM'] != ""){
			$this->db->where("COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%'");
		}
		if($params['SPEC'] != ""){
			$this->db->where("SPEC",$params['SPEC']);
		}
		if($params['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$params['GJ_GB']);
		}
		/*
		if($params['USE_YN'] != ""){
			$this->db->where("USE_YN",$params['USE_YN']);
		}*/
		//if(!empty($params['set']) && $params['set'] != "") $where = $this->db->where($params['seq']." LIKE '%".$params['set']."%'");
		$this->db->select("COUNT(IDX) as cut");
		$data = $this->db->get("T_COMPONENT");
		return $data->row()->cut;
		
	}




	//등록/제거 리스트 쿼리
	public function get_bom_material($data,$start=0,$limit=20)
	{
		$where = "tc.GJ_GB = '".$data['gjgb']."'";
		
		if(!empty($data['idx'])){

			if($data['set'] != ""){
				$where .= " AND {$data['seq']} like '%{$data['set']}%'";
			}

			$sql=<<<SQL
				SELECT
					tc.*,
					(SELECT COUNT(tb.IDX) FROM T_BOM as tb WHERE tb.H_IDX = '{$data['idx']}' AND tb.C_IDX = tc.IDX) as CHKBOM
				FROM
					T_COMPONENT as tc
				WHERE
					{$where}
				ORDER BY
					CHKBOM DESC, tc.COMPONENT , tc.IDX DESC
				LIMIT
					{$start},{$limit}
SQL;
						
			
			
			$query = $this->db->query($sql);
		
			
		}
		// echo $this->db->last_query();
		return $query->result();
	}

	public function get_level2Bom_material($params,$start=0,$limit=20)
	{
		$sql=<<<SQL
			SELECT
			tc.*,
				(SELECT 
					COUNT( tb.IDX ) 
				FROM 
					T_2BOM AS tb 
				WHERE tb.H_IDX = "{$params['H_IDX']}" 
					AND tb.C_IDX = "{$params['C_IDX']}" 
					AND tb.L2_IDX = tc.IDX 
				) AS CHKBOM 
			FROM
				T_2LVCOMP AS tc 
			WHERE
				tc.GJ_GB = "{$params['GJGB']}"
			ORDER BY
				CHKBOM DESC,
				tc.COMPONENT,
				tc.IDX DESC 
				LIMIT 0,50
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function get_level2Bom_material_cut($params)
	{
		//alert($params['H_IDX']."  ".$params['C_IDX']);
		$sql=<<<SQL
			SELECT
			COUNT(tc.IDX) AS cut,
				(SELECT 
					COUNT( tb.IDX ) 
				FROM 
					T_2BOM AS tb 
				WHERE tb.H_IDX = '{$params["H_IDX"]}' 
					AND tb.C_IDX = '{$params["C_IDX"]}' 
					AND tb.L2_IDX = tc.IDX 
				) AS CHKBOM 
			FROM
				T_2LVCOMP AS tc 
			WHERE
				tc.GJ_GB = "{$params['GJGB']}"
			ORDER BY
				CHKBOM DESC,
				tc.COMPONENT,
				tc.IDX DESC 
				LIMIT 0,50
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->cut;

	}
	
	public function get_bom_material_cut($data)
	{
		$where = "tc.GJ_GB = '".$data['gjgb']."'";
		if(!empty($data['idx'])){

			if($data['set'] != ""){
				$where .= " AND {$data['seq']} like '%{$data['set']}%'";
			}

			$sql=<<<SQL
				SELECT
					COUNT(tc.IDX) AS cut
				FROM
					T_COMPONENT as tc
				WHERE
					{$where}
SQL;
			$query = $this->db->query($sql);

		}

		return $query->row()->cut;
		
	}



	public function get_material_info($idx)
	{
		$this->db->where(array("IDX"=>$idx));
		$data = $this->db->get("T_COMPONENT");
		return $data->row();
	}

	public function get_lv2material_info($idx)
	{
		$this->db->where(array("IDX"=>$idx));
		$data = $this->db->get("T_2LVCOMP");
		return $data->row();
	}

	public function get_lv3material_info($idx)
	{
		$this->db->where(array("IDX"=>$idx));
		$data = $this->db->get("T_3LVCOMP");
		return $data->row();
	}

	public function set_materialInsert($params)
	{
		$this->db->insert("T_COMPONENT",$params);
		return $this->db->insert_id();
	}

	public function set_materialUpdate($params,$idx)
	{
		$this->db->update("T_COMPONENT",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}

	public function get_check_bom($hidx,$cidx)
	{
		$query = $this->db->select("count(IDX) as num")
						->where(array("H_IDX"=>$hidx, "C_IDX"=>$cidx))
						->get("T_BOM");
		return $query->row();
	}



	public function get_bom_list($idx)
	{
		$query = $this->db->select("tc.COMPONENT, tc.COMPONENT_NM, tc.SPEC, tc.UNIT, tc.PRICE, tb.UPDATE_DATE, tb.UPDATE_ID, tb.IDX as BIDX, tb.WORK_ALLO, tb.PT, tb.POINT, tb.REEL_CNT")
						->from("T_BOM as tb")
						->join("T_COMPONENT as tc","tc.IDX = tb.C_IDX","left")
						->where(array("tb.H_IDX"=>$idx))
						->get();

		//echo nl2br($this->db->last_query());
		// echo $this->db->last_query();
		
		return $query->result();

	}

	public function get_bom_list_r3($bno=NULL,$qty=0)
	{
		$sql=<<<SQL
			SELECT `tc`.`COMPONENT`, `tc`.`COMPONENT_NM`, `tc`.`COMPONENT_UNIT`, `tc`.`POINT`,
				   (`tc`.`POINT`*{$qty}) AS T_POINT,`tc`.COMPONENT_STOCK, (`tc`.COMPONENT_STOCK - (`tc`.`POINT`*{$qty})) AS M_POINT
			FROM `T_BOM_V` as `tc`
			WHERE BL_NO ='{$bno}'
SQL;
		$query = $this->db->query($sql);
		
		return $query->result();

	}


	public function set_bom_insertform($params)
	{
		$query = $this->db->insert_batch("T_BOM",$params);
		return $this->db->insert_id();
	
	}
	
	/* bom 자재선택 */
	public function set_bom_formUpdate($param)
	{
		$query = $this->db->insert("T_BOM",$param);
		return $this->db->insert_id();
	}
	

	/* bom 선택된자재삭제 */
	public function set_bom_formDelete($param)
	{
		$this->db->where(array("H_IDX"=>$param['H_IDX'],"C_IDX"=>$param['C_IDX']));
		$this->db->delete("T_BOM");
		return $this->db->affected_rows();
	}

	/*2레벨 bom 자재선택 */
	public function set_l2_bom_formUpdate($param)
	{
		$query = $this->db->insert("T_2BOM", $param);
		return $this->db->insert_id();
	}


	/*2레벨 bom 선택된자재삭제 */
	public function set_l2_bom_formDelete($param)
	{
		$this->db->where(array("H_IDX" => $param['H_IDX'], "C_IDX" => $param['C_IDX'], "L2_IDX"=>$param['L2_IDX']));
		$this->db->delete("T_2BOM");
		return $this->db->affected_rows();
	}

	/*3레벨 bom 자재선택 */
	public function set_l3_bom_formUpdate($param)
	{
		$query = $this->db->insert("T_3BOM", $param);
		return $this->db->insert_id();
	}


	/*3레벨 bom 선택된자재삭제 */
	public function set_l3_bom_formDelete($param)
	{
		$this->db->where(array("H_IDX" => $param['H_IDX'], "C_IDX" => $param['C_IDX'],"L2_IDX" =>$param['L2_IDX'],"L3_IDX" =>$param['L3_IDX']));
		$this->db->delete("T_3BOM");
		return $this->db->affected_rows();
	}


	public function set_bomlistUpdate($params,$idx)
	{
		$this->db->update("T_BOM",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}

	public function set_l2_bomlistUpdate($params,$idx)
	{
		$this->db->update("T_2BOM",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}

	public function set_l3_bomlistUpdate($params,$idx)
	{
		$this->db->update("T_3BOM",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}
	
	/*	bom에 자재가 등록되어있는지 확인
		IDX : T_COMPONENT IDX
	*/
	public function check_bom_info($param,$type)
	{
		$query = $this->db->select("COUNT(IDX) as num")
						->where(array($type=>$param['IDX']))
						->get("T_BOM");
		return $query->row()->num;
	}


	public function delete_material($param)
	{
		$this->db->delete("T_COMPONENT",array("IDX"=>$param['IDX']));
		return $this->db->affected_rows();
	}

	public function delete_item($param)
	{
		$this->db->delete("T_ITEMS",array("IDX"=>$param['IDX']));
		return $this->db->affected_rows();
	}


	public function get_matform_temp_list()
	{
		$this->db->where("GUBUN","입고");
		$this->db->order_by("COMPONENTNO","DESC");
		$this->db->order_by("IPGO_DATE","DESC");
		$query = $this->db->get("T_TEMP_COM");
		//echo $this->db->last_query();
		$data['list'] = $query->result();
		$data['num']  = $query->num_rows();
		return $data;
	}



	public function ajax_matform_component_update($code,$date)
	{
		$sql=<<<SQL
			UPDATE T_COMPONENT as A
			SET
				STOCK = STOCK+(SELECT SUM(B.QTY) FROM T_TEMP_COM as B WHERE B.COMPONENTNO = A.COMPONENT),
				INTO_DATE = '{$date}'
			WHERE
				A.COMPONENT = '{$code}'
SQL;
		//$this->db->query($sql);
		//return $this->db->affected_rows();
	}


	public function ajax_matform_temp_insert($params)
	{
		//$cidx = $this->db->query("SELECT A.IDX FROM T_COMPONENT as A WHERE A.COMPONENT = '{$params['C_IDX']}'");
		$this->db->select("IDX");
		$this->db->where("COMPONENT",$params['C_IDX']);
		$query = $this->db->get("T_COMPONENT");
		
		$cidx = $query->row();


		$sql=<<<SQL
			INSERT INTO T_COMPONENT_TRANS
			SET
				
				C_IDX = '{$cidx->IDX}',
				TRANS_DATE = '{$params['TRANS_DATE']}',
				KIND = 'IN',
				IN_QTY = '{$params['IN_QTY']}',
				GJ_GB = '{$params['GJ_GB']}',
				INSERT_ID = '{$params['INSERT_ID']}',
				INSERT_DATE = '{$params['INSERT_DATE']}'
SQL;

		//echo nl2br($sql);
		$this->db->query($sql);

	}

	
	public function stock_update($param){
		date_default_timezone_set('Asia/Seoul');
		$dateTime = date("Y-m-d H:i:s",time());

		// $this->db->set('STOCK',$param['QTY']);
		$this->db->set('UPDATE_ID',$param['INSERT_ID']);
		$this->db->set('UPDATE_DATE',$dateTime);
		$this->db->where("IDX",$param['IDX']);
		$this->db->update("T_COMPONENT");

		$this->db->set("C_IDX",$param['IDX']);
		$this->db->set('TRANS_DATE',$param['OUTDATE']);
		$this->db->set('KIND','OTC');
		$this->db->set('OUT_QTY',$param['OUTQTY']);
		$this->db->set('GJ_GB',$param['GJ_GB']);
		$this->db->set('INSERT_ID',$param['INSERT_ID']);
		$this->db->set('INSERT_DATE',$dateTime);
		$this->db->set('BIZ_IDX',$param['ACCOUNT']);
		$this->db->insert("T_COMPONENT_TRANS");
		
		
		return $this->db->affected_rows();
	}


}