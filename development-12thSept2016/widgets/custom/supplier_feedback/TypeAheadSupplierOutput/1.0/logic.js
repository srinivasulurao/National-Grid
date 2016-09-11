 /* Originating Release: May 2013 */
RightNow.namespace('Custom.Widgets.supplier_feedback.TypeAheadSupplierLookup');
Custom.Widgets.supplier_feedback.TypeAheadSupplierOutput = RightNow.Field.extend({
    overrides: {
        constructor: function(data, instanceID) {
            this.parent();
			this.data = data;
			this.instanceID = instanceID;

			this._inputSelector = this.baseSelector + "_" + this.data.attrs.name.replace(/\./g, "\\.");
			this._inputField = document.getElementById("rn_" + this.instanceID + "_" + this.data.attrs.name);
            this.input = this.Y.one(this._inputSelector);
			
            if(!this.input) return;

            if(this.data.attrs.hint && !this.data.attrs.hide_hint && !this.data.attrs.always_show_hint)
                this._initializeHint();

            if(this.data.attrs.initial_focus && this.input.focus)
                this.input.focus();

            //setup mask
            if (this.data.js.mask) {
                this._initializeMask();
            }
			//var FieldName = this.data.js.name;
			//var attrs = this.data.attrs.name;

            //province changing: update phone/postal masks - note: field may get mask from country selection
            if(RightNow.Text.beginsWith(this._fieldName, "Contact.Phones.")
                || this._fieldName === "Contact.Address.PostalCode") {
                RightNow.Event.on("evt_provinceResponse", this.onProvinceChange, this);
            }
            //check for existing username/email
            if(this.data.attrs.validate_on_blur) {
                this.input.on("blur", this._blurValidate, this);
                //Add blur validation to Validate Field
                if (this.data.attrs.require_validation) {
                    this.Y.Event.attach("blur", this._blurValidate, this._inputSelector + '_Validate', this, true);
                }
            }
            this._isFormSubmitting = false;

            this.parentForm().on("submit", this.onValidate, this)
                             .on("send", this._toggleFormSubmittingFlag, this)
                             .on("response", this._toggleFormSubmittingFlag, this);

			if(this.data.attrs.showna){
				this._field = this.Y.one("#rn_" + this.instanceID + "_nacheck");
				this._field.on("change",this._naChanged,this);
			}
			
			if(this._fieldName === "cf_other_desc" || this._fieldName === "cf_meds_desc" || this._fieldName === 			"cf_allergies_desc" || this._fieldName === "cf_condition_desc" || this._fieldName === "cf_attention_desc")
			{
			
         RightNow.Event.subscribe("evt_EvaluateRequired", this._eraseInput, this);
    		}

			if(this._fieldName === "cf_other"){ 
			    
				RightNow.Event.subscribe("evt_PCchosen", this._getOtherResponse, this);
				this.data.attrs.required = false; 
			}
						
  			RightNow.Event.subscribe("evt_FillValues", this._fillValues, this);
            RightNow.Event.on("evt_formValidateFailure", this._onValidateFailure, this);
			
        }
		
		
    },

    /**
     * Event handler executed when form is being submitted
     *
     * @param type String Event name
     * @param args Object Event arguments
     */
    onValidate: function(type, args) {
        var eventObject = this.createEventObject(),
            errors = [];

        this._toggleErrorIndicator(false);
		
		if(!this.validate(errors) || (this.data.attrs.require_validation && !this._validateVerifyField(null, errors)) || !this._compareInputToMask(true)) {
            this._displayError(errors, args[0].data.error_location);
            RightNow.Event.fire("evt_formFieldValidateFailure", eventObject);
            return false;
        }

        RightNow.Event.fire("evt_formFieldValidatePass", eventObject);
        return eventObject;
    },

    _displayError: function(errors, errorLocation) {
		document.getElementById("rn_supplier_Label").className = "rn_Label rn_ErrorLabel";
        var commonErrorDiv = this.Y.one("#" + errorLocation),
            verifyField;
        if(commonErrorDiv) {
            for(var i = 0, errorString = "", message, id = this.input.get("id"); i < errors.length; i++) {
                message = errors[i];
                if (typeof message === "object" && message !== null && message.id && message.message) {
                    id = verifyField = message.id;
                    message = message.message;
                }
                else {
                    message = (message.indexOf("%s") > -1) ? RightNow.Text.sprintf(message, this.data.attrs.label_input) : this.data.attrs.label_input + " " + message;
                }
               document.getElementById("contact_name").className = "rn_Text rn_ErrorField";
				id="contact_name";
			   errorString += "<div><b><a href='javascript:void(0);' onclick='document.getElementById(\"" + id +
                    "\").focus(); return false;'>" + message + "</a></b></div> ";
            }
            commonErrorDiv.append(errorString);
        }
        if (!verifyField || errors.length > 1) {
            this._toggleErrorIndicator(true);
        }
    },

    /**
     * This function highlights the form field where the error was found
     *
     * @param showOrHide Boolean Should the highlight be shown
     * @param fieldToHighlight Node of the field to highlight
     * @param labelToHighlight Node of the label to highlight
     */
    _toggleErrorIndicator: function(showOrHide, fieldToHighlight, labelToHighlight) {
        var method = ((showOrHide) ? "addClass" : "removeClass");
        if (fieldToHighlight && labelToHighlight) {
            fieldToHighlight[method]("rn_ErrorField");
            labelToHighlight[method]("rn_ErrorLabel");
        }
        else {
            //this.input[method]("rn_ErrorField");
            //this.label = this.label || this.Y.one(this.baseSelector + "_Label");
            //this.label[method]("rn_ErrorLabel");
        }
    },

    /**
     * Keep track of what state the form is in. We need to know if it is being submitted
     * so that we don't show any alert dialogs for onBlur errors.
     * @param {String} event Name of event being fired, either 'send' or 'response'
     */
    _toggleFormSubmittingFlag: function(event){
        this._isFormSubmitting = (event === 'send');
    },

    /**
    * Validates that the input field has a value (if required) and that the value is
    * of the correct format.
    * @param {Object} event Blur event
    * @param {Boolean=} validateVerifyField Whether to validate the input field or
    *   its verify field; defaults to verifying the input field (false)
    */
    _blurValidate: function(event, validateVerifyField) {
        if (this._dialogShowing)
            return;

        this._trimField();

        if (validateVerifyField) {
            this._validateVerifyField(event.target);
        }
        else {
            var valid = this.validate();
            if (valid) {
                if(this._fieldName === "Contact.Login" || this.isCommonEmailType()) {
                    this._checkExistingAccount();
                }
            }
            this._toggleErrorIndicator(!valid);
        }
    },

    /**
     * Validates the field's "verify" field, whose value must match the field value.
     * @param {Object} verifyField The verify field Node
     * @param {Array=} Array of error messages to populate; optional
     */
    _validateVerifyField: function(verifyField, errors) {
        verifyField = verifyField || this.Y.one(this._inputSelector + '_Validate');
        errors = errors || [];

        var valid = true,
            verifyLabel = this.Y.one(this._inputSelector + '_LabelValidate');

        if(verifyField && this.data.attrs.require_validation) {
            var verifyValue = this.Y.Lang.trim(verifyField.get("value")),
                label = RightNow.Text.sprintf(this.data.attrs.label_validation, this.data.attrs.label_error || this.data.attrs.label_input);

            if (this.data.attrs.required && verifyValue === "") {
                errors.push({message: RightNow.Text.sprintf(this.data.attrs.label_required, label), id: verifyField.get("id")});
                valid = false;
            }
            else if (verifyValue !== this.Y.Lang.trim(this.input.get('value'))) {
                errors.push({message: RightNow.Text.sprintf(this.data.attrs.label_validation_incorrect, label, this.data.attrs.label_input), id: verifyField.get("id")});
                valid = false;
            }
        }
        if (verifyField) {
            this._toggleErrorIndicator(!valid, verifyField, verifyLabel);
        }
        return valid;
    },

    /**
     * --------------------------------------------------------
     * Business Rules Events and Functions:
     * --------------------------------------------------------
     */

    /**
     * Event handler for when email or login field blurs
     * Check to see if the username/email is unique
     */
    _checkExistingAccount: function() {
        var massagedNewValue = this._massageValueForModificationCheck(this._value);
        if (this._value === "" || massagedNewValue === this._seenValue || (this.data.js.previousValue && this._value === this.data.js.previousValue))
            return;

        this._seenValue = massagedNewValue;

        var eventObject = new RightNow.Event.EventObject(this, {data: {contactToken: this.data.js.contactToken}});

        if (this.isCommonEmailType())
            eventObject.data.email = this._value;
        else if (this._fieldName === "Contact.Login")
            eventObject.data.login = this._value;

        if (RightNow.Event.fire("evt_accountExistsRequest", eventObject)) {
            RightNow.Ajax.makeRequest(this.data.attrs.existing_contact_check_ajax, eventObject.data, {
               successHandler: this._onAccountExistsResponse,
               scope: this,
               data: eventObject,
               json: true
            });
        }
    },

    /**
     * Sometimes we need to have more complex rules to determine if two field values are the same.
     * @param value String string To massage.
     * @returns String Massaged value.
     */
    _massageValueForModificationCheck: function(value) {
        if (this.Y.Lang.isUndefined(value) || value === null || value === "") {
            return "";
        }
        if (this.isCommonEmailType()) {
            value = value.toLowerCase();
        }
        return value.replace('&#039;',"'");
    },

    /**
     * If the response has a message and we aren't in the process of submitting
     * then alert the message; otherwise no duplicate account exists.
     * @param Object|Boolean response Server response to request
     * @param Object originalEventObject Event arguments
     */
    _onAccountExistsResponse: function(response, originalEventObject) {
        if (RightNow.Event.fire("evt_accountExistsResponse", response, originalEventObject)) {
            if(response !== false && this._isFormSubmitting === false) {
                this._toggleErrorIndicator(true);
                //create action dialog with link to acct assistance page
                var warnDialog,
                    handleOK = function() {
                        warnDialog.hide();
                        this._dialogShowing = false;
                        this.input.focus();
                    };

                warnDialog = RightNow.UI.Dialog.messageDialog(response.message, {icon : "WARN", exitCallback : {fn: handleOK, scope: this}});
                this._dialogShowing = true;
                warnDialog.show();
            }
        }
    },

    /**
     * --------------------------------------------------------
     * Mask Functions
     * --------------------------------------------------------
     */

    /**
     * Event handler for when province/state data is returned from the server
     *
     * @param type String Event name
     * @param args Object Event arguments
     */
    onProvinceChange: function(type, args)
    {
        var eventObj = args[0],
            resetMask = false;

        if(!eventObj.ProvincesLength)
            this.data.js.mask = "";

        if(this._fieldName === "Contact.Address.PostalCode" && "PostalMask" in eventObj)
        {
            resetMask = true;
            this.data.js.mask = eventObj.PostalMask;
        }
        else if("PhoneMask" in eventObj)
        {
            resetMask = true;
            this.data.js.mask = eventObj.PhoneMask;
        }

        if(this._maskNodeOnPage)
            this._maskNodeOnPage.remove();

        if(resetMask && this.data.js.mask)
            this._initializeMask();
    },

    /**
    * Creates a mask overlay
    */
     _initializeMask: function()
     {
        this.input.on("keyup", this._showHint, this);
        this.input.on("blur", this._hideMaskMessage, this);
        this.input.on("focus", this._compareInputToMask, this);
        this.data.js.mask = this._createMaskArray(this.data.js.mask);
        //Set up mask overlay
        var overlay = this.Y.Node.create("<div class='rn_MaskOverlay'>");
        if(this.Y.Overlay) {
            this._maskNode = new this.Y.Overlay({
                bodyContent: overlay,
                visible: false,
                align: {
                    node: this.input,
                    points: [this.Y.WidgetPositionAlign.TL, this.Y.WidgetPositionAlign.BL]
                }
            });
            this._maskNode.render(this.baseSelector);
        }
        else {
            this._maskNode = overlay.addClass("rn_Hidden");
            this.input.insert(this._maskNode, "after");
        }

        if(this.data.attrs.always_show_mask) {
            //Write mask onto the page
            var maskMessageOnPage = this._getSimpleMaskString(),
                widgetContainer = this.Y.one(this.baseSelector);
            if(maskMessageOnPage && widgetContainer) {
                var messageNode = this.Y.Node.create("<div class='rn_Mask'>" + RightNow.Interface.getMessage("EXPECTED_INPUT_LBL") + ": " + maskMessageOnPage + "</div>");
                if (widgetContainer.get("lastChild").hasClass("rn_HintText")) {
                    messageNode.addClass("rn_MaskBuffer");
                }
                this._maskNodeOnPage = messageNode;
                widgetContainer.append(messageNode);
            }
        }
     },

    /**
     * Creates a mask array based on the passed-in
     * string mask value.
     * @param mask String The new mask to apply to the field
     * @return Array the newly created mask array
     */
    _createMaskArray: function(mask)
    {
        if(!mask) return;
        var maskArray = [];
        for(var i = 0, j = 0, size = mask.length / 2; i < size; i++)
        {
            maskArray[i] = mask.substring(j, j + 2);
            j += 2;
        }
        return maskArray;
    },

     /**
     * Builds up simple mask string example based off of mask characters
     */
    _getSimpleMaskString: function() {
        if (!this.data.js.mask) return "";

        var maskString = "";
        for(var i = 0; i < this.data.js.mask.length; i++) {
            switch(this.data.js.mask[i].charAt(0)) {
                case "F":
                    maskString += this.data.js.mask[i].charAt(1);
                    break;
                case "U":
                    switch(this.data.js.mask[i].charAt(1)) {
                        case "#":
                            maskString += "#";
                            break;
                        case "A":
                        case "C":
                            maskString += "@";
                            break;
                        case "L":
                            maskString += "A";
                            break;
                    }
                    break;
                case "L":
                    switch(this.data.js.mask[i].charAt(1)) {
                        case "#":
                            maskString += "#";
                            break;
                        case "A":
                        case "C":
                            maskString += "@";
                            break;
                        case "L":
                            maskString += "a";
                            break;
                    }
                    break;
                case "M":
                    switch(this.data.js.mask[i].charAt(1)) {
                        case "#":
                            maskString += "#";
                            break;
                        case "A":
                        case "C":
                        case "L":
                            maskString += "@";
                            break;
                    }
                    break;
            }
        }
        return maskString;
    },

	 _showHint: function(submitting) {
		 
		 
		 alert(submitting);
		 
		 
	 },
    /**
     * Compares entered value to required mask format
     * @param submitting Boolean Whether the form is submitting or not;
     * don't display the mask message if the form is submitting.
     * @return Boolean denoting of value coforms to mask
     */
    _compareInputToMask: function(submitting) {
        if (!this.data.js.mask) return true;

        var error = [],
            value = this.input.get("value");
        if (value.length > 0) {
            for (var i = 0, tempRegExVal; i < value.length; i++) {
                if(i < this.data.js.mask.length) {
                    tempRegExVal = "";
                    switch(this.data.js.mask[i].charAt(0)) {
                        case 'F':
                            if(value.charAt(i) !== this.data.js.mask[i].charAt(1))
                                error.push([i,this.data.js.mask[i]]);
                            break;
                        case 'U':
                            switch(this.data.js.mask[i].charAt(1)) {
                                case '#':
                                    tempRegExVal = /^[0-9]+$/;
                                    break;
                                case 'A':
                                    tempRegExVal = /^[0-9A-Z]+$/;
                                    break;
                                case 'L':
                                    tempRegExVal = /^[A-Z]+$/;
                                    break;
                                case 'C':
                                    tempRegExVal = /^[^a-z]+$/;
                                    break;
                            }
                            break;
                        case 'L':
                            switch(this.data.js.mask[i].charAt(1)) {
                                case '#':
                                    tempRegExVal = /^[0-9]+$/;
                                    break;
                                case 'A':
                                    tempRegExVal = /^[0-9a-z]+$/;
                                    break;
                                case 'L':
                                    tempRegExVal = /^[a-z]+$/;
                                    break;
                                case 'C':
                                    tempRegExVal = /^[^A-Z]+$/;
                                    break;
                            }
                            break;
                        case 'M':
                            switch(this.data.js.mask[i].charAt(1)) {
                                case '#':
                                    tempRegExVal = /^[0-9]+$/;
                                    break;
                                case 'A':
                                    tempRegExVal = /^[0-9a-zA-Z]+$/;
                                    break;
                                case 'L':
                                    tempRegExVal = /^[a-zA-Z]+$/;
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            break;
                    }
                    if((tempRegExVal !== "") && !(tempRegExVal.test(value.charAt(i))))
                        error.push([i,this.data.js.mask[i]]);
                }
                else
                {
                    error.push([i,"LEN"]);
                }
            }
            //input matched mask but length didn't match up
            if((!error.length) && (value.length < this.data.js.mask.length) && (!this.data.attrs.always_show_mask || submitting === true))
            {
                for(i = value.length; i < this.data.js.mask.length; i++)
                    error.push([i,"MISS"]);
            }
            if(error.length > 0)
            {
                //input didn't match mask
                this._showMaskMessage(error);
                if(submitting === true)
                    this._reportError(RightNow.Interface.getMessage("PCT_S_DIDNT_MATCH_EXPECTED_INPUT_LBL"));
                return false;
            }
            //no mask errors
            this._showMaskMessage(null);
            return true;
        }
        //haven't entered anything yet...
        if(!this.data.attrs.always_show_mask && submitting !== true)
            this._showMaskMessage(error);
        return true;
    },

    /**
     * Actually shows the error message to the user
     * @param error Array Collection of details about error to display
     */
    _showMaskMessage: function(error) {
        if(error === null) {
            this._hideMaskMessage();
        }
        else {
            if(!this._showMaskMessage._maskMessages) {
                //set a static variable containing error messages so it's lazily defined once across widget instances
                this._showMaskMessage._maskMessages = {
                    "F" : RightNow.Interface.getMessage('WAITING_FOR_CHARACTER_LBL'),
                    "U#" : RightNow.Interface.getMessage('PLEASE_TYPE_A_NUMBER_MSG'),
                    "L#" : RightNow.Interface.getMessage('PLEASE_TYPE_A_NUMBER_MSG'),
                    "M#" : RightNow.Interface.getMessage('PLEASE_TYPE_A_NUMBER_MSG'),
                    "UA" : RightNow.Interface.getMessage('PLEASE_ENTER_UPPERCASE_LETTER_MSG'),
                    "UL" : RightNow.Interface.getMessage('PLEASE_ENTER_AN_UPPERCASE_LETTER_MSG'),
                    "UC" : RightNow.Interface.getMessage('PLS_ENTER_UPPERCASE_LETTER_SPECIAL_MSG'),
                    "LA" : RightNow.Interface.getMessage('PLEASE_ENTER_LOWERCASE_LETTER_MSG'),
                    "LL" : RightNow.Interface.getMessage('PLEASE_ENTER_A_LOWERCASE_LETTER_MSG'),
                    "LC" : RightNow.Interface.getMessage('PLS_ENTER_LOWERCASE_LETTER_SPECIAL_MSG'),
                    "MA" : RightNow.Interface.getMessage('PLEASE_ENTER_A_LETTER_OR_A_NUMBER_MSG'),
                    "ML" : RightNow.Interface.getMessage('PLEASE_ENTER_A_LETTER_MSG'),
                    "MC" : RightNow.Interface.getMessage('PLEASE_ENTER_LETTER_SPECIAL_CHAR_MSG'),
                    "LEN" : RightNow.Interface.getMessage('THE_INPUT_IS_TOO_LONG_MSG'),
                    "MISS" : RightNow.Interface.getMessage('THE_INPUT_IS_TOO_SHORT_MSG')
                };
            }
            var message = "",
                sampleMaskString = this._getSimpleMaskString().split("");
            for(var i = 0, type; i < error.length; i++) {
                type = error[i][1];
                //F means format char should have followed
                if(type.charAt(0) === "F") {
                    message += "<b>" + RightNow.Interface.getMessage('CHARACTER_LBL') + " " + (error[i][0] + 1) + "</b> " + RightNow.Interface.getMessage('WAITING_FOR_CHARACTER_LBL') + type.charAt(1) + " ' <br>";
                    sampleMaskString[(error[i][0])] = "<span style='color:#F00;'>" + sampleMaskString[(error[i][0])] + "</span>";
                }
                else {
                    if(type !== "MISS") {
                        message += "<b>" + RightNow.Interface.getMessage('CHARACTER_LBL') + " " + (error[i][0] + 1) + "</b> " + this._showMaskMessage._maskMessages[type] + "<br>";
                        if(type !== "LEN") {
                            sampleMaskString[(error[i][0])] = "<span style='color:#F00;'>" + sampleMaskString[(error[i][0])] + "</span>";
                        }
                        else {
                            break;
                        }
                    }
                }
            }
            sampleMaskString = sampleMaskString.join("");
            this._setMaskMessage(RightNow.Interface.getMessage('EXPECTED_INPUT_LBL') + ": "  + sampleMaskString + "<br>" + message);
            this._showMask();
        }
    },

    /**
    * Sets mask message.
    * @param message String message to set
    */
    _setMaskMessage: function(message)
    {
        var overlayContent = this._maskNode.get('bodyContent');
        if(overlayContent){
            overlayContent.set('innerHTML', message);
        }
        else{
            this._maskNode.set('innerHTML', message);
        }
    },

    /**
    * Shows mask message.
    */
    _showMask: function()
    {
        if(this.Y.Overlay)
            this._maskNode.show();
        else
            RightNow.UI.show(this._maskNode);
    },

    /**
     * Hides mask message.
     */
    _hideMaskMessage: function()
    {
        if(this.Y.Overlay && this._maskNode.get("visible") !== false)
            this._maskNode.hide();
        else
            RightNow.UI.hide(this._maskNode);
    },

    /**
     * Reposition mask overlay, as field's Y changes when error div is displayed
     */
    _onValidateFailure: function()
    {
        // Make sure field has a mask that is visible
        if (this.data.js.mask && this._maskNode.align && this.Y.Overlay && this._maskNode.get("visible") !== false) {
            this._maskNode.align();
        }
    },
		_onIntlChecked: function(type, args){
        
        var Label = document.getElementById("rn_" + this.instanceID + "_Label");
        
        if (args[0].data.checked){
            this.data.attrs.required = false;
            Label.innerHTML = Label.innerHTML.replace('<span class="rn_Required"> *</span>','');
        }else{
            this.data.attrs.required = true;
            Label.innerHTML = Label.innerHTML.concat('<span class="rn_Required"> *</span>');
        }
    },
	
	
	_naChanged:function(){
        
      this._checkbox = document.getElementById("rn_" + this.instanceID + "_nacheck");
      
      if (this._checkbox.checked){
          this.data.attrs.required = false;
          this._inputField.disabled = true;
          this._inputField.value = "N/A";
          this._inputField.style.backgroundColor = "lightgray";
          
      }else{
          this.data.attrs.required = true; 
          this._inputField.disabled = false;
          this._inputField.style.backgroundColor = "white";
          this._inputField.value = "";
      }
      
    },
	
	//other field needs to get value taken out if hidden
    _getOtherResponse: function(data, obj)
    {
        if (obj[0].data.prod.search("Other - Please Specify") > 0)
        { 
            this.data.attrs.required = true; 
        }else{
            this.data.attrs.required = false; 
            this._inputField.value = "";
        }
        
    },
    
    //if these fields are hidden we need to take their value out.
    _eraseInput: function(vars, args){
		
        if (args[0].data.req != "yes"){
			
            this._inputField.value = "";
        }
        
    },
	
	
	_fillValues:function(type,args){
        if (this._fieldName == "cf_sku"){
            if (args[0].data.type)
                this._inputField.value = args[0].data.type.substring(0,this.data.attrs.max_length);
            else
                this._inputField.value = "";
        }else if(this._fieldName == "cf_type" ){
            if (args[0].data.sku)
                this._inputField.value = args[0].data.sku.substring(0,this.data.attrs.max_length);
            else
                this._inputField.value = "";
        }
        
    }
});
