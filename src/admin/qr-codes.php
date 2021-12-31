<?php

namespace easyGastro\admin;

use DB_Admin_Tables;
use easyGastro\DB_User;
use easyGastro\Pages;
use easyGastro\SiteTemplate;

require_once '../SiteTemplate.php';
require_once '../Pages.php';
require_once '../db.php';
require_once '../DB_User.php';
require_once 'users/DB_Admin_Users.php';
require_once 'tables/DB_Admin_Tables.php';
require_once 'AdminHeader.php';


session_start();

$row = DB_User::getDataOfUser();
Pages::checkPage('Admin', $row);


$header = AdminHeader::getHeader() . AdminHeader::getNavigation(basename(__FILE__));

$tableIds = array_column(DB_Admin_Tables::getTables(), 'pk_tischnr_id');
$tableOptions = '';
foreach ($tableIds as $tableId) {
    $tableOptions .= "<option value='$tableId'>$tableId</option>";
}

$body = <<<BODY
<div class="col col-10 mx-auto pt-3 d-flex flex-column justify-content-center h-100">
    
    <div class="d-flex justify-content-center flex-wrap mx-4">
        <div class="d-flex justify-content-center">
            <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#generateNewTableCodes">Alle Tischcodes neu erstellen</button>
        </div>
    
        <div class="modal fade" id="generateNewTableCodes" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header d-flex justify-content-center border-bottom-0">
                        <h3 class="modal-title">Alle Tischcodes neu erstellen</h3>
                    </div>
                    <div class="modal-footer d-flex justify-content-between border-top-0">
                        <form method="post" action="qr-codes/generateNewTableCodes.php">
                            <button type="submit" class="btn btn-primary text-white fs-5">OK</button>
                        </form>
                        <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="d-flex justify-content-center mx-4">
            <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#generateNewTableCode">Tischcode neu erstellen</button>
        </div>
        
        <form method="post" action="qr-codes/generateNewTableCode.php">
            <div class="modal fade" id="generateNewTableCode" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <div class="modal-header d-flex justify-content-center border-bottom-0">
                                <h3 class="modal-title">Tischcode neu erstellen</h3>
                            </div>
                            <div class="modal-body">
                                <label for="tableId" class="form-label">Tisch</label>
                                <select class="form-select" id="tableId" aria-label="Table Selector" name="tableId" required>
                                    <option disabled hidden selected value="">Tisch</option>
                                    $tableOptions
                                </select>
                            </div>
                            <div class="modal-footer d-flex justify-content-between border-top-0 mt-3">
                                <button type="submit" class="btn btn-primary text-white fs-5">OK</button>
                                <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
                            </div>
                        </div>
                </div>
            </div>
        </form>
    </div>

    <div class="d-flex justify-content-center flex-wrap mt-4">
        <div class="d-flex justify-content-center mt-3 mb-5 mx-3">
            <a href="qr-codes/pdf.php" target="_blank"><button class="btn btn-secondary bg-gray">PDF für alle Tischcodes</button></a>
        </div>
        
        
        <div class="d-flex justify-content-center mx-3">
            <button class="btn btn-secondary mt-3 mb-5 bg-gray" data-bs-toggle="modal" data-bs-target="#selectPdfTableCodes">PDF für einzelne Tischcodes</button>
        </div>
        
        <form method="get" action="qr-codes/pdf.php" target="_blank">
            <div class="modal fade" id="selectPdfTableCodes" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <div class="modal-header d-flex justify-content-center border-bottom-0">
                                <h3 class="modal-title">PDF für einzelne Tischcodes</h3>
                            </div>
                            <div class="modal-body">
                                <label for="tableId" class="form-label">Tisch</label>
                                <select class="form-select" id="tableId" aria-label="Table Selector" name="table[]" required multiple>
                                    <option disabled hidden selected value="">Tisch</option>
                                    $tableOptions
                                </select>
                            </div>
                            <div class="modal-footer d-flex justify-content-between border-top-0 mt-3">
                                <button type="submit" class="btn btn-primary text-white fs-5" data-bs-dismiss="modal">OK</button>
                                <button type="button" class="btn btn-secondary fs-5" data-bs-dismiss="modal">Zurück</button>
                            </div>
                        </div>
                </div>
            </div>
        </form>
    </div>

</div>
BODY;

print(SiteTemplate::render('QR-Codes - Admin - EGS', $header, $body));
