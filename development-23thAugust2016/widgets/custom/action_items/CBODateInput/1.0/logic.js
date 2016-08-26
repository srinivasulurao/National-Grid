RightNow.namespace('Custom.Widgets.action_items.CBODateInput');

Custom.Widgets.action_items.CBODateInput = RightNow.Widgets.DateInput.extend({ 
   /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.TextInput#constructor.
         */
        constructor: function() {// Call into parent's constructor
		     this.parent();
					
			 var widget_id=this._inputSelector;
			 
			
	
			
				
				
				
			 YUI().use('node', function(Y) {	
				 Y.one('#payout').on('mousedown', function(){
														//alert("helloooo");
														
      		var date = new Date();
	
			var currentMonth = date.getMonth();
			var currentDate = date.getDate();
			var currentYear = date.getFullYear();
			
		$("#payout").datepicker({
	
	dateFormat:"mm-dd-yy",
      changeMonth: true,
      changeYear: true,
	  maxDate: new Date(currentYear+1, "", ""),
	//maxDate: new Date(currentYear, currentMonth, currentDate),
	onSelect: function(dateText, inst) { 
     alert('ad');
	 var res = dateText.split("-");
	
										
	Y.one(widget_id+'_Month').set("selectedIndex",parseInt(res[0]));
										alert(Y.one(widget_id+'_Month').get("value",parseInt(res[1])));
	Y.one(widget_id+'_Day').set("selectedIndex",parseInt(res[1]));
										alert(Y.one(widget_id+'_Day').get("value",parseInt(res[0])));
										
									for(var y=0;y< Y.one(widget_id+'_Month').get('options').size(); y++)
											{
																			
   												if(parseInt(res[0]) == Y.one(widget_id+'_Month').get('options').item(y).get('text'))
 																					
      											Y.one(widget_id+'_Month').set("selectedIndex",y);
											}
											
											
							for(var j=0;j< Y.one(widget_id+'_Day').get('options').size(); j++)
								{     
								//alert(res[1]);
								
												
   												if(parseInt(res[1]) == Y.one(widget_id+'_Day').get('options').item(j).get('text'))
 											
      											Y.one(widget_id+'_Day').set("selectedIndex",j);
											}
																			
										
										
										
										
										
										
										
										
									for(var x=0;x < Y.one(widget_id+'_Year').get('options').size(); x++)
										{
																				
											if(res[2] == Y.one(widget_id+'_Year').get('options').item(x).get('text'))

											Y.one(widget_id+'_Year').set("selectedIndex",x);
										}									
										
	   
	   
	   
    }
    });

													
													
													
													
													
													
													
													
												
				
					});
				
				});
				
				
			
				
	 YUI().use('node', function(Y) {	
				 Y.one('#payout').on('change', function(){
				
				if(Y.one('#payout').get("value")=="")
				{
					
				Y.one(widget_id+'_Month').set("selectedIndex",0);	
				Y.one(widget_id+'_Day').set("selectedIndex",0);
				Y.one(widget_id+'_Year').set("selectedIndex",0);
					
				}
				
			
					});
				
				});
				
			
	
	
			
        },
		
			
		toggleErrorIndicator: function(showOrHide, fieldsToHighlight) {
			
        var method = ((showOrHide) ? "addClass" : "removeClass");
		//alert(method);
        if (fieldsToHighlight) {
		
           this.input.each(function(field) {
                if (this.Array.indexOf(fieldsToHighlight, field.get("id")) > -1) {
                    field[method]("rn_ErrorField");
                }
            }, this.Y);
        }
        else {
			
            this.input[method]("rn_ErrorField");
					
			 YUI().use('node', function(Y) {
										 Y.one('#payout')[method]("rn_ErrorField");
										});
			
        }
		
        this.label = this.label || this.Y.one(this.baseSelector + "_Legend");
	
        this.label[method]("rn_ErrorLabel");
		 YUI().use('node', function(Y) {
									
		Y.one('#payout_lbl')[method]("rn_ErrorLabel");
									});
    }
	
		 },
		 
	
	issuedate_set_attribute: function(type, args) {
		
	
		var value = args[0].data;
		//alert(value);	
	
		if(value==196)//Commission Inquiry
			{
		
				//var labelnew = document.getElementById("rn_" + this.instanceID + "_" + "Incident.CustomFields.c.original_issue_date");
				var labelnew = "rn_" + this.instanceID + "_" + "Incident.CustomFields.c.original_issue_date";
				//alert(labelnew);
				labelnew.innerHTML="Original Issue Date"+"<span class='rn_Required'> *</span>";
				this.data.attrs.required=true;
			}
			
			else
			{
			this.input.set('value','');
			this.data.attrs.required=false;
				 YUI().use('node', function(Y) {	
				
				Y.one('#payout').set("value",'');
				
				});
			}



    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});