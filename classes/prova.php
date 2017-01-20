<?php
/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 18/01/2017
 * Time: 15:07
 */

$temp = json_decode('["1","2"]');
$temp[] = "1";
echo json_encode($temp);