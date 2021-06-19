<?php

    if( isset( $_GET[ "id" ] ) ) {

        $response = array();

        $conn = new mysqli( "localhost", "blueplanet", "Bluepl@net2021", "BLUE" );

        $lastMewsId =  ( int ) $_GET[ "id" ];

        if( $lastMewsId == -1 ) {

            $sql = "SELECT
                        `blueplanet_News`.`id`,
                        `blueplanet_News`.`title`,
                        `blueplanet_News`.`url`,
                        `blueplanet_News`.`date`,
                        `blueplanet_News`.`img_url`
                        FROM
                        `blueplanet_News`
                        ORDER BY `blueplanet_News`.`id` DESC
                        LIMIT 20";

        } else {

            $el = ( int )( $lastMewsId - 1 );
            $sl = ( int )( $lastMewsId - 21 );
            $sql = "SELECT
                    `blueplanet_News`.`id`,
                    `blueplanet_News`.`title`,
                    `blueplanet_News`.`url`,
                    `blueplanet_News`.`date`,
                    `blueplanet_News`.`img_url`
                    FROM
                    `blueplanet_News`
                    WHERE
                    `blueplanet_News`.`id`
                    BETWEEN
                    $sl AND $el
                    ORDER BY `blueplanet_News`.`id` DESC
                    ";

        }

        $result = mysqli_query($conn, $sql);

        if ( mysqli_num_rows($result) != 0 ) {

            while( $row = mysqli_fetch_assoc($result) ) {
                $myDate = new DateTime($row['date']);
                $news = [ "id"=>$row['id'], "title"=>$row['title'], "url"=>$row['url'], "date"=>$myDate->format("H:i | j M"), "img_url"=>$row['img_url'] ];
                array_push( $response, $news );
            }

        }

        echo json_encode( $response );
    }

?>
