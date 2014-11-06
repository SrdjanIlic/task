<?php
if(isset($_GET['table'])) {
    $table = $_GET['table'];
    if(isset($_GET['_id'])) {
        $_id = $_GET['_id'];
    } else {
        header('Location: table_select.php?table='.$table);
    }
} else {
    header('Location: index.php');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Data update</title>
        <link rel="stylesheet" type="text/css" href="css.css"/>
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript">
            $(function(){
                var data_select = $.ajax({
                    url: "./controller.php",
                    type: "POST",
                    data: { 'function':'data_select', 'table':'<?php echo $table; ?>', 'column':'_id', '_id':'<?php echo $_id; ?>' },
                    dataType: "json"
                });
                data_select.done(function(data) {
                    var html = "";
                    if (data.length == 0) {
                        html += "<tr><td colspan='2'>No data</td></tr>";
                    } else {
                        $.each(data[0], function(i, item) {
                            if(i != '_id') {
                                html += "<p><label for='col_"+ i +"'>"+ i +":</label><input type='text' name='"+ i +"' id='col_"+ i +"' value='"+ item +"' /></p>";
                            }
                        });
                    }
                    $('#idForm').prepend(html);
                });
                $('body').on('click', ':submit', function() {
                    var form_data = $("#idForm").serializeArray();
                    var data_update_ajax = $.ajax({
                        url: "./controller.php",
                        type: "POST",
                        data: { 'function':'data_update_ajax', 'table':'<?php echo $table; ?>', 'column':'_id', 'value':'<?php echo $_id; ?>', 'data':form_data },
                        dataType: "json"
                    });
                    data_update_ajax.done(function(data) {
                        if (data.status == true) {
                            alert(data.msg);
                            window.location.replace('table_select.php?table=<?php echo $table; ?>');
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
        <h1>Table <?php echo "$table"; ?>, update data</h1>
        <form method="post" action="" id="idForm">
            <input type="submit" name="submit" id="submit" class="button" />
        </form>
    </body>
</html>

