<?php

abstract class ReportOutputBase implements ReportOutput {

    public static function prepareReport($report, ReportFilter $reportFilter): Report
    {
        $environment = $_SESSION['environment'];

        $report = new Report($report, $reportFilter, $environment);

        return $report;
    }
}
