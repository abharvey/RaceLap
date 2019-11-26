<?php
// Application library
require __DIR__ . '/../_config.php';

$request_file = $_GET['q']; // try to get event token
//var_dump($json_file);
//Make sure it's lowercase and that it dosent have a tail /
$request_file = strtolower($request_file);
$json_file = JSON_LOCATION . $request_file . ".json";
$baseuri = "http://test.charetx2.com/";

//var_dump(DIR_ROOT ."/event/". $json_file);
if (!is_file(DIR_ROOT . $json_file)) {
  header("Location: " . $baseuri);
}

$string = file_get_contents(DIR_ROOT . $json_file);
$json_a = json_decode($string, true);
$eventdata = array();
$eventdata['evanturl'] = BASE . $request_file; //substr($json_file, 0, strrpos($json_file, "."));
$eventdata['public'] = $json_a['event'][0]['public'];
$eventdata['evantname'] = $json_a['event'][0]['name'];
$eventdata['location'] = $json_a['event'][0]['location'];
$eventdata['organizer'] = $json_a['event'][0]['organizer'];

$meta = "";
if ($eventdata['organizer'] == 'Velo NB') {
  $meta .= "<meta property='og:description' content='Racelap - Velo NB' />\r\n";
  $meta .= "<meta property='og:image' content='http://results.charetx2.com/img/VNB_RaceResults.png' />\r\n";
  //$meta .= "<meta property='og:video:width' content='500' />\r\n";
  //$meta .= "<meta property='og:video:height' content='260' />\r\n";
} else if ($eventdata['organizer'] == 'RVC') {
  $meta .= "<meta property='og:description' content='Racelap - RVC' />\r\n";
  $meta .= "<meta property='og:image' content='http://results.charetx2.com/img/RVC_RaceResults.png' />\r\n";
  //$meta .= "<meta property='og:video:width' content='500' />\r\n";
  //$meta .= "<meta property='og:video:height' content='260' />\r\n";
}
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
<link href="/eventPage.css" rel="stylesheet" />
<script src="/scripts/js/jquery-3.2.1.min.js"></script>
<script src="/scripts/js/jquery.dataTables.min.js"></script>

<script src="https://unpkg.com/react@15/dist/react.js"></script>
<script src="https://unpkg.com/react-dom@15/dist/react-dom.js"></script>
<script src="/eventApp.js"></script>

<link href="/scripts/css/base.css" rel="stylesheet">

<meta property="og:title" content="Racelap - Results <?php echo $eventdata['evantname']?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://results.charetx2.com<?php echo $eventdata['evanturl']?>" />
<?php echo $meta; ?>

</head>

<body>
<div id="RaceLapBar">
  <a href="/"><img class="racelapLogo" src="/img/RaceLap3.png"></a>
</div>
<div id="RaceLapBarMenu">
  <a href="/">Home</a> | <a href="/services.php">Services</a> | <a href="/contact.php">Contact</a>
</div>
<div id="react-root"></div>

<div class="_note col-sm-5" style="padding-Bottom: 40px;">
  Acronyms:
<ul>
  <li>DNS: Did Not Start</li>
  <li>DNF: Did Not Finish</li>
  <li>DSQ: Disqualified</li>
</ul>
</div>
<div class="footer" id="footer">
    <div class="text-center">&copy; <?php echo date('Y')?> - RaceLap | CharetX<sup>2</sup> All Rights Reserved</div>
</div>
<script>


    /* TODO: Trim out the jquery as refactored into react state */

    $(document).ready(function($) {

      var VisitCounter = 0;
      String.prototype.capitalize = function() {
          return this[0].toUpperCase() + this.slice(1);
      };

      //Visitors
      function update() {
          $.getJSON("<?php echo "/scripts/visitors.php?json=" . $request_file; ?>",
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
          url: "<?php echo $json_file; ?>", //'test/t6.json',
          dataType: 'json',
          success: function(response) {
              json_results = response;
            //   $("#eventname").append(response.event[0].name);
            //   $(document).attr("title", 'RaceLap | ' + response.event[0].name);
            //   $("#eventdate").append("Date: " + response.event[0].date);
            //   $("#eventlocation").append(response.event[0].location);

            //   var commissaire = response.event[0].commissaire;
            //   if (commissaire && commissaire.trim().length) {
            //     $("#eventcommissaire").append("Commissaire: " + commissaire);
            //   }

            //   var participant = response.event[0].participant;
            //   if (participant && participant.trim().length) {
            //     $("#eventparticipant").append("Participants: " + participant);
            //   }

            //   var resultstatus = response.event[0].resultstatus;
            //   if (resultstatus && resultstatus.trim().length) {
            //     $("#eventresultstatus").append("Status: " + resultstatus);
            //   }
            //   var eventorganizer = response.event[0].organizer;

            //   if (eventorganizer && eventorganizer.trim().length) {
            //     $("#eventorganizer").append("Race Series: " + eventorganizer);
            //   }
            //   var eventtype = response.event[0].type;
            //   if (eventtype && eventtype.trim().length) {
            //     $("#eventorganizer").append(" (" + eventtype + ")");
            //   }
              //eventresultstatus

              //loadResults from buttonclick
              $('#ResultsDetailed').click();
              // TODO: merge First Name and Last Name into a "Name" Column

              const eventDataLoaded = new CustomEvent('event::data::loaded', {detail: { eventData: response }});

              window.dispatchEvent(eventDataLoaded);
          }
      });

      $('#ResultsDetailed').on('click', function (e) {
        if (results_detailed == false){
          $("#ResultsDetailed").text('Detailed Results');
        } else {
          $("#ResultsDetailed").text('Quick Results');
        }

        $("#ResultsDiv").empty();

        $.each(json_results, function(key, val) {
            if (key != 'event') { //Skip event info
                initiateTable(key, json_results, results_detailed);
            }
        });
        // Toggle value (simple, detailed)
        results_detailed ^= true;
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

          }


          //Combining first and last names as "Name" object
          for (i = 0; i < response[tableId].results.length; i++) {
              response[tableId].results[i].Name = response[tableId].results[i]['First Name'] + ' ' + response[tableId].results[i]['Last Name'];
              response[tableId].results[i]['Finish Time'] = response[tableId].results[i]['Finish Time'].substr(0, 10);
          }
      }
    });
</script>
</body>

</html>
