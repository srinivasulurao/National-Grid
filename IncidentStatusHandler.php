<?php
/**
* CPMObjectEventHandler: IncidentStatusHandler
* Package: RN
* Objects: Incident
* Actions: Create, Update
* Version: 1.2
* Purpose: CPM handler for incident update.
* 1. Update the desired service location according to the Primary contact registered for the incident
* 2. Updatethe service attribute fields
*/

// Connect API
require_once(get_cfg_var('doc_root') . "/ConnectPHP/Connect_init.php");
initConnectAPI();

use \RightNow\Connect\v1_2 as RNCPHP;
use \RightNow\CPM\v1 as RNCPM;

/**
* Handler class for CPM
*/
class IncidentStatusHandler implements RNCPM\ObjectEventHandler
{
public static function apply($run_Mode, $action, $incident, $cycle)
    {
        if ($cycle !== 0) return;
if (RNCPM\ActionUpdate == $action){
        $c_id= $incident->PrimaryContact->ID;
        $incident->Subject="Subject Changed VIA CPM !";
        $incident->save();
      }
    }
}



class IncidentStatusHandler_TestHarness implements RNCPM\ObjectEventHandler_TestHarness
{

    public static function setup()
    {
      if (RNCPM\ActionUpdate == $action){
      $incident=RNCPHP\Incident::fetch(250);
      $incident->Subject="Subject Changed VIA CPM !";
      $incident->save();
}
    }


    public static function fetchObject($action, $object_type){
      return true;
     }

    public static function validate($action, $incident) {
      return true;
    }

    public static function cleanup(){
    return true;
    }

}


?>
