<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_api2 extends CI_Model {

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = '';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'field';

    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($table_name = NULL, $where = NULL) {
        $this->db->select('*');
        $this->db->from($table_name);
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->db->get()->result();
        if ($result) {
            if ($where !== NULL) {
                return array_shift($result);
            } else {
                return $result;
            }
        } else {
            return NULL;
        }
    }

    public function getTx($where = NULL, $index = NULL)
    {
        $this->db->query("SET lc_time_names = 'id_ID'");                      
        $this->db->select('user.nama, user.role role,transaksi.file_name, user.saldo, transaksi.dana, DATE_FORMAT(transaksi.created_date, "%d %M %Y") as created_date, transaksi.jenis, transaksi.nama_transaksi, transaksi.keterangan, transaksi.id, proyek.nama_proyek');
        $this->db->from('transaksi');
        $this->db->join('user', 'transaksi.id_user = user.id', 'left');
        $this->db->join('proyek', 'transaksi.id_proyek = proyek.id', 'left');
        if ($index !== NULL ) {
           $this->db->limit($index);
        }

        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
            
        }
        $this->db->order_by('transaksi.created_date', 'desc');

        $result = $this->db->get()->result();


        if ($result) {
            if ($where !== NULL) {
                return $result;
            } else {
                return $result;
            }
        } else {
            return NULL;
        }


    }



    public function getAll($table_name = NULL, $where = NULL) {
        $this->db->select('*');
        $this->db->from($table_name);
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        $result = $this->db->get()->result();
        if ($result) {
            if ($where !== NULL) {
                return $result;
            } else {
                return $result;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    public function insert($table_name = NULL, Array $data) {
        if ($this->db->insert($table_name, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update($table_name = NULL, Array $data, $where = array()) {
            if (!is_array($where)) {
                $where = array(self::PRI_INDEX => $where);
            }
        $this->db->update($table_name, $data, $where);
        $this->db->affected_rows();
        return true;
    }

    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete($table_name = NULL, $where = array()) {
        // if (!is_array()) {
        //     $where = array(self::PRI_INDEX => $where);
        // }
        $this->db->delete($table_name, $where);
        return $this->db->affected_rows();
    }


    public function upload_file($name_form='', $dir='')
    {
        $config['upload_path'] = './uploads/'.$dir;    
        $config['allowed_types'] = '*';
        $config['max_size']    = '1024';     
              
        $this->load->library('upload', $config); 
        // Load konfigurasi uploadnya    
        if($this->upload->do_upload($name_form)){ 
        // Lakukan upload dan Cek jika proses upload berhasil      
        // Jika berhasil :      
        $return = $this->upload->data();      
        return $return['file_name'];    
        }else{      
        // Jika gagal :
            $log = fopen("./uploads/error_upload.txt", "w");
            fwrite($log, $this->upload->display_errors());      
            return NULL;    
        }
    }
}
        



 ?>