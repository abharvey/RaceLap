<?php
// Application library
require __DIR__ . '/../_config.php';

$request_file = $_GET['q']; // try to get event token
//var_dump($json_file);
//Make sure it's lowercase and that it dosent have a tail /
$request_file = strtolower($request_file);
$json_file = $request_file . ".json";

$string = file_get_contents(DIR_ROOT . JSON_SERIES_LOCATION . $json_file);
$json_a = json_decode($string, true);
$eventdata = array();
$eventdata['evanturl'] = BASE . $request_file; //substr($json_file, 0, strrpos($json_file, ".")); $eventdata['evanturl'] = BASE . substr($value, 0, strrpos($value, "."));
$eventdata['public'] = $json_a['event'][0]['public'];
$eventdata['evantname'] = $json_a['event'][0]['name'];
$eventdata['location'] = $json_a['event'][0]['location'];
$eventdata['organizer'] = $json_a['event'][0]['organizer'];

if ($eventdata['organizer'] == 'Velo NB') {
  $meta .= "<meta property='og:description' content='Racelap - VeloNB' />\r\n";
  $meta .= "<meta property='og:image' content='http://results.charetx2.com/img/VNB_RaceResults.png' />\r\n";
  $meta .= "<meta property='og:video:width' content='500' />\r\n";
  $meta .= "<meta property='og:video:height' content='260' />\r\n";

} else if ($eventdata['organizer'] == 'RVC') {
  $meta .= "<meta property='og:description' content='Racelap - RVC' />\r\n";
  $meta .= "<meta property='og:image' content='http://results.charetx2.com/img/RVC_RaceResults.png' />\r\n";
  $meta .= "<meta property='og:video:width' content='500' />\r\n";
  $meta .= "<meta property='og:video:height' content='260' />\r\n";
} else if ($eventdata['organizer'] == 'MBS') {
  $meta .= "<meta property='og:description' content='Racelap - MBS' />\r\n";
  $meta .= "<meta property='og:image' content='http://results.charetx2.com/img/MBS_RaceResults.png' />\r\n";
  $meta .= "<meta property='og:video:width' content='500' />\r\n";
  $meta .= "<meta property='og:video:height' content='260' />\r\n";
} else {
  $meta = "";
}


/*
Excel Formula
="{"& CHAR(34) &"Place"& CHAR(34) &":"& CHAR(34) &A1& CHAR(34) &","& CHAR(34) &"First Name"& CHAR(34) &":"& CHAR(34) &B1& CHAR(34) &","& CHAR(34) &"Last Name"& CHAR(34) &":"& CHAR(34) &C1& CHAR(34) &","& CHAR(34) &"Affiliation"& CHAR(34) &":"& CHAR(34) &D1& CHAR(34) &","& CHAR(34) &"Club"& CHAR(34) &":"& CHAR(34) &E1& CHAR(34) &","& CHAR(34) &"W1"& CHAR(34) &":"& CHAR(34) &F1& CHAR(34) &","& CHAR(34) &"W2"& CHAR(34) &":"& CHAR(34) &G1& CHAR(34) &","& CHAR(34) &"W3"& CHAR(34) &":"& CHAR(34) &H1& CHAR(34) &","& CHAR(34) &"W4"& CHAR(34) &":"& CHAR(34) &I1& CHAR(34) &","& CHAR(34) &"Total"& CHAR(34) &":"& CHAR(34) &J1& CHAR(34) &"}"
*/


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="ISO-8859-1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Race Results">
  <meta name="keywords" content="Race Results VeloNB Chip Timing Services">
  <meta name="viewport" content="width=device-width, initial-scale=0.75">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>RaceLap - Results</title>
