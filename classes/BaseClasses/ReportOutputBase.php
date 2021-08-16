<?php

abstract class ReportOutputBase implements ReportOutput {

    protected const FILE_EXTENSION = '';
    protected string $reportName;

    public function prepareReport(ReportFilter $reportFilter): Report
    {
        $environment = $_SESSION['environment'];

        return new Report($this->reportName, $reportFilter, $environment);
    }
}
