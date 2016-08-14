RightNow.namespace('Custom.Widgets.action_items.customDatePickerr');
Custom.Widgets.action_items.customDatePickerr = RightNow.Field.extend({ 
    /**
     * Widget constructor.
     */
 overrides: {
        constructor: function(data, instanceID) {
		
		    this.parent();
			this.data = data;
			this.instanceID = instanceID;

			this._inputSelector = this.baseSelector + "_" + this.data.attrs.name.replace(/\./g, "\\.");
			this._inputField = document.getElementById("rn_" + this.instanceID + "_" + this.data.attrs.name);
            this.input = this.Y.one(this._inputSelector);
			var str = this.data.attrs.name;
			str = str.replace(".", "_");
			this.res = str.replace(".", "_");
			
			 this.div_id = "#rn_" + this.instanceID + "_" + this.res;
		
		this._eo = new RightNow.Event.EventObject(this);
		this.mycalendar = this.Y.one("#rightcolumn");
	    this.mycalendar.hide();

		YUI().use('calendar', 'datatype-date', 'cssbutton', 'event-outside', function(Y) {
 
			// Create a new instance of calendar, placing it in 
			// #mycalendar container, setting its width to 340px,
			// the flags for showing previous and next month's 
			// dates in available empty cells to true, and setting 
			// the date to today's date.          
			var calendar = new Y.Calendar({
		    contentBox: "#mycalendar",
			minimumDate: new Date(),
			width:'255px',
			showPrevMonth: true,
			showNextMonth: false,
			minimumDate: new Date(document.getElementById('minimum_date').value)}).render();
			
			
			
			// Get a reference to Y.DataType.Date
			var dtdate = Y.DataType.Date;
		
			// Listen to calendar's selectionChange event.
			calendar.on("selectionChange", function (ev) {
	
		console.log(ev.newSelection);
		
		var value = new Date(ev.newSelection);
		var new_date=new Date(value);
		var curr_date = new_date.getDate();
        var curr_month = new_date.getMonth() + 1; //Months are zero based
        var curr_year = new_date.getFullYear();
		var date = (curr_year + "-" + curr_month + "-" + curr_date );
		
			 var div_id=document.getElementById('hid_date_due').value;
			 var div_id_std=document.getElementById('hid_date_due_std').value;
			 Y.one(div_id).set('value',dtdate.format(date));
				this.mycalendar = Y.one("#rightcolumn");
				this.mycalendar.hide();
								
			});
		
					
		Y.one('#demo').on('clickoutside', function (e){
			
			 this.mycalendar = Y.one("#rightcolumn");
			 this.mycalendar.hide();
			 
				
			});
		
		});
		
	
	this._ondateChange(this.div_id);	
	this.calendarIcon = this.Y.one("#calendar_icon");
	this.calendarIcon.on('click', this._ShowCalendar,this);
	
	this.calendarIcon = this.Y.one(this.div_id);
	this.calendarIcon.on('click', this._ShowCalendar,this);
				
	var form = RightNow.Form.find(this.baseDomID, this.instanceID);
	form.on("submit", this.onValidate, this);
	//this.parentForm().on("submit", this.onValidate, this)
	
	
}
 },


	
    _getSelected: function()
    {
		
        return this._selectBox.get('options').item(this._selectBox.get('selectedIndex')).get('value');
    },
   
	
	/**
			 * Show calendar widget method.
			 */
			_ShowCalendar: function() {
				
				
				this.mycalendar.replaceClass('yui3-u','yui3-custom');	
				this.mycalendar.show();
				
				
			},
			_ondateChange: function(div_id){
				
				
				var v = this.Y.one(div_id).get('value');
		
							
				},
			onValidate: function(type, args) 
			{		
			
				var form = RightNow.Form.find(this.baseDomID, this.instanceID);
				
				var eo = new RightNow.Event.EventObject(this, {data: {
				"name" : this.data.js.name,			
				"table" : this.data.js.table,
				"value":this._getValue(),
				"form": form._parentForm
				}});
			
				
				if(eo.data.value=='')
				{
				
			errorLocation= args[0].data.error_location;
				document.getElementById("rn_duedate").className = "rn_Label rn_ErrorLabel";
			var commonErrorDiv = this.Y.one("#" + errorLocation),
            verifyField;
			var div_id=document.getElementById('hid_date_due_std').value;
			document.getElementById(div_id).className = "rn_Text rn_ErrorField";
			message="Please enter the Due Date";
			errorString="<div><b><a href='javascript:void(0);' onclick='document.getElementById(\"" + div_id +
                    "\").focus(); return false;'>" + message + "</a></b></div> ";
			commonErrorDiv.append(errorString);
            RightNow.Event.fire("evt_formFieldValidateFailure", eo);
            return false;
					
				}else{
				RightNow.Event.fire("evt_formFieldValidationPass", eo);
				return eo;
				}
				
			},
		
			_getValue: function()
			{
				var div_id=document.getElementById('hid_date_due').value;
				
				return this.Y.one(div_id).get('value');
		
			},
	

    /**
     * Sample widget method.
     */
    methodName: function() {
		

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
        // Handle response
    },
	/**
			 * Renders the `view.ejs` JavaScript template.
			 */
			renderView: function() {
			
				// JS view:
				var content = new EJS({text: this.getStatic().templates.view}).render({
					// Variables to pass to the view
					// display: this.data.attrs.display
				});
			}
	
	
});