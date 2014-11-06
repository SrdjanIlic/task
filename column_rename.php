<?php
if(isset($_GET['table'])) {
    $table = $_GET['table'];
    if(isset($_GET['column'])) {
        $column = $_GET['column'];
    } else {
        header('Location: table_alter.php?table='.$table);
    }
} else {
    header('Location: index.php');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Rename column</title>
        <link rel="stylesheet" type="text/css" href="css.css"/>
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript">
            $(function(){
                $('#submit').click(function() {
                    var param = $("#name").val();
                    var alter_table = $.ajax({
                        url: "./controller.php",
                        type: "POST",
                        data: { 'function':'alter_table', 'table':'<?php echo $table; ?>', 'operation':'rename', 'column_name':'<?php echo $column; ?>', 'param':param },
                        dataType: "json"
                    });
                    alter_table.done(function(data) {
                        if (data.status == true) {
                            alert(data.msg);
                            window.location.replace('table_alter.php?table=<?php echo $table; ?>');
                        } else {
                            alert(data.msg);
                        }
                    });
                    return false;
                });
            });
        </script>
    </head>
    <body>
        <nav>
            <ul>
                <li><a href='index.php'>Home</a></li>
            </ul>
        </nav>
        <br class="clr"/>
        <p>Rename column</p>
        <form method="post" action="" id="idForm">
            <p><label for="name">Column name: </label><input type="text" name="name" id="name" value="<?php echo $column; ?>"/></p>
            <input type="submit" name="submit" id="submit" class="button" />
        </form>
    </body>
</html>