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
            	add_button_text=document.getElementById('add_tca').innerHTML;
            	if(add_button_text=="+ Add"){
            	Y.one('#add_corrective_action').show(true);
            	add_tca.setContent("- Hide");
            	}
            	else{
            	Y.one('#add_corrective_action').hide(true);
            	add_tca.setContent("+ Add");
            	}
            	
            });
            
            var dialog = new Y.Panel({
        contentBox : Y.Node.create('<div id="dialog" />'),
        bodyContent: '<div class="message icon-warn">Corrective Action Added Successfully</div>',
        width      : 410,
        zIndex     : 6,
        centered   : true,
        modal      : false, // modal behavior
        render     : '.example',
        visible    : false, // make visible explicitly with .show()
        buttons    : {
            footer: [
                {
                    name  : 'cancel',
                    label : 'Cancel',
                    action: 'onCancel'
                },

                {
                    name     : 'proceed',
                    label    : 'OK',
                    action   : 'onOK'
                }
            ]
        }
    });
    
            //Submit Corrective Action.
            
            sca=Y.one("#add_corrective_action");
            sca.on("submit",function (e){
            	
            	var detail=document.getElementById('corrective_actions_details').value;
            	var description=document.getElementById('corrective_actions_description').value;
            	var complete=document.getElementById('corrective_actions_complete').value;
            	var due_date=document.getElementById('corrective_actions_due_date').value;
            	var completion_date=document.getElementById('corrective_actions_completion_date').value;
            	var i_id=document.getElementById('corrective_actions_iid').value;
            	//dialog.show();
            	
            	queryString="detail="+detail+"&description="+description+"&complete="+complete+"&due_date="+due_date+"&completion_date="+completion_date+"&i_id="+i_id;
            	
            	var xhttp=new XMLHttpRequest();
                xhttp.open("GET", "/cc/customerFeedbackSystem/addCorrectiveAction/?"+queryString, false);
                xhttp.send();
                document.getElementById('correctiveActionList').innerHTML=xhttp.responseText;
            	
            	document.getElementById('corrective_actions_details').value="";
            	document.getElementById('corrective_actions_description').value="";
            	document.getElementById('corrective_actions_complete').value="";
            	document.getElementById('corrective_actions_due_date').value="";
            	document.getElementById('corrective_actions_completion_date').value="";
            	
            	
            	Y.one('#add_corrective_action').hide(true);
            	add_tca.setContent("+ Add");
            	updateCountStatus();
            	
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
            	
		            	RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/deleteCorrectiveActions",{input:total_deletes.join('|')},{
		                    successHandler: function (response) {
		
		                        if(response.responseText!=""){
		                           document.getElementById('correctiveActionList').innerHTML=response.responseText;
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
		            	RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/changeCorrectiveActionStatus",{input:total_sca.join('|'),status:1},{
		                    successHandler: function (response) {
		
		                        if(response.responseText!=""){
		                           document.getElementById('correctiveActionList').innerHTML=response.responseText;
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
		            	RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/changeCorrectiveActionStatus",{input:total_sca.join('|'),status:0},{
		                    successHandler: function (response) {
		
		                        if(response.responseText!=""){
		                           document.getElementById('correctiveActionList').innerHTML=response.responseText;
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
            
            
         
            
            
          }); //Events ends here.
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

function updateCountStatus(){
	var tca=parseInt(document.getElementsByClassName('corrective_action_checkbox').length);
	document.getElementById('total_corrective_actions').innerHTML="("+tca+")";
}
