<?php
include_once('./main.php');

/**
 * Interface iStructure
 */
interface iController {
    public function select_tables();
    public function get_table_structure($table_name);
    public function create_table($table_name, $fields);
    public function alter_table($table_name, $operation, $column_name, $param);
    public function delete_table($table_name);
    public function truncate_table($table_name);
    public function insert($table_name, $data);
    public function select($table_name, $params);
    public function update($table, $params, $data);
    public function delete($table, $params);
}

/**
 * Class Controller
 */
class Controller implements iController {

    /**
     * Function return all tables in system
     * @return string
     */
    public function select_tables(){
        $tables = scandir("./data");
        $table_return = [];
        foreach ($tables as $k_tab => $v_tab) {
            if ($v_tab != "." && $v_tab != ".." ) {
                $table = explode('.', $v_tab);
                $ext = end($table);
                $table_name = substr($v_tab, 0, -(strlen($ext)+1));
                $table_row['name'] = $table_name;
                $table_row['ext'] = $ext;
                $table_return[] = $table_row;
            }
        }
        return $table_return;
    }

    /**
     * Function create new table
     * example/format params of function
     * $table = "Users";
     * $fields = Array
     * (
     *    [0] => id
     *    [1] => name
     *    [2] => pass
     * )
     *
     * @param string $table_name
     * @param $fields
     *
     * @return bool
     */
    public function create_table($table_name, $fields){
        $structure = new StructureJson();
        return $structure->create($table_name, $fields);
    }

    /**
     * Function update table.
     * Function has three modes of operation
     *
     * $operation = 'add' | 'rename' | 'delete'
     *
     * example of 'add' operation
     * $table_name = "Users";
     * $operation = "add";
     * $column_name = "address";
     * $param = "First";
     *
     * example of 'rename' operation
     * $table_name = "Users";
     * $operation = "add";
     * $column_name = "pass";
     * $param = "password";
     *
     * example of 'delete' operation
     * $table_name = "Users";
     * $operation = "delete";
     * $column_name = "address";
     *
     *
     * @param $table_name
     * @param $operation
     * @param $column_name
     * @param $param
     *
     * @return bool|int
     */
    public function alter_table($table_name, $operation, $column_name, $param){
        $structure = new StructureJson();
        return $structure->alter($table_name, $operation, $column_name, $param);
    }

    /**
     * Function delete table
     * example/format param of function
     * $table = "Users";
     *
     * @param string $table_name
     *
     * @return bool
     */
    public function delete_table($table_name){
        $structure = new StructureJson();
        return $structure->delete($table_name);
    }

    /**
     * Function truncate table.
     * example/format param of function
     * $table = "Users";
     *
     * @param string $table_name
     *
     * @return int
     */
    public function truncate_table($table_name){
        $structure = new StructureJson();
        return $structure->truncate($table_name);
    }

    /**
     * Function return structure of table.
     * example/format param of function
     * $table_name = "Users";
     *
     * example/format of return value
     * return Array
     * (
     *        [0] => _id
     *        [1] => id
     *        [2] => name
     *        [3] => password
     * )
     *
     * @param $table_name
     *
     * @return string
     */
    public function get_table_structure($table_name){
        $structure = new StructureJson();
        return $structure->get_structure($table_name);
    }

    /**
     * Function insert data in table.
     * example/format params of function
     * $table = "Users";
     * $data = Array
     * (
     * [0] => Array
     *        (
     *              [id] => 1
     *              [name] => Pera
     *              [password] => Pera
     *        )
     * )
     *
     * @param $table_name
     * @param $data
     * @return int
     */
    public function insert($table_name, $data){
        $Data = new DataJson();
        return $Data->insert($table_name, $data);
    }

    /**
     * Function return selected data from table.
     * example/format params of function
     * $table = "Users";
     * $params = Array
     * (
     * [0] => Array
     *        (
     *              [column] => _id
     *              [value] => 1
     *              [select_type] => exact_match
     *        )
     * )
     *
     * @param $table_name
     * @param array $params
     * @return array
     */
    public function select($table_name, $params = []){
        $Data = new DataJson();
        return $Data->select($table_name, $params);
    }

    /**
     * Function update data in table.
     * example/format params of function
     * $table = "Users";
     * $params = Array
     * (
     * [0] => Array
     *        (
     *              [column] => _id
     *              [value] => 1
     *              [select_type] => exact_match
     *        )
     * )
     *
     * $data = Array
     * (
     *        [id] => 1
     *        [name] => Pera1
     *        [password] => Pera1
     * )
     *
     * @param $table_name
     * @param array $params
     * @param $data
     * @return int
     */
    public function update($table_name, $params = [], $data){
        $Data = new DataJson();
        return $Data->update($table_name, $params, $data);
    }

    /**
     * Function delete row/rows from selected table
     * example/format params of function
     * $table = "Users";
     * $params = Array
     * (
     * [0] => Array
     *        (
     *              [column] => _id
     *              [value] => 1
     *              [select_type] => exact_match
     *        )
     * )
     *
     * @param $table_name
     * @param array $params
     * @return mixed
     */
    public function delete($table_name, $params = []){
        $Data = new DataJson();
        return $Data->delete($table_name, $params);
    }

}