<link href="/scripts/css/bootstrap.min.css" rel="stylesheet">
<link href="/scripts/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<script src="/scripts/js/jquery-3.2.1.min.js"></script>
<script src="/scripts/js/jquery.dataTables.min.js"></script>
<script src="/scripts/js/dataTables.bootstrap.min.js"></script>
<link href="/scripts/css/base.css" rel="stylesheet">
<meta property="og:type" content="website" />
<meta property="og:title" content="Results" />
<?php echo $meta; ?>
<!--
<style type="text/css">
  .runner{
  width: 20px;
  height: 20px;
  -webkit-mask-box-image: url(http://www.clker.com/cliparts/F/5/I/M/f/U/running-icon-white-on-transparent-background-md.png);
}
</style>
-->

</head>

<body>

<div id="RaceLapBar">
  <a href="/"><img class="racelapLogo" src="/img/RaceLap3.png"></a>
</div>
<div id="RaceLapBarMenu">
  <a href="/">Home</a> | <a href="/services.php">Services</a> | <a href="/contact.php">Contact</a>
</div>
<div class="RaceLapPage">
  <h1 class="page-header raceCat">
    <div id="eventname" style="display: inline-block;"></div> <small><div id="eventlocation" style="display: inline-block;"></div></small>
  </h1>

  <div style="margin-top:5px;margin-bottom:20px;">
    <span id="visitor" class="pull-right"><span id="visitorimg"></span><span id="visitorcount"></span></span>
    <!--<div class="runner pull-right" style="display: inline-block; background-color: red;"></div>-->
    <div id="eventorganizer" class="_note1" style="display: block;margin-left:15px;"></div>
    <div id="eventdate" class="_note2 " style="display: block;margin-left:15px;"></div>
    <div id="eventresultstatus" class="_note2" style="display: block;margin-left:15px;"></div>
    <div id="eventparticipant" class="_note2" style="display: block;margin-left:15px;"></div>
    <div id="eventcommissaire" class="_note2" style="display: block;margin-left:15px;"></div>

  </div>
  <div id="ResultsDiv">Result Loading...</div>


<div class="_note col-sm-5" style="padding-Bottom: 40px;">
* <a href="http://www.uci.org/docs/default-source/rules-and-regulations/part-v--cyclo-cross.pdf#page=20">UCI regulation 5.3.013</a>:
Riders tying on points will be ranked by the greatest number of 1st places, 2nd places, etc. taking account only of places for which points are
awarded for the UCI world cup. If they are still tied, the points scored in most recent event shall be used to separate them.
</div>
</div>

<div class="footer" id="footer">
    <div class="text-center">&copy; <?php echo date('Y')?> - RaceLap | CharetX<sup>2</sup> All Rights Reserved</div>
</div>
<script>
    $(document).ready(function($) {
      var VisitCounter = 0;
      String.prototype.capitalize = function() {
          return this[0].toUpperCase() + this.slice(1);
      };

      //Visitors
      function update() {
          $.getJSON("http://results.charetx2.com/scripts/visitors.php?json=<?php echo $request_file; ?>",
          function(json){
            var visitorcount = json.Visitor;
            if (visitorcount <= 1){
              $('#visitorcount').html(visitorcount + " viewer");
              $('#visitorimg').addClass("imgvisitor visitoricon1").removeClass("visitoricon2");
            } else {
              $('#visitorcount').html(visitorcount + " viewers");
              $('#visitorimg').addClass("imgvisitor visitoricon2").removeClass("visitoricon1");
            }
        });

        //Dissable request after
        if(VisitCounter < 30) { // 5 Min
          VisitCounter++;
          //console.log("-" + VisitCounter + "-" + VisitCounter*10 + "sec");
        } else {
          clearInterval(VisitTimer); //Dissable timer
          $('#visitor').fadeOut("slow");
        }


      }
      var VisitTimer = setInterval(update, 10000); //milliseconds - 10s
      update();

      //Hold the result json
      var json_results = "";
      //Hold the result state
      var results_detailed = false;

      $.ajax({
          cache: false,
          url: "<?php echo JSON_SERIES_LOCATION . $json_file; ?>", //'test/t6.json',
          dataType: 'json',
          success: function(response) {
              json_results = response;
              $("#eventname").append(response.event[0].name);
              $(document).attr("title", 'RaceLap | ' + response.event[0].name);
              $("#eventdate").append("Date: " + response.event[0].date);
              $("#eventlocation").append(response.event[0].location);

              var commissaire = response.event[0].commissaire;
              if (commissaire && commissaire.trim().length) {
                $("#eventcommissaire").append("Commissaire: " + commissaire);
              }

              var participant = response.event[0].participant;
              if (participant && participant.trim().length) {
                $("#eventparticipant").append("Participants: " + participant);
              }

              var resultstatus = response.event[0].resultstatus;
              if (resultstatus && resultstatus.trim().length) {
                $("#eventresultstatus").append("Status: " + resultstatus);
              }
              var eventorganizer = response.event[0].organizer;

              if (eventorganizer && eventorganizer.trim().length) {
                $("#eventorganizer").append("Race Serie: " + eventorganizer);

                $('meta[property="og:description"]').attr('content',"$modified_desc" );
              }
              var eventtype = response.event[0].type;
              if (eventtype && eventtype.trim().length) {
                $("#eventorganizer").append(" (" + eventtype + ")");
              }
              //eventresultstatus

              $("#ResultsDiv").empty();

              $.each(json_results, function(key, val) {
                  if (key != 'event') { //Skip event info
                      initiateTable(key, json_results, results_detailed);
                  }
              });
              // Toggle value (simple, detailed)
              results_detailed ^= true;
          }
      });


      function initiateTable(tableId, response, detailed) {
          var columns = [];

          for (i = 0; i < response[tableId].columns.length; i++) {
              var col = '';
              // if column name is "First Name" then map to data object "Name"
              if (response[tableId].columns[i][0] === 'First Name') {
                  col = 'Name';
              } else { // otherwise name and title are the same
                  col = response[tableId].columns[i][0];
              }

              //Add Thead columns header and style
              if ((col == "First Name") || (col == "Last Name")  ){
                // Skip
              } else if ((col == "Place")) {
                    columns.push({"data": col,"title": response[tableId].columns[i][0],"orderable": false,"class": "smallColumn"});
              } else if ((col == "W1") || (col == "W2") || (col == "W3") || (col == "W4")) {
                    columns.push({"data": col,"title": response[tableId].columns[i][0],"orderable": false,"class": "smallColumn"});
              } else if ((col == "Name")){
                    columns.push({"data": col,"title": col,"orderable": false,"class": "mediumColumn"});
              } else if ((col == "Affiliation") || (col == "Club")) {
                  columns.push({"data": col,"title": response[tableId].columns[i][0],"orderable": false,"class": "mediumColumn"});
              } else {
                  columns.push({"data": col, "title": response[tableId].columns[i][0],"orderable": false});
              }
          }

          var columnDefs = [
            { targets : [0],
              render : function (data, type, row) {
                if (data == '1'){
                  return '<span class="app-icon icon-1 icon-s">1</span>';
                }else if (data == '2'){
                  return '<span class="app-icon icon-2 icon-s">2</span>';
                }else if (data == '3'){
                  return '<span class="app-icon icon-3 icon-s">3</span>';
                }else{
                  return '<span class="app-txt">'+data+'</span>';
                }
              },
              targets : [6],
                render : function (data, type, row) {

                  var results = [];
                  data.split(/\|/).forEach(function(myString) {
                    var splitString = myString.split("::");
                    //results.concat();
                    results.push("<a href=\"/event/" +splitString[0]+ "\">" + splitString[1] + "</a>");
                  });
                  //console.log(results);
                  return '<span>'+results+'</span>';
                }
            }
          ];

          columnDefs.push({"targets": '_all', "visible": true});


          //Combining first and last names as "Name" object
          for (i = 0; i < response[tableId].results.length; i++) {
              response[tableId].results[i].Name = response[tableId].results[i]['First Name'] + ' ' + response[tableId].results[i]['Last Name'];
          }
          //Table Data
          var TableIdName = tableId.replace(/[\W_]/g, "");
          $('#ResultsDiv').append('<table id="' + TableIdName + '" class="table table-striped table-condensed" width="100%;" style="white-space:nowrap;"></table>');
          var oTable = $("#" + TableIdName).dataTable({
              data: response[tableId].results,
              "dom": '<"toolbar' + TableIdName + '">frtip',
              "bProcessing": false,
              "bPaginate": false,
              "bLengthChange": false,
              "bFilter": false,
              "bInfo": false,
              "bAutoWidth": true,
              columns: columns,
              columnDefs: columnDefs,
              "ordering": false,
              "language": {
              "emptyTable": "No Participants / Aucun participant"
            }
          });
          // Table category
          if ( tableId.indexOf("::") > -1 ) {
            var fields = tableId.split('::');
            $("div.toolbar" + TableIdName).html('<h3 style="margin-bottom:0px;margin-top:40px;">' + fields[0].capitalize() + ' <small><div style="display: inline-block;">' + fields[1].capitalize() + '</div></small></h3>');
          } else {
            $("div.toolbar" + TableIdName).html('<h3 style="margin-bottom:0px;margin-top:40px;">' + tableId.capitalize() + '</h3>');
          }

          $("#header").html($("#header").html());
      }
    });
</script>
</body>

</html>
