<?php

/**
 * Interface iStructure
 */
interface iStructure {
    public function create($table_name, $fields);
    public function alter($table_name, $operation, $column_name, $param);
    public function delete($table_name);
    public function truncate($table_name);
}

/**
 * Interface iData
 */
interface iData {
    public function insert($table, $data);
    public function select($table, $params);
    public function update($table, $params, $data);
    public function delete($table, $params);
}

/**
 * Class CommonFunction
 */
class CommonFunction {

    /**
     * Debug function
     * @param $data
     */
    public function debug($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    /**
     * Function save json file
     * @param $file
     * @param $data
     * @return int
     */
    public function save_file_json ($file, $data) {
        return file_put_contents($file, json_encode($data));
    }

}
/**
 * Class StructureJson
 * Purpose of the class is structure manipulation of table.
 */
class StructureJson extends CommonFunction implements iStructure {
    /**
     * @var string location of json file with table structure information
     */
    public $tables_dir = './tables';
    /**
     * @var string location of json file with table data information
     */
    public $data_dir = './data';

    /**
     * Function create new table
     *
     * @param string $table_name table name
     * @param $fields table fields
     * @return bool
     */
    public function create($table_name = "", $fields){
        if ($this->table_exists($table_name)) {
            return false;
        }
        $fields_save = [];
        foreach ($fields as $k => $v) {
            if ($v != "" && $v != '_id') {
                $fields_save[] = $v;
            }
        }
        if (count($fields_save) > 0) {
            if (file_exists("$this->tables_dir/$table_name.json")){
                return false;
            } else {
                $fields[] = '_id';
                if(!$this->save_file_json("$this->tables_dir/$table_name.json", $fields_save)){
                    return false;
                }
                if(!$this->save_file_json("$this->data_dir/$table_name.json", [])){
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Function update table.
     *
     * @param string $table_name table name
     * @param $operation operation on table
     * @param $column_name
     * @param string $param
     * @return bool|int
     */
    public function alter($table_name="", $operation, $column_name, $param = ''){
        if (!$this->table_exists($table_name)) {
            return false;
        }
        $data = $this->get_structure($table_name);
        $Content = new DataJson();
        $content = $Content->get_content($table_name);
        $content_save = [];
        if ($column_name != '_id') { // can not add fileld with this name
            if ($operation == 'add') {
                $data[] = $column_name;
                $this->save_file_json("$this->tables_dir/$table_name.json", $data);
                foreach($content as $k => $v) {
                    $v[$column_name] = $param;
                    $content_save[] = $v;
                }
                return $this->save_file_json("$this->data_dir/$table_name.json", $content_save);
            } elseif ($operation == 'rename') {
                $key = array_search($column_name, $data);
                $data[$key] = $param;
                $this->save_file_json("$this->tables_dir/$table_name.json", $data);
                foreach($content as $k => $v) {
                    foreach($v as $k_d => $v_d) {
                        if ($k_d == $column_name) {
                            unset($v[$k_d]);
                            $v[$param] = $v_d;
                        } else {
                            $v[$k_d] = $v_d;
                        }
                    }
                    $content_save[] = $v;
                }
                return $this->save_file_json("$this->data_dir/$table_name.json", $content_save);
            } elseif ($operation == 'delete') {
                $key = array_search($column_name, $data);
                unset($data[$key]);
                $this->save_file_json("$this->tables_dir/$table_name.json", $data);
                foreach($content as $k => $v) {
                    foreach($v as $k_d => $v_d) {
                        if ($k_d == $column_name) {
                            unset($v[$k_d]);
                        }
                    }
                    $content_save[] = $v;
                }
                return $this->save_file_json("$this->data_dir/$table_name.json", $content_save);
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Function delete table
     *
     * @param string $table_name table name
     * @return bool
     */
    public function delete($table_name = ""){
        if (!$this->table_exists($table_name)) {
            return false;
        }
        $rez = true;
        if (file_exists("$this->tables_dir/$table_name.json")) {
            $rez = unlink("$this->tables_dir/$table_name.json");
        }
        if ($rez) {
            if (file_exists("$this->data_dir/$table_name.json")) {
                $rez = unlink("$this->data_dir/$table_name.json");
            }
        }
        return $rez;
    }

    /**
     * Function truncate table.
     *
     * @param string $table_name
     * @return int
     */
    public function truncate($table_name = ""){
        if (!$this->table_exists($table_name)) {
            return false;
        }
        return $this->save_file_json("$this->data_dir/$table_name.json", []);
    }

    /**
     * Function return structure of table.
     *
     * @param string $table_name table name
     *
     * @return mixed
     */
    public function get_structure($table_name = ""){
        if (!$this->table_exists($table_name)) {
            return [];
        }
        return json_decode(file_get_contents("$this->tables_dir/$table_name.json"), true);
    }

    /**
     * Function check if table exist in system
     * @param string $table table name
     * @return bool
     */
    public function table_exists($table = "") {
        $rez = false;
        if ($table == "") {
            return $rez;
        }
        $rez = file_exists("$this->tables_dir/$table.json");
        if ($rez) {
            $rez = file_exists("$this->data_dir/$table.json");
        }
        return $rez;
    }
}


/**
 * Class DataJson
 * Purpose of the class is data manipulation of table.
 */
class DataJson extends CommonFunction implements iData {
    /**
     * @var string location of json file with table data information
     */
    public $data_dir = './data';

    /**
     * Function insert data in table.
     *
     * @param $table table name
     * @param array $data insert data
     * @return int
     */
    public function insert($table, $data = []){
        $Table = new StructureJson();
        $structure = $Table->get_structure($table);
        $structure_empty = array_fill_keys($structure, '');
        $content = $this->get_content($table);
        foreach($data as $row){
            $data_save = $structure_empty;
            foreach ($row as $k => $v) {
                if (in_array($k, $structure)) {
                    $data_save[$k] = $v;
                }
            }
            $data_save['_id'] = $this->last_id_increment($table);
            $content[] = $data_save;
        }
        return $this->save_file_json("$this->data_dir/$table.json", $content);
    }

    /**
     * Function update data in table.
     *
     * @param $table table name
     * @param array $params select criteria for update
     * @param array $data data for update
     * @return int
     */
    public function update($table, $params = [], $data){
        $Table = new StructureJson();
        $structure = $Table->get_structure($table);
        $content = $this->get_content($table);
        foreach($content as $k => $r){
            foreach($params as $p){
                if($p['select_type'] == 'exact_match'){
                    if (isset($r[$p['column']])) {
                        if($r[$p['column']] == $p['value']){
                            foreach ($data as $k_d => $v_d) {
                                if (in_array($k_d, $structure)) {
                                    $content[$k][$k_d] = $v_d;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->save_file_json("$this->data_dir/$table.json", $content);
    }

    /**
     * Function return selected data from table.
     *
     * @param $table table name
     * @param array $params column name, value and type of select
     * @return array
     */
    public function select($table, $params = []){
        $return_array = [];
        $content = $this->get_content($table);
        if($params == []) return $content;
        foreach($content as $r){
            foreach($params as $p){
                if($p['select_type'] == 'exact_match'){
                    if (isset($r[$p['column']])) {
                        if($r[$p['column']] == $p['value']) $return_array[] = $r;
                    }
                }
            }
        }
        return $return_array;
    }

    /**
     * Function delete row/rows from selected table
     *
     * @param $table table name
     * @param array $params select criteria for delete
     * @return mixed
     */
    public function delete($table, $params = []){
        $content = $this->get_content($table);
        foreach($content as $k => $r){
            foreach($params as $p){
                if($p['select_type'] == 'exact_match'){
                    if($r[$p['column']] == $p['value']){
                        unset($content[$k]);
                    }
                }
            }
        }
        return $this->save_file_json("$this->data_dir/$table.json", $content);
    }

    /**
     * Function return all data from table
     * example/format params of function
     * $table = "Users";
     * @param $table table name
     * @return mixed
     */
    public function get_content($table){
        $file = "$this->data_dir/$table.json";
        return json_decode(file_get_contents($file), true);
    }

    /**
     * Function return last insert _id
     * example/format params of function
     * $table = "Users";
     * @param $table
     * @return int
     */
    public function get_last_id($table){
        $data = $this->get_content($table);
        $last_id = 0;
        foreach($data as $k => $v) {
            if ($v['_id'] > $last_id) {
                $last_id = $v['_id'];
            }
        }
        return $last_id;
    }

    /**
     * Function return last autoincrement _id
     * example/format params of function
     * $table = "Users";
     * @param $table
     * @return int
     */
    public function last_id_increment($table) {
        $last_id = $this->get_last_id($table);
        $last_id++;
        return $last_id;
    }
}






