<?php

interface ReportOutput {
    public static function generate($report): void;
    public static function prepareReport($report, ReportFilter $reportFilter): Report;
}

