<?php
/**
 * CPMObjectEventHandler: IncidentHandler
 * Package: RN
 * Objects: Incident
 * Actions: Create, Update
 * Version: 1.2
 * Purpose: Minimal CPM handler for incident create and update.
 */
use \RightNow\Connect\v1_2 as RNCPHP;
use \RightNow\CPM\v1 as RNCPM;
/**
 * Handler class for CPM
 */
class IncidentHandler implements RNCPM\ObjectEventHandler
{

    /**
     * Apply CPM logic to object.
     * @param int $runMode
     * @param int $action
     * @param object $incident
     * @param int $cycle
     */
    public static function apply($run_Mode, $action, $incident, $cycle)
    {
        if ($cycle !== 0) return;
        if (RNCPM\ActionUpdate == $action)
        {
          $parent_id=$incident->CustomFields->CFS->Incident->ID;
          $allInvestigationClosed=true;
              if($parent_id){

                    //CPM Part1.
                      $query=RNCPHP\ROQL::queryObject("SELECT Incident FROM Incident WHERE Incident.CustomFields.CFS.Incident.ID='$parent_id'")->next();
                        while($result=$query->next()):
                              if($result->StatusWithType->Status->ID!=2):
                              $allInvestigationClosed=false;
                              break;
                              endif;
                        endwhile;

                        if($allInvestigationClosed):
                          $parent_incident=RNCPHP\Incident::fetch($parent_id);
                          $parent_incident->StatusWithType->Status=102; //Review Id.
                          $parent_incident->save(RNCPHP\RNObject::SuppressAll);
                          RNCPHP\ConnectAPI::commit();
                        endif;

                        exit;
                        // //CPM Part 2.
                        // ################### Make it resolved, ########################################
                        // # 1). if the corrective Actions are remaining.
                        // # 2). Cost Value is empty.
                        // # 3). Formal Response=1 & Formal Response Completed=0.
                        // ##############################################################################

                        // $parent_incident=RNCPHP\Incident::fetch($incident->CustomFields->CFS->Incident->ID);
                        // $cost=(int)$parent_incident->CustomFields->c->cost;
                        // $formal_response=(int)$parent_incident->CustomFields->c->formal_response;
                        // $formal_response_completed=(int)$parent_incident->CustomFields->c->formal_response_completed;
                        
                        // $results=RNCPHP\ROQL::query("select count(*) as pending  from CFS.CorrectiveAction WHERE CFS.CorrectiveAction.Incident='{$incident->ID}' AND CFS.CorrectiveAction.Complete='0'")->next();
                        // while($result=$results->next()):
                        // $corrective_actions_pending=(int)$result['pending'];
                        // break;
                        // endwhile;

                        // if($corrective_actions_pending or !$allInvestigationClosed or !$cost or $formal_response or !$formal_response_completed):
                             
                        //      $parent_incident->StatusWithType->Status=106; //Resolved.
                        //      $parent_incident->save(RNCPHP\RNObject::SuppressAll);
                        //      RNCPHP\ConnectAPI::commit();
                        // endif;

                        // ################### Make it Closed, #########################################
                        // # 1). if the corrective Actions are remaining.
                        // # 2). Cost Value is not empty.
                        // # 3). Formal Response=0 & Formal Response Completed=1.
                        // # 4). If all are true then you should close it.
                        // ##############################################################################
                        
                        // if(!$corrective_actions_pending && $allInvestigationClosed && $cost && !$formal_response && $formal_response_completed):
                             
                        //      $parent_incident->StatusWithType->Status=2; //closed.
                        //      $parent_incident->save(RNCPHP\RNObject::SuppressAll);
                        //      RNCPHP\ConnectAPI::commit();
                        // endif;

              }
         }
      }
} //Class Ends Here.

/**
 * CPM test harness
 */
class IncidentHandler_TestHarness
        implements RNCPM\ObjectEventHandler_TestHarness
{


/**
 * Set up test cases.
 */

 static $incident_get = NULL;
 		//------------------------------setup function-----------------------------------------------
 	public static function setup()
 	{
 	}

/**
 * Return the object that we want to test with. You could also return
 * an array of objects to test more than one variation of an object.
 * @param int $action
 * @param class $object_type
 * @return object | array
 */
public static function fetchObject($action, $object_type)
{
  $res = RNCPHP\ROQL::queryObject("SELECT Incident FROM Incident where id = 250")->next();
        while ($inc = $res->next()) {
            static::$incident_get = RNCPHP\Incident::fetch($inc->ID);
        }
        return (static::$incident_get);
}

/**
 * Validate test cases
 * @param int $action
 * @param object $Incident
 * @return bool
 */
public static function validate($action, $Incident)
{
     if (RNCPM\ActionUpdate == $action)
     {
      echo " Log updated ";
     }
     elseif (RNCPM\ActionCreate == $action)
     {

		echo " Log created ";
     }

    return true;
}

/**
 * Destroy every object created by this test. Not necessary since in
 * test mode and nothing is committed, but good practice if only to
 * document the side effects of this test.
 */
public static function cleanup()
{
      return;
}
}
