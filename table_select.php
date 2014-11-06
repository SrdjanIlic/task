<?php
if($_GET['table']) {
    $table = $_GET['table'];
} else {
    header('Location: index.php');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>All records</title>
        <link rel="stylesheet" type="text/css" href="css.css"/>
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript">
            $(function(){
                var table_select = $.ajax({
                    url: "./controller.php",
                    type: "POST",
                    data: { 'function':'table_select', 'table':'<?php echo $table; ?>' },
                    dataType: "json"
                });
                table_select.done(function(data) {
                    var html = "";
                    if (data.structure.length == 0) {
                        html += "<tr><td style='clear: both'>No data</td></tr>";
                    } else {

                        html += "<tr>";
                        $.each(data.structure, function(i, item) {
                            if (item != '_id') {
                                html += "<th>"+ item +"</th>";
                            }
                        });
                        html += "<th>Actions</th></tr>";
                    }
                    if (data.data.length == 0) {
                        html += "<tr><td style='clear: both'>No data</td></tr>";
                    } else {
                        $.each(data.data, function(i, item) {
                            html += "<tr>";
                            var _id = item._id;
                            $.each(item, function(k, v){
                                if(k != '_id') {
                                    html += "<td>" + v + "</td>";
                                }
                            });
                            html += "<td>";
                            html += "<a href='data_select.php?table=<?php echo $table; ?>&&_id="+ _id +"' class='button'>Select</a> ";
                            html += "<a href='data_update.php?table=<?php echo $table; ?>&&_id="+ _id +"' class='button'>Update</a> ";
                            html += "<input type='submit' value='Delete' name='" + _id + "' class='button' /> ";
                            html += "</td>";
                            html += "</tr>";
                        });
                    }
                    $('.table').append(html);
                });
                $('body').on('click', ':submit', function() {
                    if (this.value == "Delete") {
                        var _id = this.name;
                        var data_delete = $.ajax({
                            url: "./controller.php",
                            type: "POST",
                            data: { 'function':'data_delete', 'table':'<?php echo $table; ?>', 'operation':'delete', '_id':_id },
                            dataType: "json"
                        });
                        data_delete.done(function(data) {
                            if (data.status == true) {
                                alert(data.msg);
                                location.reload(true);
                            } else {
                                alert(data.msg);
                            }
                        });
                    }
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
        <h1>Table <?php echo "$table"; ?>, all records</h1>
        <table class="table">

        </table>
        <p><a href="./data_insert.php?table=<?php echo $table; ?>" class="button">Insert data</a></p>
    </body>
</html>