$Controller = new Controller();

if (isset($_POST['function'])) {
    $function = $_POST['function'];

    if($function == 'select_tables') {
        echo json_encode($Controller->select_tables());
    } elseif ($_POST['table']) {
        $table = $_POST['table'];

        if($function == 'create_table') {
            $fields = $_POST['fields'];
            if ($Controller->create_table($table, $fields)) {
                echo json_encode(['status' => true, 'msg' => 'Table created']);
            } else {
                echo json_encode(['status' => false, 'msg' => 'Table not created']);
            }
        }

        if($function == 'get_table_structure') {
            echo json_encode($Controller->get_table_structure($table));
        }

        if($function == 'alter_table') {
            if (isset($_POST['operation']) && isset($_POST['column_name'])) {
                $operation = $_POST['operation'];
                $column_name = $_POST['column_name'];
                if ($operation == 'delete') {
                    if ($Controller->alter_table($table, $operation, $column_name, [])) {
                        echo json_encode(['status' => true, 'msg' => 'Column deleted']);
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'Column not deleted']);
                    }
                } elseif ($operation == 'rename') {
                    if ($Controller->alter_table($table, $operation, $column_name, $_POST['param'])) {
                        echo json_encode(['status' => true, 'msg' => 'Column renamed']);
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'Column not renamed']);
                    }
                } elseif ($operation == 'add') {
                    if ($Controller->alter_table($table, $operation, $column_name, $_POST['param'])) {
                        echo json_encode(['status' => true, 'msg' => 'Column added']);
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'Column not added']);
                    }
                } else {
                    echo json_encode(['status' => false, 'msg' => 'Please select operation']);
                }

            } else {
                echo json_encode(['status' => false, 'msg' => 'Please select operation and column']);
            }
        }

        if($function == 'truncate_table') {
            if ($Controller->truncate_table($table)) {
                echo json_encode(['status' => true, 'msg' => 'Table truncated']);
            } else {
                echo json_encode(['status' => false, 'msg' => 'Table not truncated']);
            }
        }

        if($function == 'delete_table') {
            if ($Controller->delete_table($table)) {
                echo json_encode(['status' => true, 'msg' => 'Table deleted']);
            } else {
                echo json_encode(['status' => false, 'msg' => 'Table not deleted']);
            }
        }

        if($function == 'table_select') {
            $data['structure'] = $Controller->get_table_structure($table);
            $data['data'] = $Controller->select($table);
            echo json_encode($data);
        }

        if($function == 'data_delete') {
            $params = [['column'=>'_id','value'=>$_POST['_id'],'select_type'=>'exact_match']];
            if ($Controller->delete($table, $params)) {
                echo json_encode(['status' => true, 'msg' => 'Data deleted']);
            } else {
                echo json_encode(['status' => false, 'msg' => 'Data not deleted']);
            }
        }

        if($function == 'data_select') {
            $params = [['column'=>$_POST['column'],'value'=>$_POST['_id'],'select_type'=>'exact_match']];
            echo json_encode($Controller->select($table, $params));
        }

        if($function == 'data_insert') {
            $params = $_POST['params'];
            if ($Controller->insert($table, $params)) {
                echo json_encode(['status' => true, 'msg' => 'Data insert']);
            } else {
                echo json_encode(['status' => false, 'msg' => 'Data not insert']);
            }
        }

        if($function == 'data_insert_ajax') {
            $params = $_POST['params'];
            $params_save = [];
            foreach($params as $k => $v) {
                $params_save[$v['name']] = $v['value'];
            }

            if ($Controller->insert($table, [$params_save])) {
                echo json_encode(['status' => true, 'msg' => 'Data insert']);
            } else {
                echo json_encode(['status' => false, 'msg' => 'Data not insert']);
            }
        }

        if($function == 'data_update') {
            $params = [['column'=>$_POST['column'],'value'=>$_POST['value'],'select_type'=>'exact_match']];
            $data_save = $_POST['data'];

            if ($Controller->update($table, $params, $data_save)) {
                echo json_encode(['status' => true, 'msg' => 'Data updated']);
            } else {
                echo json_encode(['status' => false, 'msg' => 'Data not updated']);
            }
        }

        if($function == 'data_update_ajax') {
            $params = [['column'=>$_POST['column'],'value'=>$_POST['value'],'select_type'=>'exact_match']];
            $data = $_POST['data'];
            $data_save = [];
            foreach($data as $k => $v) {
                $data_save[$v['name']] = $v['value'];
            }
            if ($Controller->update($table, $params, $data_save)) {
                echo json_encode(['status' => true, 'msg' => 'Data updated']);
            } else {
                echo json_encode(['status' => false, 'msg' => 'Data not updated']);
            }
        }

    } else {
        echo json_encode(['status' => false, 'msg' => 'Please select table']);
    }

} else {
    echo json_encode(['status' => false, 'msg' => 'Please select function']);
}


