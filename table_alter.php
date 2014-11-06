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
    <title>Alter table</title>
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
                    html += "<tr><td colspan='2'>No columns created</td></tr>";
                } else {
                    $.each(data, function(i, item) {
                        html += "<tr>";
                        html += "<td>"+ item + "</td>";
                        html += "<td>";
                        html += "<a href='column_rename.php?table=<?php echo $table; ?>&&column=" + item + "' class='button'>Rename</a> ";
                        html += "<input type='submit' name='" + item + "' value='Delete' class='button' /> ";
                        html += "</td>";
                        html += "</tr>";
                    });
                }
                $('.table').append(html);
            });
            $('body').on('click', ':submit', function() {
                if (this.value == "Delete") {
                    var column_name = this.name;
                    var alter_table = $.ajax({
                        url: "./controller.php",
                        type: "POST",
                        data: { 'function':'alter_table', 'table':'<?php echo $table; ?>', 'operation':'delete', 'column_name':column_name },
                        dataType: "json"
                    });
                    alter_table.done(function(data) {
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
        <h1>Alter table <?php echo $table; ?></h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Column</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    <p><a href="./column_add.php?table=<?php echo $table; ?>" class="button">Add column</a></p>
    </body>
</html>