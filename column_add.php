<?php
if(isset($_GET['table'])) {
    $table = $_GET['table'];
    if($_POST['name']) {
        $column = $_POST['name'];
    }
} else {
    header('Location: index.php');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Add column</title>
        <link rel="stylesheet" type="text/css" href="css.css"/>
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript">
            $(function(){

                $('#submit').click(function() {
                    var column_name = $("#name").val();
                    var param = $("#default").val();
                    var alter_table = $.ajax({
                        url: "./controller.php",
                        type: "POST",
                        data: { 'function':'alter_table', 'table':'<?php echo $table; ?>', 'operation':'add', 'column_name':column_name, 'param':param },
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
        <h1>Table <?php echo $table; ?>, new column</h1>
        <form method="post" action="">
            <p><label for="name">Column: </label><input type="text" name="name" id="name" /></p>
            <p><label for="default">Default: </label><input type="text" name="default" id="default" /></p>
            <input type="submit" name="submit" id="submit" class="button" />
        </form>
    </body>
</html>

