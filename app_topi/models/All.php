<?php defined('BASEPATH') OR exit('No direct script access allowed');

class All extends CI_Model {

    var $table = 'view_logs_activity'; //nama tabel dari database
    var $column_order = array(null, 'nama','username','uri','params', 'api_key', 'ip_address', 'terakhir_akses'); //field yang ada di table user
    var $column_search = array('nama','username','uri','params', 'api_key', 'ip_address', 'terakhir_akses'); //field yang diizin untuk pencarian 
    var $order = array('terakhir_akses' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function member($member = 0)
    {
        // $this->db->where('group_id', $member);
        $dt = $this->db->get('sink_menu');
        $oke = $dt->result();

        return $this->buildTree($oke,0);
    } 

    public function buildTree(array &$elements, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent == $parentId) {
                $children = self::buildTree($elements, $element->id);
                if ($children) {
                    $element->state = 'closed';
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    private function _get_datatables_query()
    {
        
        $this->db->from($this->table);

        $i = 0;
    
        // filter kolom
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }   

        // search by user
        if(!empty($_POST['user'])){
            $this->db->where("user_id",$_POST['user']);
        }

        // search by start_date
        if(!empty($_POST['start_date'])){
            $this->db->where("terakhir_akses >=",$_POST['start_date']);
        }

        // search by end_date
        if(!empty($_POST['end_date'])){
            $this->db->where("terakhir_akses <=",$_POST['end_date']." 23:59:59");
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    
}

/* End of file all.php */
/* Location: ./application/models/all.php */