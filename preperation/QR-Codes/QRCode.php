<?php

class QRCode
{
    public function __construct(string $host, string $dbname, string $user, string $passwd)
    {
        $this->db = new PDO("mysql:host=$host;dbname=$dbname", $user, $passwd);
        $this->tableSelect = 'SELECT * FROM Tisch';
    }

    public function showAllCodes(): string
    {
        $allCodes = '';
        $tableSQL = $this->db->prepare($this->tableSelect);
        if ($tableSQL->execute()) {
            while ($table = $tableSQL->fetch()) {
                $allCodes .= <<<QRCODE
                <div class="qrcode">
                <img src="qrcode-generator.php?code={$table['tischcode']}">
                <div>Tisch: {$table['pk_tischnr_id']}</div>
                <div>Code: {$table['tischcode']}</div>
                QRCODE;
            }
        }
        return $allCodes;
    }

    public function renewAllCodes()
    {
        $tableSQL = $this->db->prepare($this->tableSelect);
        if ($tableSQL->execute()) {
            $newTableCodes = $this::newValidCodes();
            while ($table = $tableSQL->fetch()) {
                $updateTableSQL = $this->db->prepare("UPDATE Tisch SET tischcode = '{$newTableCodes[$table['pk_tischnr_id']]}'WHERE pk_tischnr_id = {$table['pk_tischnr_id']};");
                $updateTableSQL->execute();
            }
        }
    }

    private function newValidCodes()
    {
        $tableSQL = $this->db->prepare($this->tableSelect);
        if ($tableSQL->execute()) {
            while ($table = $tableSQL->fetch()) {
                $currentTableIds[] = $table['pk_tischnr_id'];
                $currentTableCodes[] = $table['tischcode'];
            }
        }
        foreach ($currentTableIds as $tableId) {
            do {
                $randomCode = $this::generateRandomString();
            } while (in_array($randomCode, $currentTableCodes));
            $newTableCodes[$tableId] = $randomCode;
        }
        return $newTableCodes;
    }

    private function generateRandomString($length = 5): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
