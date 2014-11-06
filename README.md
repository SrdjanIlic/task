#URL: controller.php

All params must be send with POST method

#All functions:
#select_tables 
###params example:
Array
(
    [function] => select_tables
)
###return example:
[{"name":"Users","ext":"json"}]


#create_table
###params example:
Array
(
    [function] => create_table,
    [table] => Users,
    [fields] => Array,
                (
                    [0] => id,
                    [1] => ime,
                    [2] => loz
                )
)
###return example:
{"status":true,"msg":"Table created"}


#table_select
###params example:
Array
(
    [function] => table_select,
    [table] => Users
)
###return example:
{"structure":["id","ime","lozinka"],"data":[{"id":"1","ime":"Pera","lozinka":"Pera","_id":1}]}


#get_table_structure
###params example:
Array
(
    [function] => get_table_structure,
    [table] => Users
)
###return example:
["id","ime","lozinka"]


#alter_table
###params example:
Array
(
    [function] => alter_table,
    [table] => Users,
    [operation] => rename,
    [column_name] => loz,
    [param] => lozinka
)
###return example:
{"status":true,"msg":"Column renamed"}


#truncate_table
###params example:
Array
(
    [function] => truncate_table,
    [table] => Users
)
###return example:
{"status":true,"msg":"Table truncated"}


#delete_table
###params example:
Array
(
    [function] => delete_table,
    [table] => Users
)
return:
{"status":true,"msg":"Table deleted"}


#data_insert
###params example:
Array
(
    [function] => data_insert_ajax,
    [table] => Users,
    [params] => Array
                (
                 [id] => 1,
                 [ime] => Pera,
                 [lozinka] => Pera
                )
)
###return example:
{"status":true,"msg":"Data insert"}


#data_insert_ajax
###params example:
Array
(
    [function] => data_insert_ajax,
    [table] => Users,
    [params] => Array
                (
                    [0] => Array
                    (
                        [name] => id,
                        [value] => 1
                    ),
                    [1] => Array
                    (
                         [name] => ime,
                         [value] => Pera
                    ),
                    [2] => Array
                    (
                         [name] => lozinka,
                         [value] => Pera
                    )
                )
)
###return example:
{"status":true,"msg":"Data insert"}


#data_select
###params example:
Array
(
    [function] => data_select,
    [table] => Users,
    [column] => _id,
    [_id] => 1
)
###return example:
[{"id":"1","ime":"Pera","lozinka":"Pera","_id":1}]


#data_update
###params example:
Array
(
    [function] => data_update_ajax,
    [table] => Users,
    [column] => _id,
    [value] => 1,
    [data] => Array
    (
        [id] => 1,
        [ime] => Pera1,
        [lozinka] => Pera1
    )
)
###return example:
{"status":true,"msg":"Data updated"}


#data_update_ajax
###params example:
Array
(
    [function] => data_update_ajax,
    [table] => Users,
    [column] => _id,
    [value] => 1,
    [data] => Array
    (
        [0] => Array
        (
            [name] => id,
            [value] => 1
        ),
        [1] => Array
        (
            [name] => ime,
            [value] => Pera1
        ),
        [2] => Array
        (
            [name] => lozinka,
            [value] => Pera1
        )
    )
)
###return example:
{"status":true,"msg":"Data updated"}


#data_delete
###params example:
Array
(
    [function] => data_delete,
    [table] => Users,
    [operation] => delete,
    [_id] => 1
)
###return example:
{"status":true,"msg":"Data deleted"}
