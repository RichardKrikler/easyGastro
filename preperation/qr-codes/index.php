<form method="post">
    <button type="submit" name="showCodes" value="Show Codes">Show Codes</button>
    <button type="submit" name="renewCodes" value="Renew Codes">Renew Codes</button>
</form>


<?php

require_once 'QRCode.php';
$qrcode = new QRCode('localhost', 'egs', 'root', '');

if(isset($_POST['showCodes']))
{
    echo $qrcode->showAllCodes();
}

if(isset($_POST['renewCodes']))
{
    $qrcode->renewAllCodes();
    echo $qrcode->showAllCodes();
}

?>