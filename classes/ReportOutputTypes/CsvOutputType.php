<?php

class CsvOutputType extends ReportOutputBase {

    public static function generate($report): void {

        $file_name = preg_replace(array('/[\s]+/','/[^0-9a-zA-Z\-_\.]/'),array('_',''),$report->options['Name']);

        self::store($file_name, $report->options['DataSet']);
    }

    protected static function store($file_name, $dataset): void {
        $out = fopen($file_name.'.csv', 'w');

        fputcsv($out, array_keys($dataset[0]), $separator = ",", $enclosure = '"', $escape_char = "\\");

        foreach ($dataset as $value){
            fputcsv($out, $value, $separator = ",", $enclosure = '"', $escape_char = "\\");
        }
        fclose($out);
    }

}
