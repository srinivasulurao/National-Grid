RightNow.namespace('Custom.Widgets.investigations.InvestigationClosure');
Custom.Widgets.investigations.InvestigationClosure = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
    	
    	 var form = RightNow.Form.find("investigation_closure_form", this.instanceID);
	     form.on("submit", this.onValidate, this);
	     
         document.getElementsByName('Incident.CustomFields.c.was_there_a_problem')[0].className="problem_detect1";
         document.getElementsByName('Incident.CustomFields.c.was_there_a_problem')[1].className="problem_detect2";
         
         
         YUI().use('transition','event', function (Y) {

            var problem_detect1 = Y.one('.problem_detect1');
            problem_detect1.on("click", function (e) {            	
            Y.one("#why_fields").show();            	
            });
                     
            var problem_detect2 = Y.one('.problem_detect2');
            problem_detect2.on("click", function (e) {          	
            Y.one("#why_fields").hide();   	
            });
            
            
            //Pre Load Incase the data is saved.	
	          if(document.getElementsByClassName('problem_detect1')[0].checked==true)
	     	      Y.one("#why_fields").show();
	     	  else
	     	      Y.one("#why_fields").hide();
	           
	          });   
          
          
          
         
         
         
    },
    

    /**
     * Sample widget method.
     */
    onValidate: function(type,args) {
    	   	
    	var form = RightNow.Form.find("investigation_closure_form", this.instanceID);
				
				var eo = new RightNow.Event.EventObject(this, {data: {
				"name" : this.data.js.name,			
				"table" : this.data.js.table,
				"value":this._getValue(),
				"form": form._parentForm
				}});
				
            if(eo.data.value==1)
				{   
               var why1=document.getElementsByName('Incident.CustomFields.c.why1')[0].value;
               var why2=document.getElementsByName('Incident.CustomFields.c.why2')[0].value;
               var why3=document.getElementsByName('Incident.CustomFields.c.why3')[0].value;
               
               if(why1=="" || why2=="" || why3==""){
               	
		               	     if(why1==""){
		               	     document.getElementsByName("Incident.CustomFields.c.why1")[0].style.background="#fefda0";
		               	     document.querySelector("#why1 label").style.color = "#c31c24";
		                     }
		                     else{
		                     document.querySelector("#why1 label").style.color = "#333";
		                     document.getElementsByName("Incident.CustomFields.c.why1")[0].style.background="white";
		                     }
		                     
		                     if(why2==""){
		               	     document.querySelector("#why2 label").style.color = "#c31c24";
		               	     document.getElementsByName("Incident.CustomFields.c.why2")[0].style.background="#fefda0";
		                     }
		                     else{
		                     document.querySelector("#why2 label").style.color = "#333";
		                     document.getElementsByName("Incident.CustomFields.c.why2")[0].style.background="white";
		                     }
		                     
		                     if(why3==""){
		               	     document.querySelector("#why3 label").style.color = "#c31c24";
		               	     document.getElementsByName("Incident.CustomFields.c.why3")[0].style.background="#fefda0";
		                     }
		                     else{
		                     document.querySelector("#why3 label").style.color = "#333";
		                     document.getElementsByName("Incident.CustomFields.c.why3")[0].style.background="white";
		                     }
                     
                     var commonErrorDiv = this.Y.one("#" + "rn_err_validation");
				     errorString="<b><a href='javascript:void(0)' onclick=\"document.getElementsByClassName('yahoo_date_selected')[0].focus(); return false;\">Please provide atleast three reasons (Why1, Why2, Why3)!</a></b><br>";
				     commonErrorDiv.append(errorString);
                     RightNow.Event.fire("evt_formFieldValidateFailure", eo);
                     return false;
               }
               else{  
               	
                     document.querySelector("#why1 label").style.color = "#333";
                     document.getElementsByName("Incident.CustomFields.c.why1")[0].style.background="white";
                     
                     document.querySelector("#why2 label").style.color = "#333";
                     document.getElementsByName("Incident.CustomFields.c.why2")[0].style.background="white";
                     
                     document.querySelector("#why3 label").style.color = "#333";
                     document.getElementsByName("Incident.CustomFields.c.why3")[0].style.background="white";	
		                     
               	     RightNow.Event.fire("evt_formFieldValidationPass", eo);
               	 
               	return true;
               }
               
            }
            return true;
     },
     _getValue:function(type,args){
     	if(document.getElementsByClassName('problem_detect1')[0].checked==true)
     	return 1;
     	else
     	return 0;
     	
     }         
	
});