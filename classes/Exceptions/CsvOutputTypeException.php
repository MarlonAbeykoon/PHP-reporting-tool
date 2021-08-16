<?php

class CsvOutputTypeException extends Exception implements ReportingToolException {

    protected $message;

    public function __construct(string $message)
    {
        $this->message = $message;
        parent::__construct($this->message());
    }

    public function message(): string
    {
        return $this->message;
    }

    public function reasonKey(): string
    {
        return "csv_output_exception";
    }
}
