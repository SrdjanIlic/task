<?php
if(isset($_GET['table'])) {
    $table = $_GET['table'];
    if (isset($_GET['_id'])) {
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
        <title>Data select</title>
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
                                html += "<tr><td>"+ i +"</td><td>"+ item +"</td></tr>";
                            }
                        });
                    }
                    $('.table').append(html);
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
        <h1>Table <?php echo "$table"; ?>, selected data</h1>
        <table class="table">
            <thead>
            <tr>
                <th>Column name</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </body>
</html>
