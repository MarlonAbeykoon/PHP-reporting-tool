<?php

class ReportDummy {

    public static function ReportObject(): Report
    {
        self::createSqlFile();
        $report = new Report('test/testReport.sql');
        $report->report = 'test/testReport.sql';
        $report->options = [
            'Environment' => 'main',
            'Type' => 'Mysql',
            'Database' => 'mysql',
            'Name' => 'test/testReport.sql',
            'Query' => SqlQueryDummy::PlainSqlQuery(),
            'DataSet' => [
                ['brand_name' => '0-brand',
                    'turnover' => 234],
                ['brand_name' => '1-brand',
                    'turnover' => 34],
            ],
        ];
        $report->raw_query = SqlQueryDummy::PlainSqlQuery();

        return $report;
    }

    private static function createSqlFile(): void {
        file_put_contents("reports/test/testReport.sql",SqlQueryDummy::PlainSqlQuery());
    }
}
