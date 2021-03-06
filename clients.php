<?php 

namespace Google\AdsApi\Examples\AdWords\v201710\BasicOperations;

require __DIR__ . '/vendor/autoload.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201710\ReportDownloader;
 use Google\AdsApi\AdWords\Reporting\v201710\DownloadFormat;
 use Google\AdsApi\AdWords\ReportSettingsBuilder;
 use Google\AdsApi\Common\OAuth2TokenBuilder;

include('db.php');
$query = "select * from clients where status = 1";
$result = $link->query($query);

if ($result->num_rows > 0) {
 ?>
 <form method="post" action="">
	<label for="from">From</label>
	<input type="text" id="from" name="from">
	<label for="to">to</label>
	<input type="text" id="to" name="to">
	 
 </form>


<table border=1 align="center">

	<tr>
		<th>Id</th>
		<th>client Name</th>
		<th>client Id</th>
		<!--<th>Location</th>
		<th>Service/Products</th>-->
		<th>Channels</th>
		<th>Spend</th>
		<th>Revenue</th>
		<th>Orders</th>
		<th>Leads</th>
	</tr>
<?php  


		
while($row = $result->fetch_assoc()) { 

//$location_query  = "SELECT c.location_name FROM clients a INNER JOIN client_location_mapping b ON a.id=b.clients_id and a.id=".$row['id']." INNER JOIN location c ON b.location_id = c.id";
//$location_result = $link->query($location_query);

//$services_query  = "SELECT c.name FROM clients a INNER JOIN client_service_mapping b ON a.id=b.clients_id and a.id=".$row['id']." INNER JOIN services_products c ON b. 	services_products_id = c.id";
//$services_result = $link->query($services_query);
$channel_query  = "SELECT c.channel_name FROM clients a INNER JOIN client_channel b ON a.id=b.clients_id and a.id=".$row['id']." INNER JOIN channels c ON b.channels_id = c.id";
$channel_result = $link->query($channel_query);





?>
	<tr>
		<td><?php  echo $row['id']; ?></td>
		<td><?php if($row['isManager']==1){ ?><a href="clientsapi.php?id=<?php  echo $row['client_channel_id']; ?>"><?php  echo $row['client_name']; ?></a> <?php }else{  ?><a href="campaignsapi.php?id=<?php  echo $row['client_channel_id']; ?>"><?php  echo $row['client_name']; ?></a><?php } ?></td>
				
		<td><?php  echo $row['client_channel_id']; ?></td>
 
		<td>
			<?php  while($channel_row = $channel_result->fetch_assoc()) {  
						echo $channel_row['channel_name']."<br>";
					}
			?>
		</td>
		<td>
			&#8377;<?php if($row['isManager']!=1){ echo getCost($row['client_channel_id'],$_POST['from'],$_POST['to']); } ?>
		</td>
	</tr>
	<?php } ?>
</table>
<?php
 //}
} else {
    echo "0 results";
}
function getCost($clientId,$from,$to){
	$oAuth2Credential = (new OAuth2TokenBuilder())
        ->fromFile()
        ->build();

    // Construct an API session configured from a properties file and the OAuth2
    // credentials above.
    $session = (new AdWordsSessionBuilder())
        ->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
		->withClientCustomerId($clientId)
        ->build();

	if($from=="" || $to==""){ $during = "LAST_7_DAYS"; } else{ $during = $from.",".$to; } 

echo $reportQuery = 'select CampaignName,Cost
					from CRITERIA_PERFORMANCE_REPORT
					where ExternalCustomerId = '.$clientId.' and Cost !=0
					during $during';
		$reportDownloader = new ReportDownloader($session);
		$reportSettingsOverride = (new ReportSettingsBuilder())
									->includeZeroImpressions(true)
									->build();
		$reportDownloadResult = $reportDownloader->downloadReportWithAwql($reportQuery, DownloadFormat::CSV, $reportSettingsOverride);
		$data1 = explode("\n", $reportDownloadResult->getAsString());

		$price=0;
		$i=0;

		foreach ($data1 as $campaign) {
			$data = explode(",", $campaign);
 			if($i==0 || $i==1) {}else{  
				if($data[0]=="Total") { $price =  $data[1]; }
			}
			if($i==(count($data1)-2)){  break; }
			$i++;
		}
		echo round(($price/1000000),2);

}
?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script>
  $( function() {
    var dateFormat = "YYYYMMDD",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 3
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  </script>