<?php
if(isset($_GET['table'])) {
    $table = $_GET['table'];
} else {
    header('Location: index.php');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Data insert</title>
        <link rel="stylesheet" type="text/css" href="css.css"/>
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript">
            $(function(){
                var get_table_structure = $.ajax({
                    url: "./controller.php",
                    type: "POST",
                    data: { 'function':'get_table_structure', 'table':'<?php echo $table; ?>' },
                    dataType: "json"
                });
                get_table_structure.done(function(data) {
                    var html = "";
                    if (data.length == 0) {
                        html += "No data";
                    } else {
                        $.each(data, function(i, item) {
                            if(i != '_id') {
                                html += "<p><label for='col_"+ item +"'>"+ item +": </label><input type='text' name='"+ item +"' id='col_"+ item +"' value='' /></p>";
                            }
                        });
                    }
                    $('#idForm').prepend(html);
                });
                $('body').on('click', '#submit', function() {
                    var params = $("#idForm").serializeArray();
                    var data_insert_ajax = $.ajax({
                        url: "./controller.php",
                        type: "POST",
                        data: { 'function':'data_insert_ajax', 'table':'<?php echo $table; ?>', 'params':params },
                        dataType: "json"
                    });
                    data_insert_ajax.done(function(data) {
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
    <h1>Table <?php echo "$table"; ?>, insert data</h1>
    <form method="post" action="" id="idForm">
        <input type="submit" name="submit" id="submit" class="button" />
    </form>
    </body>
</html>