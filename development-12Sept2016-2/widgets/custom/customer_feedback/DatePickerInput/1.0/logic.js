RightNow.namespace('Custom.Widgets.customer_feedback.DatePickerInput');
Custom.Widgets.customer_feedback.DatePickerInput= RightNow.Field.extend({
   overrides: {
   /**
   * Widget constructor.
   */
    constructor: function() {

        var yds_required=document.getElementsByClassName('yahoo_date_selected')[0].required;
        if(yds_required==true){
        var form = RightNow.Form.find(this.baseDomID, this.instanceID);
	      form.on("submit", this.onValidate, this);
        }
    },

     /**
     * Event handler executed when form is being submitted
     * Note: This function was adapted from the standard/input/TextInput widget and SiteInfo widget.
     *
     * @param type String Event name
     * @param args Object Event arguments
     */
    onValidate: function(type, args) {

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
	     document.getElementById(this.instanceID+"_Label").className = "rn_Label rn_ErrorLabel";
             document.getElementsByClassName("yahoo_date_selected")[0].className="rn_Text yahoo_date_selected rn_ErrorField";
	     var commonErrorDiv = this.Y.one("#" + errorLocation);
	     errorString="<b><a href='javascript:void(0)' onclick=\"document.getElementsByClassName('yahoo_date_selected')[0].focus(); return false;\">Please Enter the Target Date</a></b><br>";
	     commonErrorDiv.append(errorString);
             RightNow.Event.fire("evt_formFieldValidateFailure", eo);
              return false;

	}else{
        document.getElementById(this.instanceID+"_Label").className = "rn_Label";
        document.getElementsByClassName("yahoo_date_selected")[0].className="rn_Text yahoo_date_selected";
	RightNow.Event.fire("evt_formFieldValidationPass", eo);
	return eo;
	}

    },
   _getValue: function()
			{

          var div_id=document.getElementsByClassName("yahoo_date_selected")[0].value;

				//return this.Y.one(div_id).get('value');
                                return div_id;

			},

   /*** This function displays an error in the error div used by the ask a question form validation
    **Note: This function was adapted from the standard/input/TextInput widget and SiteInfo widget.
    * @param type Array errors -
    * @param args Object Error Location -
    */
     _displayError: function(errors, errorLocation) {

     }
}
});
