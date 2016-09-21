<?php
namespace Custom\Widgets\investigations;

class IncidentSurvey extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);

        #########################
        ########AWESOME##########
        ########################
    }

    function getData() {
        $i_id=getUrlParm('i_id');
        $ci=&get_instance();
        $child_incidents=$ci->model('custom/CustomerFeedbackSystem')->getChildIncidentIds($i_id);

        $correctiveActionExist=0;//Lets assume they don't exist
        if(sizeof($child_incidents)):
            foreach($child_incidents as $key=>$value):
            $correctiveActions=$ci->model('custom/CustomerFeedbackSystem')->fetchCorrectiveActionsModel($value);
                  if(sizeof($correctiveActions)):
                    $correctiveActionExist=1;
                    break;
                  endif;
            endforeach;
      endif;


        $incident=$ci->model('custom/CustomerFeedbackSystem')->getIncident($i_id);
        $close_id=$ci->model('custom/CustomerFeedbackSystem')->getStatusIdByStatusName('closed');
        $complaintClosed=($incident->StatusWithType->Status->ID==$close_id)?1:0;
        if($complaintClosed && $ci->session->getSessionData('last_incident_page_visited')=="update"){

          if($correctiveActionExist){
            $this->data['survey_link']="/ci/documents/detail/1/AvMG~wrxDv8S7xb~Gv8e~yJVJv8q_aL7FGA6IT7~Pv~z/5/8/12/45ca9f62c58ab15b9fe123a36b3a420c68eccd66/13/MTQ3MzQzNDU2OA!!/15/MTE5/6/1/7/$i_id";
          }
          else{
            $this->data['survey_link']="/ci/documents/detail/1/AvMG~wr5Dv8S9xb~Gv8e~yIzJv8q_aL9FGAqIT7~Pv~j/5/4/12/ef711c3c152a653de162256e41b2a2312e1d5f67/13/MTQ3MzQzNjAxNg!!/15/NA!!/6/1/7/$i_id";
          }
      }
      if($ci->session->getSessionData('last_incident_page_visited')=="new"){
        // to be shown when the incident is newly created.
          $this->data['survey_link']="/ci/documents/detail/1/AvMG~wrpDv8S6Rb~Gv8e~yJFJv8q_aL7FGAcIT7~Pv~V/5/11/12/245cfe1718889dd43ee26d6654e354af8331c668/13/MTQ3MjE0MDAwMQ!!/6/1/7/$i_id";
      }



        return parent::getData();

    }
}
