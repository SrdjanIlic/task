<html>
<head>
    <meta charset="utf-8">
    <title>All tables</title>
    <link rel="stylesheet" type="text/css" href="css.css"/>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript">
        $(function(){
            var select_tables = $.ajax({
                url: "./controller.php",
                type: "POST",
                data: { 'function' : 'select_tables' },
                dataType: "json"
            });
            select_tables.done(function(data) {
                var html = "";
                if (data.length == 0) {
                    html += "<tr><td colspan='3'>No tables created</td></tr>";
                } else {
                    $.each(data, function(i, item) {
                        html += "<tr>";
                        html += "<td>" + item.name + "</td><td>" + item.ext + "</td>";
                        html += "<td>";
                        html += "<a href='table_select.php?table=" + item.name + "' class='button'>Select</a> ";
                        html += "<a href='table_alter.php?table=" + item.name + "' class='button'>Alter</a> ";
                        html += "<input type='submit' value='Delete' name='" + item.name + "' id='delete' class='button' /> ";
                        html += "<input type='submit' value='Truncate' name='" + item.name + "' id='truncate' class='button' /> ";
                        html += "</td>";
                        html += "</tr>";
                    });
                }
                $('.table').append(html);
            });
            $('body').on('click', '#truncate', function() {
                var truncate_table = $.ajax({
                    url: "./controller.php",
                    type: "POST",
                    data: { 'function':'truncate_table', 'table':this.name },
                    dataType: "json"
                });
                truncate_table.done(function(data) {
                    alert(data.msg);
                });
            });
            $('body').on('click', '#delete', function() {
                var delete_table = $.ajax({
                    url: "./controller.php",
                    type: "POST",
                    data: { 'function':'delete_table', 'table':this.name },
                    dataType: "json"
                });
                delete_table.done(function(data) {
                    if (data.status == true) {
                        alert(data.msg);
                        location.reload(true);
                    } else {
                        alert(data.msg);
                    }
                });
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
        <h1>All tables</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <p><a href="table_add.php" class='button'>Add table</a></p>
    </body>
</html>
