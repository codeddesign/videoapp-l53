<?php

namespace App\Transformers\Spreadsheet;

interface SpreadsheetTransformer
{
    /**
     * @return array
     */
    public function header();

    /**
     * @param $obj
     *
     * @return array
     */
    public function transform($obj);
}
