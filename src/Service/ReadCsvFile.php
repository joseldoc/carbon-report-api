<?php
    namespace App\Service;
    use League\Csv\Reader;

    class ReadCsvFile {
        public static function getRecordsFile(string $filePath): \Iterator {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
            return $csv->getRecords();
        }
    }