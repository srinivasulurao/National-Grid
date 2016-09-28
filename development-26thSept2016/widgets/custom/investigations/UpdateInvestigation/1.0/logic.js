RightNow.namespace('Custom.Widgets.investigations.UpdateInvestigation');
Custom.Widgets.investigations.UpdateInvestigation = RightNow.Widgets.extend({
    /**
     * Widget constructor.
     */
    constructor: function() {
     	YUI().use('transition','event','panel', function(Y) {


			         //Show, Hide Form
			          	var add_thread = Y.one('#add_thread');
			            add_thread.on("click", function (e) {
			            	Y.one('#thread_submit').toggleView();
			            	add_button_text=document.getElementById('add_thread').innerText;
                    for(ii=0;ii<document.getElementsByName("Contact.Emails.PRIMARY.Address").length;ii++)
                    document.getElementsByName("Contact.Emails.PRIMARY.Address")[ii].value="";
			            	if(add_button_text=="+ Add"){
			            	add_thread.setContent("- Hide");
			            	}
			            	else{
			            	add_thread.setContent("+ Add");
			            	}

			            });


			 //Contact Lookup Search.
			      var cl = Y.all('#thread_submit .rn_Email');
			      cl.on("keyup", function (e) {
			      	            contact_lookup=document.getElementsByName("Contact.Emails.PRIMARY.Address")[0].value;
			      	            if(parseInt(contact_lookup.length) > 3){
						      	RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/contactLookUpSearch",{input:contact_lookup}, {
			                    successHandler: function (response) {

			                        if(response.responseText!="")
			                            document.getElementById('contact_look_up').innerHTML=response.responseText
			                        else
			                            document.getElementById('contact_look_up').innerHTML='';

			                    },
			                    scope: this,
			                    json: false,
			                    type: "POST"
			                    });
			                   }
			      });


			      //Hide the contact Lookup.
			      var cl = Y.all('.rn_FormSubmit');
			      cl.on("hover", function (e) {
			      Y.one("#contact_look_up").setContent("");
			      });


			});


			//Subscribe to response Event()
			var form = RightNow.Form.find("thread_submit", this.instanceID);
	        form.on("response", this.callThreads, this);
    },

    /**
     * Sample widget method.
     */
    callThreads: function(type,args) {
    	YUI().use('transition','event','panel', function(Y) {
    		document.getElementsByName('Incident.Threads')[0].value="";
    		document.getElementsByName('Contact.Emails.PRIMARY.Address')[0].value="";
        if(document.querySelectorAll(".rn_FileAttachmentUpload ul").length)
    		Y.one(".rn_FileAttachmentUpload ul").setContent("");
    		Y.one('#thread_submit').toggleView();
    		Y.one('#add_thread').setContent("+ Add");
    	});
    },

    /**
     * Makes an AJAX request for `default_ajax_endpoint`.
     */
    getDefault_ajax_endpoint: function() {
        // Make AJAX request:
        var eventObj = new RightNow.Event.EventObject(this, {data:{
            w_id: this.data.info.w_id,
            // Parameters to send
        }});
        RightNow.Ajax.makeRequest(this.data.attrs.default_ajax_endpoint, eventObj.data, {
            successHandler: this.default_ajax_endpointCallback,
            scope:          this,
            data:           eventObj,
            json:           true
        });
    },

    /**
     * Handles the AJAX response for `default_ajax_endpoint`.
     * @param {object} response JSON-parsed response from the server
     * @param {object} originalEventObj `eventObj` from #getDefault_ajax_endpoint
     */
    default_ajax_endpointCallback: function(response, originalEventObj) {
       alert("Hello");
    }
});


function setContact(id,email){
	document.getElementById("rn_TextInput_36_Contact.Emails.PRIMARY.Address").value=email;
	document.getElementById('contact_look_up').innerHTML="";
}
