<?php 
    $project_root = "/var/www/rent-my-car";
    require "$project_root/simple_html_dom.php";
    
    exec("/usr/bin/phantomjs --ssl-protocol=any $project_root/drivejoy.js", $result);
    $drivejoy = implode($result);
    file_put_contents("$project_root/drivejoy_dump",$drivejoy);

    $html = file_get_html("$project_root/drivejoy_dump");
    
    if(get_class($html) == "simple_html_dom"){
        $day_price   = $html->find('div.day   div', 0)->plaintext;
        $week_price  = $html->find('div.week  div', 0)->plaintext;
        $month_price = $html->find('div.month div', 0)->plaintext;

        $response_rate = $html->find('div.response-rate span', 0)->plaintext;
        $response_time = $html->find('div.response-time span', 0)->plaintext;
        
        $pricing = "$day_price/day, $week_price/week, $month_price/month";
        file_put_contents("$project_root/pricing",$pricing);

        $responsiveness =  preg_replace('/\s+/', ' ',"I have been known to respond in $response_time on average and $response_rate of the time");
        file_put_contents("$project_root/responsiveness",$responsiveness);
    }{
        else echo get_class($html);
        echo "\nSomething just went royally wrong, wait till next run"
    }
?>
