#!/bin/sh

timestamp() {
        date +"%T"
}

while true
do

        sudo php jsonToDBFinance.php


        echo " - News Added - Current time: "
        timestamp
        sleep 600
done
