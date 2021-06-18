#!/bin/sh

timestamp() {
        date +"%T"
}

while true
do
        sudo php jsonToDB.php
        sudo php jsonToDBFinance.php
        sudo php jsonToWixFinance.php
        sudo php jsonToDBGujarat.php
        sudo php jsonToDBSaurashtra.php
        
        echo " - News Added - Current time: "
        timestamp
        sleep 600
done