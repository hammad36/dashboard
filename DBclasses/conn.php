<?php

$conn = mysqli_connect('localhost', 'hammad', 'My@2530', 'dash');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
