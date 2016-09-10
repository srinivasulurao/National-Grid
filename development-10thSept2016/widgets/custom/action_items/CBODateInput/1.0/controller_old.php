<?php
namespace Custom\Widgets\action_items;

class CBODateInput extends \RightNow\Widgets\DateInput {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        return parent::getData();

    }

    /**
     * Overridable methods from DateInput:
     */
    // public function outputSelected($index, $itemIndex)
    // protected function getConstraints(array $constraints)
    // function($type, $constraint) use ($useDefines)
    // protected function getMetaConstraints()
    // protected function getDateArray($year, $date, $type = 'max')
}