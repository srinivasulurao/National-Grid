<?php
namespace Custom\Widgets\test;

class test_report extends \RightNow\Widgets\Multiline {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        return parent::getData();

    }

    /**
     * Overridable methods from Multiline:
     */
    // function showColumn($value, array $header)
    // function getHeader(array $header)
}