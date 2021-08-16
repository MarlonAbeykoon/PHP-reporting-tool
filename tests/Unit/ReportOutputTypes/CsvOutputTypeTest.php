<?php

use PHPUnit\Framework\TestCase;

class CsvOutputTypeTest extends TestCase {

    public function test_it_generates_csv():void
    {
        $report = ReportDummy::ReportObject();
        (new CsvOutputType('mysqltestReport'))->generate($report);
        $this->assertFileExists('mysqltestReport.sql.csv');
    }

    public function test_csv_contains_correct_data():void
    {
        $report = ReportDummy::ReportObject();
        (new CsvOutputType('mysqltestReport'))->generate($report);
        $this->assertFileExists('mysqltestReport.sql.csv');
        $contents = file_get_contents('mysqltestReport.sql.csv');

        $this->assertSame(
            "brand_name,turnover\n0-brand,234\n1-brand,34\n", $contents);
    }
}
