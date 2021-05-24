<?php
  include("fusioncharts.php");

  //database connection
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "calorific_value_data";
	$con = mysqli_connect($servername,$username,$password,$dbname);

	if(!$con)
	{
		die("Connection failed:".mysqli_connect_error());
	}
?>
<?php
//preparing the data to be posted.
$postRequest = array(
    'PublicationObjectIds' => '408:28, 408:5328, 408:5291',
    'PublicationObjectCount' => '3',
    'LatestValue' => 'true',
    'Applicable' => 'applicableAt',
    'FromUtcDatetime' => '2021-01-01T00:00:00.000Z',
    'ToUtcDateTime' => '2021-05-22T23:00:00.000Z',
    'FileType' => 'Xml' // xml format data
);

// curl connection
$cURLConnection = curl_init('http://mip-prd-web.azurewebsites.net/DataItemViewer/DownloadFile');
//set data to be posted
curl_setopt($cURLConnection,CURLOPT_POST, true);
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($cURLConnection);
curl_close($cURLConnection);

//converting the content of the website into xml format.
$xml = simplexml_load_string($response);

//paring through the xml entities.
foreach ($xml->children() as $row) {

    $ApplicableFor = date('Y-m-d',strtotime(str_replace('/', '-',$row->ApplicableFor)));
    $DataItem = $row->DataItem;
    $Value = $row->Value;

    //inserting value of ApplicableFor , DataItem and Value xml entity into the database.

    $sql = "INSERT INTO calorific_data(ApplicableFor,Area) VALUES ('" . $ApplicableFor . "','" . $DataItem . "')";

    $result = mysqli_query($con, $sql);

    if (empty($result)) {
        $error_message = mysqli_error($con) . "\n";
    }

    $sql2 = "INSERT INTO calorific_value(Value) VALUES ('" . $Value . "')";

    $result2 = mysqli_query($con, $sql2);

    if (empty($result2)) {
        $error_message = mysqli_error($con) . "\n";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name = "viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
  <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
  <title>Display Data</title>
</head>
<body>
  <style>
  table{
    width:100%;
  }
  table th{
    background-color: #009ddc;
    border: 1pt solid #000;
    padding: 4px;
    color: white;
    font-size:18px;
    text-align: center;
  }
  .header-text{
    text-align: center;
    padding-top:1rem;
    padding-bottom:1rem;
  }
  </style>
  <div class="container">
    <h3 class="header-text">Calorific Value for Campbeltown , EA and NE area.</h3>
    <div class="row">
      <div class="col-md-6">
        <table border="1" cellspacing="2" cellpadding="2">
          <thead>
            <tr>
                <th width="35%">Applicable For</th>
                <th width="35%">Area</th>
                <th width="30%">Value</th>
            </tr>
          </thead>
          <tbody>
            <?php
            //fetching data from tables and displaying it in the web page in table format.

            $query = "SELECT D.ApplicableFor,V.Value,D.Area FROM calorific_data D, calorific_value V where D.ID = V.ID";
            if ($result3 = mysqli_query($con, $query)) {
              while ($row = mysqli_fetch_assoc($result3)) {

            ?>
            <tr>
              <td><?php echo $row['ApplicableFor']; ?></td>
              <td><?php echo $row['Area']; ?></td>
              <td><?php echo $row['Value']; ?></td>
            </tr>
            <?php
          }
          }
           ?>
          </tbody>
        </table>
      </div>
      <div class="col-md-6">
        <?php
        //graphical reprensentation of calorific value data using fushion charts.

        $strQueryg = "SELECT D.Area, AVG(V.Value) as Value from calorific_data D, calorific_value V where D.ID = V.ID GROUP BY D.Area Order By D.Area ASC";
        // Execute the query, or else return the error message.
        $resultg = $con->query($strQueryg) or exit("Error code ({$con->errno}): {$con->error}");
        // If the query returns a valid response, prepare the JSON string
        if ($resultg) {
        // The `$arrDatag` array holds the chart attributes and data
        $arrDatag = array(
        "chart" => array(
        "theme" => "fusion",
        "subCaption" => "Average Calorific value for Region",
        "xAxisName" => "Region",
        "yAxisName" => "Calorific Value",
        "bgcolor" =>"FFFFFF",
        "pieRadius"=>"70"  ,
        "useDataPlotColorForLabels"=> "1",
        "showpercentvalues"=> "1",
        "showPercentInTooltip"=> "0",
        "decimals"=>"0",
        "labelFont"=> "Verdana",
        "labelFontSize"=> "9",
        "legendPosition"=>"RIGHT",
        "pieyscale"=> "70",
        "showborder" =>"0",  "showlabels" =>"1",  "showlegend" =>"0", "palettecolors" =>"#f8bd19,#e44a00,#008ee4,#33bdda,#6baa01,#583e78"
        			)
        );

        $arrDatag["data"] = array();

        // Push the data into the array

        while($rowg = mysqli_fetch_array($resultg)) {
        array_push($arrDatag["data"], array(
        "label" => $rowg["Area"],
        "value" => $rowg["Value"]

        )
        );
        }

        /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

        $jsonEncodedDatag = json_encode($arrDatag);

        /*Create an object for the column chart. Initialize this object using the FusionCharts PHP class constructor. The constructor is used to initialize the chart type, chart id, width, height, the div id of the chart container, the data format, and the data source. */

        $columnChartg = new FusionCharts("column2D", "myFirstCharts" , '100%', 400, "chart", "json", $jsonEncodedDatag);

        // Render the chart
        $columnChartg->render();

        $con->close();

        }
        ?>
        <div id="chart" style="padding-top:15%;"><!-- Fusion Charts will render here--></div>
      </div>
    </div>
  </div>
</body>
</html>
