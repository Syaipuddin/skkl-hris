<?php
	include "template/topKosong.php";
        include "include/JSON.php";
        
        $year = date('Y');
	$month = date('m');
        $json =new Services_JSON();
        
        $arraydong= array(
	
                            array(
                                    'id' => 111,
                                    'title' => "Event1",
                                    'start' => "$year-$month-10",
                                    'url' => "http://yahoo.com/"
                            ),

                            array(
                                    'id' => 222,
                                    'title' => "Event2",
                                    'start' => "$year-$month-20",
                                    'end' => "$year-$month-22",
                                    'url' => "http://yahoo.com/"
                            )

                    );
	echo $json->encode($arraydong);

	include "template/bottomKosong.php";
        
	

	

?>
