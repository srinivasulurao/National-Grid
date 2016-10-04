RightNow.namespace('Custom.Widgets.investigations.CorrectiveActions');
Custom.Widgets.investigations.CorrectiveActions = RightNow.Widgets.extend({
    /**
     * Widget constructor.
     */
    constructor: function() {
          //Show total Corrective Actions.
          updateCountStatus();


          YUI().use('transition','event','panel', function(Y) {

          	//Show, Hide Form
          	var add_tca = Y.one('#add_tca');

            add_tca.on("click", function (e) {
            	Y.one('#add_corrective_action').toggleView();
            	add_button_text=document.getElementById('add_tca').innerText;
            	if(add_button_text=="+ Add"){
                  document.getElementById('corrective_actions_edit_id').value="";
                  document.getElementById('corrective_actions_details').value="";
                  document.getElementById('corrective_actions_description').value="";
                  document.getElementById('corrective_actions_complete').value="";
                  document.getElementById('corrective_actions_due_date').value="";
                  document.getElementById('corrective_actions_completion_date').value="";
                  document.getElementById('editCorrectiveAction').style.display="none";
                  document.getElementById('submitCorrectiveAction').style.display="block"; 
                	add_tca.setContent("- Hide");
            	}
            	else{
            	add_tca.setContent("+ Add");
            	}

            	 e.preventDefault();
            	 e.stopPropagation();
            });



            //Submit & edit Corrective Action.

            sca=Y.one("#add_corrective_action");
            sca.on("submit",function (e){

            	var detail=document.getElementById('corrective_actions_details').value;
            	var description=document.getElementById('corrective_actions_description').value;
            	var complete=document.getElementById('corrective_actions_complete').value;
            	var due_date=document.getElementById('corrective_actions_due_date').value;
            	var completion_date=document.getElementById('corrective_actions_completion_date').value;
            	var i_id=document.getElementById('corrective_actions_iid').value;
              var edit_id=document.getElementById('corrective_actions_edit_id').value;
              var action=(edit_id)?"editCorrectiveAction":"addCorrectiveAction";

                RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/"+action,{detail:detail,description:description,complete:complete,due_date:due_date,completion_date:completion_date,i_id:i_id,edit_id:edit_id},{
                      successHandler: function (response) {

                          if(response.responseText!=""){
                              document.getElementById('correctiveActionList').innerHTML=response.responseText;
                              document.getElementById('corrective_actions_details').value="";
                             	document.getElementById('corrective_actions_description').value="";
                             	document.getElementById('corrective_actions_complete').value="";
                             	document.getElementById('corrective_actions_due_date').value="";
                             	document.getElementById('corrective_actions_completion_date').value="";

                             	Y.one('#add_corrective_action').toggleView();
                             	add_tca.setContent("+ Add");
                             	updateCountStatus();
                          }

                      },
                      async:false,
                      scope: this,
                      json: false,
                      type: "POST"
                  });
            });

            //Delete Corrective Actions
            dca=Y.one("#delete_corrective_actions");
            dca.on("click",function (e){

            	class_length=document.getElementsByClassName('corrective_action_checkbox').length;
            	total_deletes=new Array();
            	var j=0;
            	for(i=0;i<class_length;i++){
            		if(document.getElementsByClassName('corrective_action_checkbox')[i].checked==true){
            		total_deletes[j]=document.getElementsByClassName('corrective_action_checkbox')[i].id;
            		j++;
            		}
            	}

            	if(j){
            	Y.one(".lpw").show(true);
		            	RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/deleteCorrectiveActions",{input:total_deletes.join('|')},{
		                    successHandler: function (response) {

		                        if(response.responseText!=""){
		                           document.getElementById('correctiveActionList').innerHTML=response.responseText;
		                           Y.one(".lpw").hide(true);
		                           updateCountStatus();
		                        }

		                    },
		                    async:false,
		                    scope: this,
		                    json: false,
		                    type: "POST"
		                });

               }

            });



            sca=Y.one("#complete_corrective_actions");
            sca.on("click",function (e){

            	class_length=document.getElementsByClassName('corrective_action_checkbox').length;
            	total_sca=new Array();
            	var j=0;
            	for(i=0;i<class_length;i++){
            		if(document.getElementsByClassName('corrective_action_checkbox')[i].checked==true){
            		total_sca[j]=document.getElementsByClassName('corrective_action_checkbox')[i].id;
            		j++;
            		}
            	}

            	if(j){
            		Y.one(".lpw").show(true);
		            	RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/changeCorrectiveActionStatus",{input:total_sca.join('|'),status:1},{
		                    successHandler: function (response) {

		                        if(response.responseText!=""){
		                           document.getElementById('correctiveActionList').innerHTML=response.responseText;
		                           Y.one(".lpw").hide(true);
		                           updateCountStatus();
		                        }

		                    },
		                    async:false,
		                    scope: this,
		                    json: false,
		                    type: "POST"
		                });

            	}
            });




            sca=Y.one("#incomplete_corrective_actions");
            sca.on("click",function (e){

            	class_length=document.getElementsByClassName('corrective_action_checkbox').length;
            	total_sca=new Array();
            	var j=0;
            	for(i=0;i<class_length;i++){
            		if(document.getElementsByClassName('corrective_action_checkbox')[i].checked==true){
            		total_sca[j]=document.getElementsByClassName('corrective_action_checkbox')[i].id;
            		j++;
            		}
            	}

            	if(j){
            		Y.one(".lpw").show(true);
		            	RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/changeCorrectiveActionStatus",{input:total_sca.join('|'),status:0},{
		                    successHandler: function (response) {

		                        if(response.responseText!=""){
		                           document.getElementById('correctiveActionList').innerHTML=response.responseText;
		                           Y.one(".lpw").hide(true);
		                           updateCountStatus();
		                        }

		                    },
		                    async:false,
		                    scope: this,
		                    json: false,
		                    type: "POST"
		                });

            	}

            });


            sca=Y.one("#edit_corrective_actions");
            sca.on("click",function (e){

            	class_length=document.getElementsByClassName('corrective_action_checkbox').length;
            	total_sca=new Array();
            	var j=0;
            	for(i=0;i<class_length;i++){
            		if(document.getElementsByClassName('corrective_action_checkbox')[i].checked==true){
            		ca=document.getElementsByClassName('corrective_action_checkbox')[i].id;
            		j++;
            		}
            	}

            	if(parseInt(j)==1){
            		Y.one(".lpw").show(true);
		            	RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/getCorrectiveAction",{input:ca},{
		                    successHandler: function (response) {

		                        if(response.responseText!=""){
		                        	//console.log(response.responseText);
		                           ca_json=JSON.parse(response.responseText);
		                           document.getElementById('corrective_actions_details').value=ca_json.Details;
		                           document.getElementById('corrective_actions_description').value=ca_json.Description;
		                           document.getElementById('corrective_actions_complete').value=ca_json.Complete;
		                           document.getElementById('corrective_actions_completion_date').value=ca_json.CompletionDate;
		                           document.getElementById('corrective_actions_due_date').value=ca_json.DueDate;
		                           document.getElementById('corrective_actions_edit_id').value=ca_json.ID;

		                           Y.one('#add_corrective_action').show(true);
		                           disable_enable_completion_date();
		                           add_tca.setContent("- Hide");
		                           Y.one(".lpw").hide(true);
		                           document.getElementById('submitCorrectiveAction').style.display="none";
	                             document.getElementById('editCorrectiveAction').style.display="block";
		                        }

		                    },
		                    async:false,
		                    scope: this,
		                    json: false,
		                    type: "POST"
		                });

            	}
            	else{
            		alert("Please Select One Item to edit !");
            	}

            });





          }); //Events ends here.


          wid_min_date=document.getElementById('wid_min_date').value;
