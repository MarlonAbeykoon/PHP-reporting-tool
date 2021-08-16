<?php

interface ReportingToolException
{
    public function message(): string;
    public function reasonKey(): string;
}
