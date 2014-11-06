<html>
    <head>
        <meta charset="utf-8">
        <title>Table add</title>
        <link rel="stylesheet" type="text/css" href="css.css"/>
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript">
            $(function(){
                $('#add_column').click(function (e){
                    e.preventDefault();
                    add_column();
                });
                $('body').on('click', '#submit', function() {
                    var post = $("#idForm").serializeArray();
                    var fields_string = "{";
                    var table = "";
                    $.each(post, function(i, item){
                        if (item.name == 'table') {
                            table = item.value;
                        } else {
                            fields_string += '"'+item.name + '":"' + item.value+'",';
                        }
                    });
                    fields_string = fields_string.slice(0,-1);
                    fields_string += "}";
                    var fields = $.parseJSON(fields_string);
                    var create_table = $.ajax({
                        url: "./controller.php",
                        type: "POST",
                        data: { 'function':'create_table', 'table':table, 'fields':fields },
                        dataType: "json"
                    });
                    create_table.done(function(data) {
                        if (data.status == true) {
                            alert(data.msg);
                            window.location.replace('index.php');
                        } else {
                            alert(data.msg);
                        }
                    });
                    return false;
                });
            });
            function add_column () {
                var i=$('.new_column').length,
                    html = "<p><label for='col_"+i+"'>Column name: </label><input type='text' name='"+i+"' class='new_column' id='col_"+i+"' /></p>";
                $('#columns').append(html);
            }
        </script>
    </head>
    <body>
    <nav>
        <ul>
            <li><a href='index.php'>Home</a></li>
        </ul>
    </nav>
    <br class="clr"/>
    <h1>New table</h1>
    <form method="post" action="" id="idForm">
        <p><label for="name">Table name: </label><input type="text" name="table" id="name" /></p>
        <br />
        <div id="columns">
            <p><label for="col_0">Column name: </label><input type="text" name="0" class="new_column" id="col_0" /></p>
        </div>
        <p><a href="#" name="add_column" id="add_column" class="button"/>Add column</a></p>
        <input type="submit" name="submit" id="submit" class="button" onclick="return false;"/>
    </form>
    </body>
</html>