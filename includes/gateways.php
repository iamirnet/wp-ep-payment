<?php
$dir = __DIR__.'/../gateways/';
require_once $dir.'zarinpal.php';
require_once $dir.'mellat.php';
require_once $dir.'pay.ir.php';
require_once $dir.'sadad.php';
require_once $dir.'saman.php';
global $zarinpal,$mellat,$payir,$saman,$sadad;
$zarinpal = new ZarinPal();
$mellat = new Mellat();
$payir = new PayIR();
$sadad = new Sadad();
$saman = new Saman();

