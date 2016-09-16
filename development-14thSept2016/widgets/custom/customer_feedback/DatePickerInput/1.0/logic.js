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


        var yahoo_yui=YUI();
         yahoo_yui.use('calendar', 'datatype-date', 'cssbutton', function (G) {
            minimum_date=document.getElementById('minimum_date_yui').value;

            calendar = new G.Calendar({
            contentBox: "#yahoo-calendar",
            width:'340px',
            showPrevMonth: false,
            showNextMonth: true,
            minimumDate: new Date(minimum_date),
            date:new Date(minimum_date.split(",").join("/"))
           }).render();

             var dtdate = G.DataType.Date;
             calendar.on("selectionChange", function (ev) {
             var newDate = ev.newSelection[0];
             G.one(".yahoo_date_selected").set('value',dtdate.format(newDate));
             G.one("#yahoo-calendar").toggleView();
          });

          G.one("#toggleCalendar").on('click', function (ev) {
            if(document.getElementById('yahoo-calendar').style.display=="none"){
              document.getElementById('yahoo-calendar').style.display="block";
            }
            else {
              document.getElementById('yahoo-calendar').style.display="none";
            }
            calendar.set('showPrevMonth', !(calendar.get("showPrevMonth")));
          });

      }); // G use ends here.

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
