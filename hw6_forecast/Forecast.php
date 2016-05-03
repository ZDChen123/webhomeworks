
<html>
<head>
    <title>ForeCast</title>
    <style type="text/css">
        p{
        border:0;
        margin:0;
        padding:0;
        width:450px;
        }
    .base{
        border:3px solid black;
        height:230px;
        width:400px;
        position: relative;
        margin-left: auto;
        margin-right: auto;
        align-content: center;
        padding:20px;
    }
    input.button_main{
            background-color: #E8E8E8;
            border-width:1px;
        }
    td.row1{
        width:150px;
    }
    .base2{
        border:3px solid black;
        height:600px;
        width:400px;
        position: relative;
        margin-left: auto;
        margin-right: auto;
        align-content: center;
        padding:20px;
        margin-top: 30px;
    }
    .title{
        text-align:center;
        width:100%;
        border-width:0;
    }
    .table2{
        width:100%;
        border-width:0;
    }
    </style>
</head>
<body>
    <?php if($_POST["submit"]):  ?>
    <?php
        $street=$_POST["street"];
        $city=$_POST["city"];
        $state=$_POST["state"];
        $street_string = str_replace(" ", "+", $street); 
        $city_string = str_replace(" ","+",$city);    
        //extract information from xml
$google_url="https://maps.googleapis.com/maps/api/geocode/xml?"."address=".$street_string.",".$city_string.",".$state."&"."key=AIzaSyANWWs3yKb-Eg2yQQjiklLxh0phP81UqI4";
        $xml = simplexml_load_file($google_url);
        $lat= $xml->result->geometry->location->lat;
        $lng= $xml->result->geometry->location->lng;
        if($_POST["degree1"] == "Fahrenheit"){
           $units="us";
        }
        else if($_POST["degree2"]=="Celsius")
        {
            $units="si";}
$forecast_url="https://api.forecast.io/forecast/a110e3588202611ca445c2075b93c326/".$lat.",".$lng."?units=".$units."&exclude=flags";
        //extract forecast message from json
        $contents = file_get_contents($forecast_url);  
        $results = json_decode($contents); 
        $icon = $results->{'currently'}->{'icon'};
        $temperature = $results->{'currently'}->{'temperature'};
        $precipitation = $results->{'currently'}->{'precipIntensity'};
        $chance = $results->{'currently'}->{'precipProbability'};
        $wind = $results->{'currently'}->{'windSpeed'};
        $dewpoint= $results->{'currently'}->{'dewPoint'};
        $humidity= $results->{'currently'}->{'humidity'};
        $visibility= $results->{'currently'}->{'visibility'};
        $sunrise= $results->{'daily'}->data[0]->sunriseTime;
        $sunset= $results->{'daily'}->data[0]->sunsetTime;
        $timezone= $results->{'timezone'};
        date_default_timezone_set($timezone);
        //explain the data
        $date1=date_create();
        date_timestamp_set($date1,$sunrise);
        $sunrisetime=date_format($date1,"h:i A");
        $date2=date_create();
        date_timestamp_set($date2,$sunset);
        $sunsettime=date_format($date2,"h:i A");
        //display the data in tabular format
        if($icon == "clear-day"){
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/clear.png";
            $title="Clear";}
        else if($icon == "clear-night"){
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/clear_night.png";
            $title="Clear";}
        else if($icon == "rain"){
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/rain.png";
            $title="Rain";}
        else if($icon == "snow"){
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/snow.png";
            $title="Snow";}
        else if($icon == "sleet"){
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/sleet.png";
            $title="Sleet";}
        else if($icon == "wind"){
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/wind.png";
            $title="Wind";}
        else if($icon == "fog"){
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/fog.png";
            $title="Fog";}
        else if($icon == "cloudy"){
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/cloudy.png";
            $title="Cloudy";}
        else if($icon == "partly-cloudy-day"){
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/cloud_day.png";
            $title="Cloud";}
        else{
            $image="http://cs-server.usc.edu:45678/hw/hw6/images/cloud_night.png";
            $title="Cloud";}
        if($precipitation<0.002)
            $prec="None";
        else if(($precipitation>=0.002) && ($precipitation<0.017))
            $prec = "Very Light";
        else if(($precipitation>=0.017) && ($precipitation<0.1))
            $prec = "Light";
        else if(($precipitation>=0.1) && ($precipitation<0.4))
            $prec = "Moderate";
        else
            $prec = "Heavy";
        $html = "<div  class = 'base2'><table class='table2'><tr class='title'><td colspan='2'><h2>";
        $html = $html.$title."</h2></td></tr>";
        $html = $html."<tr class='title'><td colspan='2'><h2>";
        $html = $html.$temperature;
        if($units=="us"){
            $html = $html."&deg F"."</h2></td></tr>";}
        else{
            $html = $html."&deg C"."</h2></td></tr>";}
        $html = $html."<tr class='title'><td colspan='2'>";
        $html = $html."<img src='".$image."'>"."</td></tr>";
        $html = $html."<tr><td>Precipation:</td><td>".$prec."</td></tr>";
        $html = $html."<tr><td>Chance of Rain:</td><td>".($chance*100)."%</td></tr>";
        $html = $html."<tr><td>Wind Speed:</td><td>".$wind." mph</td></tr>";
        $html = $html."<tr><td>Dew Point:</td><td>".$dewpoint."</td></tr>";
        $html = $html."<tr><td>Humidity:</td><td>".($humidity*100)." %</td></tr>";
        $html = $html."<tr><td>Visibility:</td><td>".$visibility." mi</td></tr>";
        $html = $html."<tr><td>Sunrise:</td><td>".$sunrisetime."</td></tr>";
        $html = $html."<tr><td>Sunset:</td><td>".$sunsettime."</td></tr>";
        $html = $html."</table></div>";
        
    ?>
<?php endif; ?>
    <H1 style="text-align:center">Forecast Search</H1>
    <div class="base">
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <table>
                <tr>
                    <td class="row1"><label for="street">Street Address:* </label></td>
                    <td><input type="text" name="street" value="" style="width:170px"></td>
                </tr>           
            
                <tr>
                    <td class="row1"><label for="city">City:* </label></td>
                    <td><input type="text" name="city" value="" style="width:170px"></td>
                </tr>
                <tr>
                    <td class="row1"><label for="state">State:* </label></td>
                    <td><SELECT name="state" style="width:150" size=1>
                    <option>Select your state...</option>    
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iwoa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">MAssachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                    </SELECT>
                    </td>
                </tr>
                <tr>
                    <td class="row1"><label for="degree">Degree:*</label></td>
                    <td>
                    <input type="radio" name="degree1" value="Fahrenheit" checked>Fahrenheit
                    <input type="radio" name="degree2" value="Celsius">Celsius
                    </td>
                </tr>
                <tr>
                    <td class="row1"></td>
                    <td>
                    <input type="submit" name="submit" value="Search" class="button_main" >
                    <input type="reset" name="reset" value="Clear" class="button_main">
                    </td>   
                </tr>
            </table>
            <p>
                <i>* - Mandatory fields.</i><br><br>
                <center><a href="http://forecast.io/">Powered by Forecast.io</a></center>
            </p>
        </form>
    </div>
    <?php 
        echo $html;
        ?>
</body>
</html>
