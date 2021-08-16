<?php

class CsvOutputType extends ReportOutputBase {

    protected const FILE_EXTENSION = '.csv';
    protected const MODE = 'w';
    private string $separator = ',';
    private string $enclosure = '"';
    protected string $reportName;

    public function __construct(string $reportName)
    {
        $this->reportName = $reportName;
    }

    public function generate(Report $report): void {

        $file_name = preg_replace(array('/[\s]+/','/[^0-9a-zA-Z\-_\.]/'),array('_',''), $report->options['Name']);

        $this->store($file_name, $report->options['DataSet']);
    }

    protected function store($file_name, $dataset): void {
        $out = fopen($file_name.self::FILE_EXTENSION, self::MODE);

        fputcsv($out, array_keys($dataset[0]), $separator = $this->separator, $enclosure = $this->enclosure);

        foreach ($dataset as $value){
            fputcsv($out, $value, $separator = $this->separator, $enclosure = $this->enclosure);
        }
        fclose($out);
    }

}
