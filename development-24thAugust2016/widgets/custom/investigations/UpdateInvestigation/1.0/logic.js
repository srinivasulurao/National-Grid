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
			            	add_button_text=document.getElementById('add_thread').innerHTML;
			            	if(add_button_text=="+ Add"){
			            	Y.one('#thread_submit').show(true);
			            	add_thread.setContent("- Hide");
			            	}
			            	else{
			            	Y.one('#thread_submit').hide(true);
			            	add_thread.setContent("+ Add");
			            	}
			            	
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
    	//console.log(args);
    	//console.log(args.data.results.transaction.incident.value);
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
});	//console.log(args.data.results.transaction.incident.value);
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