//Add a calendar
  //Calendar1
  var yahoo_yui=YUI();
   yahoo_yui.use('calendar', 'datatype-date', 'cssbutton', function (G) {

      calendar = new G.Calendar({
      contentBox: "#cacd",
      width:'340px',
      showPrevMonth: false,
      showNextMonth: true,
      minimumDate: new Date(wid_min_date)
     }).render();

     var dtdate = G.DataType.Date;
       calendar.on("selectionChange", function (ev) {
       var newDate = ev.newSelection[0];
       G.one("#corrective_actions_completion_date").set('value',dtdate.format(newDate));
       G.one("#cacd").toggleView();
    });


    G.all("#corrective_actions_completion_date,#toggleCalendar1").on('click', function (ev) {
      G.one('#cacd').toggleView();
      ev.preventDefault();
      //calendar.set('showPrevMonth', !(calendar.get("showPrevMonth")));
    });

    //Calendar 2
     calendar = new G.Calendar({
      contentBox: "#cadd",
      width:'340px',
      showPrevMonth: false,
      showNextMonth: true,
      minimumDate: new Date(wid_min_date)
     }).render();


     var dtdate = G.DataType.Date;
       calendar.on("selectionChange", function (ev) {
       var newDate = ev.newSelection[0];
       G.one("#corrective_actions_due_date").set('value',dtdate.format(newDate));
       G.one("#cadd").toggleView();
    });


    G.all("#corrective_actions_due_date,#toggleCalendar2").on('click', function (ev) {
      G.one('#cadd').toggleView();
      ev.preventDefault();
      calendar.set('showPrevMonth', !(calendar.get("showPrevMonth")));
    });


}); // G use ends here.

    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});



function centralCheck(){
	class_length=document.getElementsByClassName('corrective_action_checkbox').length;

            	for(i=0;i<class_length;i++){
            		if(document.getElementById('centralCheck').checked==true){
            		document.getElementsByClassName('corrective_action_checkbox')[i].checked=true;
            		}
            		else
            		document.getElementsByClassName('corrective_action_checkbox')[i].checked=false;
            	}
}

function disable_enable_completion_date(){
	com=document.getElementById('corrective_actions_complete').value;
	//console.log(com);
	if(parseInt(com)==1)
	document.getElementById('corrective_actions_completion_date').removeAttribute("disabled");
	else
	document.getElementById('corrective_actions_completion_date').setAttribute("disabled","disabled");
}

function updateCountStatus(){
	var tca=parseInt(document.getElementsByClassName('corrective_action_checkbox').length);
	document.getElementById('total_corrective_actions').innerHTML="("+tca+")";
	document.getElementById('submitCorrectiveAction').style.display="block";
	document.getElementById('editCorrectiveAction').style.display="none";

}

function addPresentDate(){
	com=document.getElementById('corrective_actions_complete').value;
  var d = new Date();
  var month=parseInt(d.getMonth())+1;
  today=d.getFullYear()+"-"+('0' + month).slice(-2)+"-"+('0' + d.getDate()).slice(-2);
	//console.log(com);
	if(parseInt(com)==1){
	document.getElementById('corrective_actions_completion_date').removeAttribute("disabled");
  document.getElementById('corrective_actions_completion_date').value=today;
  }
	else{
	document.getElementById('corrective_actions_completion_date').setAttribute("disabled","disabled");
  document.getElementById('corrective_actions_completion_date').value="";
  }
}
