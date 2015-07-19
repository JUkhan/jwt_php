<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sp_model extends CI_Model {

	public function call_sp($spname, $params)
	{
		
		$paramStr='';
		if($params){
		      foreach ($params as $key => $val)
		      {
		        $params[$key] = '"' . mysql_real_escape_string($val) . '"';
		      }
		     $paramStr= implode(', ', $params);
	     }
	   $query=  'CALL ' . $spname . '(' . $paramStr . ');' ;
	   $qry_res    = $this->db->query($query);

       $res        = $qry_res->result();

       $qry_res->next_result(); // Dump the extra resultset.
       $qry_res->free_result(); // Does what it says.

       return $res;
		//return $this->db->call_procedure($spname, $params);
	}
	
	 public function call_sp_out($spname, $params, $out_params){
	 	$paramStr='';
	 	$out_params_str='';
	 	$select_out_params_sql='';

		if($params){
		      foreach ($params as $key => $val)
		      {
		        $params[$key] = '"' . mysql_real_escape_string($val) . '"';
		      }
		     $paramStr= implode(', ', $params);
	    }

	     if ($out_params)
		 {
		 	  foreach ($out_params as $key => $val)
		      {
		         $out_params[$key] = '@' . $val;
		      }
			  $out_params_str = implode(', ', $out_params);
			  $select_out_params_sql = 'SELECT ' . $out_params_str . ';';
			  
		 }
		 if($params){

		 	  // We need a comma to separate regular parameters from out parameters in the CALL statement
			  $out_params_str = ',' . $out_params_str;
		 }
	    $query=  'CALL ' . $spname . '(' . $paramStr . $out_params_str. ');' . $select_out_params_sql;
	    $qry_res    = $this->db->query($query);

        $res[]        = $qry_res->result();

        $res[] =$qry_res->next_result(); // Dump the extra resultset.
        $qry_res->free_result(); // Does what it says.
        return $res;
	}
    
}

/* End of file users.php */
/* Location: ./application/models/users.php */