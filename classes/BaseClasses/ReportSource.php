<?php

interface ReportSource
{
    public static function run(&$report): void;
}
