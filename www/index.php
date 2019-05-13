<?php
$button = $_GET['pressed'];
$servername = "159.65.204.46";
$username = "root";
$password = "tiger";
$dbname = "nodemcu";
// Create connection
$conn = new mysqli($servername, $username,$password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Retrieve DB from server
$sql = "SELECT * FROM buttonpress ORDER by id DESC LIMIT 33";
$result = $conn->query($sql);
$conn->close();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NodeMCU Demo</title>
            <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
        <!-- Light CSS -->
        <link rel="stylesheet" href="light.css">

        <!-- <link rel="stylesheet" href="/assets/css/bulma.min.css"> -->
    </head>
    <body>
        <section>
            <div class="container-fluid">
                <div class="row">
                <div class="col-sm-6">
                    <h1>NodeMCU Demo <span class="pull-right">
                        <div id="light"></div>
                    </span></h1>
                    <button type="button" id="toggle" data-state="1" class="btn btn-danger">Toggle</button>
                </div>

                <div class="col">
                    <h1>Website Light Toggling</h1>
                    <table class="table table-bordered table-sm table-striped">
                    <thead>
                        <tr>
                        <th>
                            State
                        </th>
                        <th>
                            Region
                        </th>
                        <th>
                            Country
                        </th>
                        <th>
                            When
                        </th>
                        </tr>
                    </thead>
                    <tbody id="log"></tbody>
                    </table>
                </div>
                </div>
                <h1>Physical Button Clicks</h1>
                <?php
                    if ($result->num_rows > 0) {
                        // output data of each row
                        echo "<div class='col'><table class='table table-bordered table-sm table-striped' border='1'><th>ID</th><th>Was it pressed?</th><th>DATE TIME</th>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$row['id']."</td>";
                            echo "<td>".$row['pressed']."</td>";
                            echo "<td>".$row['date']."</td>";
                            echo "</tr>";
                        }
                        echo "</table></col>";
                    } else {
                        echo "0 results";
                    }
                ?>

            </div>
        </section>


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.js"></script>

  <script>

function current_state() {
  btn = $('#toggle');
  light = $('#light');
  log = $('#log');
  $.ajax({
    url: 'toggle.php?current',
    method: 'get',
    success: function(data){
      if(data==1){
        btn.data('state', '0');
        light.addClass('on');
        light.removeClass('off');
        btn.addClass('btn-danger');
        btn.removeClass('btn-success');
        btn.html('Turn Off');
        // console.log("TEST");
      }else{
        btn.data('state', '1');
        light.addClass('off');
        light.removeClass('on');
        btn.addClass('btn-success');
        btn.removeClass('btn-danger');
        btn.html('Turn On');
      }
    }
  });
  $.ajax({
    url: 'toggle.php?log',
    method: 'get',
    success: function(data){
      var lines = data.split('\n');
      lines.reverse();
      var result = '';
      for(var i = 0;i < lines.length;i++){
        if (lines[i] != ''){
          var cells = lines[i].split('-');
          cell = '<tr>';
          for(var x = 0;x < cells.length;x++){
            cell = cell+'<td>'+cells[x]+'</td>';
          }
          cell = cell+'</tr>';
          result = result+cell;
        }
      }
        log.html(result);
        // console.log("TEST");
    }
  });
}



$(document).ready(function(){
  current_state();
  setInterval(current_state, 1000);
  $('#toggle').click(function(){
    var current = $(this).data('state');
    $.ajax({
      url: 'toggle.php?state='+current,
      method: 'get'
    });
  });

});

</script>

</body>
</html>