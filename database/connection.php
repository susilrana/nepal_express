<?php

$HOST = 'localhost';
$USER = 'root';
$PASS = '';
$DB = 'bus_ticket_booking_system';

$CON = mysqli_connect($HOST, $USER, $PASS, $DB);

if (!$CON) {

    echo 'Connection failed';
}
