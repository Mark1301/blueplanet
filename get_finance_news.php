<?php

    if( isset( $_GET[ "id" ] ) ) {

        $response = array();

        $conn = new mysqli( "localhost", "news-admin", "JayMehta123#", "news_storage" );

        $lastMewsId =  ( int ) $_GET[ "id" ];

        if( $lastMewsId == -1 ) {
            
            $sql = "SELECT
                        `finance_news`.`id`, 
                        `finance_news`.`title`, 
                        `finance_news`.`url`,
                        `finance_news`.`date`, 
                        `finance_news`.`img_url`
                        FROM
                        `finance_news`
                        ORDER BY `finance_news`.`id` DESC
                        LIMIT 20";

        } else {

            $el = ( int )( $lastMewsId - 1 );
            $sl = ( int )( $lastMewsId - 21 );
            $sql = "SELECT
                    `finance_news`.`id`, 
                    `finance_news`.`title`, 
                    `finance_news`.`url`,
                    `finance_news`.`date`, 
                    `finance_news`.`img_url`
                    FROM
                    `finance_news`
                    WHERE
                    `finance_news`.`id`
                    BETWEEN
                    $sl AND $el
                    ORDER BY `finance_news`.`id` DESC
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
