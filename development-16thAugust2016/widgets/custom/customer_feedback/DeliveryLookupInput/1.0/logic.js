RightNow.namespace('Custom.Widgets.customer_feedback.DeliveryLookupInput');
Custom.Widgets.customer_feedback.DeliveryLookupInput = RightNow.Widgets.extend({
    /**
     * Widget constructor.
     */
    constructor: function() {

        var reqd=document.getElementsByClassName('delivery_lookup')[0].required;
        if(reqd==true){
            var form = RightNow.Form.find(this.baseDomID, this.instanceID);
            form.on("submit", this.onValidate, this);
        }
        //#######################################################################################
        //################################Delivery Lookup Starts here############################
        //#######################################################################################
        YUI().use('event', function (Y) {

            var searching_for = Y.one('.delivery_lookup');
            searching_for.on("keyup", function (e) {
                document.getElementById('autocomplete_delivery').innerHTML='';
                search_text=document.getElementsByClassName('delivery_lookup')[0].value;
                RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/deliveryLookupSearch",{search_term:search_text}, {
                    successHandler: function (response) {

                        if(response.responseText!=""){
                            var html="<div class='deliverableList'><ul>";
                            // response get here
                            var arr =JSON.parse(response.responseText.toString());
                            //Start the iteration.
                            for (var i = 0; i < arr.length; i++){
                                var obj = arr[i];
                                dd=obj.delivery;
                                sold=obj.sold_to_customer;
                                //ship=obj.ship_to_customer;
                                //prod=obj.prod_no;
                                html+="<li><a href='javascript:void(0)'  onclick=\"setDelivery('"+dd+"','"+sold+"')\">"+obj.delivery+"</a></li>";
                            }
                            html+="</ul></div>";
                        }
                        if(arr.length)
                            document.getElementById('autocomplete_delivery').innerHTML=html;
                        else
                            document.getElementById('autocomplete_delivery').innerHTML='';

                    },
                    scope: this,
                    json: false,
                    type: "POST"
                });
            });
        });

        //#######################################################################################
        //################################Advanced Delivery Lookup Start here####################
        //#######################################################################################

        YUI().use('event', function (Y) {
            reset_html='<label>Delivery #</label><input type="text" id="delivery_no" ><label>Customer PO Number</label><input type="text" id="customer_po_no"><label>Sold to Customer Name</label><input type="text" id="sold_to_customer"><label>Ship to Customer Name</label><input type="text" id="ship_to_customer">';
            var ddgb = Y.one("#ddgb");
            ddgb.on("click", function (e) {

                if (document.getElementById("deliveryDetailsGrid").style.display == "block")
                    document.getElementById('deliveryDetailsGrid').style.display = 'none';
                else
                    document.getElementById('deliveryDetailsGrid').style.display = 'block';

                document.getElementById("DeliverySearchPlay").innerHTML=reset_html;
                document.getElementById('search_delivery').style.display="inline-block";

            });

            var reset=Y.one('#search_delivery_reset');
            reset.on("click",function(e){
                document.getElementById("DeliverySearchPlay").innerHTML=reset_html;
                document.getElementById('search_delivery').style.display="inline-block";
            });

            var close_del=Y.one("#close_delivery_lookup_butt");
            close_del.on("click",function(e){
                document.getElementById('deliveryDetailsGrid').style.display = 'none';
            });


            var search=Y.one('#search_delivery');
            search.on("click",function(e){
                var delivery_no=document.getElementById('delivery_no').value;
                var customer_po_no=document.getElementById('customer_po_no').value;
                var sold_to_customer=document.getElementById('sold_to_customer').value;
                var ship_to_customer=document.getElementById('ship_to_customer').value;

                RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/deliveryDetailsLookup",{delivery_no:delivery_no,customer_po_no:customer_po_no,sold_to_customer:sold_to_customer,ship_to_customer:ship_to_customer}, {
                    successHandler: function (response) {
                        if(response.responseText){
                            document.getElementById('DeliverySearchPlay').innerHTML=response.responseText+"<br>";
                            document.getElementById("search_delivery").style.display="none";
                        }
                        else
                            document.getElementById('DeliverySearchPlay').innerHTML=reset_html;

                    },
                    scope: this,
                    json: false,
                    type: "POST"
                });


            });

            
        //#######################################################################################
        //################################Sold to customer Lookup Starts Here####################
        //#######################################################################################
             document.getElementsByName("Incident.CustomFields.c.sold_to_customer_name")[0].className="rn_Text sold_to_customer";
             
             var stc=Y.all('.sold_to_customer');
             stc.on("keyup",function(e){      
                sold_to_customer_input=document.getElementsByName('Incident.CustomFields.c.sold_to_customer_name')[0].value;         
                RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/soldToCustomerSuggest",{input:sold_to_customer_input}, {
                    successHandler: function (response) {
                        if(response.responseText){
                            document.getElementById('sold_to_customer_suggestions').innerHTML=response.responseText;
                        }
                        else
                            document.getElementById('sold_to_customer_suggestions').innerHTML="";

                    },
                    scope: this,
                    json: false,
                    type: "POST"
                });

            });

         document.getElementsByName("Incident.CustomFields.c.ship_to_customer_name")[0].className="rn_Text ship_to_customer";
             
             var stc=Y.all('.ship_to_customer');
             stc.on("keyup",function(e){      
                ship_to_customer_input=document.getElementsByName('Incident.CustomFields.c.ship_to_customer_name')[0].value;         
                RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/shipToCustomerSuggest",{input:ship_to_customer_input}, {
                    successHandler: function (response) {
                        if(response.responseText){
                            document.getElementById('ship_to_customer_suggestions').innerHTML=response.responseText;
                        }
                        else
                            document.getElementById('ship_to_customer_suggestions').innerHTML="";

                    },
                    scope: this,
                    json: false,
                    type: "POST"
                });

            });


         document.getElementsByName("Incident.CustomFields.c.product_no")[0].className="rn_Text product_no_to_customer";
             
             var stc=Y.all('.product_no_to_customer');
             stc.on("keyup",function(e){      
                product_no_to_customer_input=document.getElementsByName('Incident.CustomFields.c.product_no')[0].value;         
                RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/productNoToCustomerSuggest",{input:product_no_to_customer_input}, {
                    successHandler: function (response) {
                        if(response.responseText){
                            document.getElementById('product_number_suggestions').innerHTML=response.responseText;
                        }
                        else
                            document.getElementById('product_number_suggestions').innerHTML="";

                    },
                    scope: this,
                    json: false,
                    type: "POST"
                });

            });


        }); //Yahoo event handler ends here.


    },
    onValidate: function(type, args) {

        var form = RightNow.Form.find(this.baseDomID, this.instanceID);

        var eo = new RightNow.Event.EventObject(this, {data: {
            "name" : this.data.js.name,
            "table" : this.data.js.table,
            "value":this._getValue(),
            "form": form._parentForm
        }});

        var delivery_no_exists="invalid";

        var xhttp=new XMLHttpRequest();
        xhttp.open("GET", "/cc/customerFeedbackSystem/checkDeliveryNumberExist/?delivery_no="+document.getElementsByClassName('delivery_lookup')[0].value, false);
        xhttp.send();
        delivery_no_exists=(parseInt(xhttp.responseText))?"valid":"invalid";

        if(eo.data.value=='')
        {

            errorLocation= args[0].data.error_location;
            document.getElementById(this.instanceID+"_Label").className = "rn_Label rn_ErrorLabel";
            document.getElementsByClassName("delivery_lookup")[0].className="rn_Text delivery_lookup rn_ErrorField";
            var commonErrorDiv = this.Y.one("#" + errorLocation);
            errorString="<b><a href='javascript:void(0)' onclick=\"document.getElementsByClassName('delivery_lookup')[0].focus(); return false;\">Please Enter the Delivery detail</a></b><br>";
            commonErrorDiv.append(errorString);
            RightNow.Event.fire("evt_formFieldValidateFailure", eo);
            return false;

        }
        else if (delivery_no_exists =="invalid"){
            errorLocation= args[0].data.error_location;
            document.getElementById(this.instanceID+"_Label").className = "rn_Label rn_ErrorLabel";
            document.getElementsByClassName("delivery_lookup")[0].className="rn_Text delivery_lookup rn_ErrorField";
            var commonErrorDiv = this.Y.one("#" + errorLocation);
            errorString="<b><a href='javascript:void(0)' onclick=\"document.getElementsByClassName('delivery_lookup')[0].focus(); return false;\">Invalid Delivery Number !</a></b><br>";
            commonErrorDiv.append(errorString);
            RightNow.Event.fire("evt_formFieldValidateFailure", eo);
            return false;
        }
        else{
            document.getElementById(this.instanceID+"_Label").className = "rn_Label";
            document.getElementsByClassName("delivery_lookup")[0].className="rn_Text delivery_lookup";
            RightNow.Event.fire("evt_formFieldValidationPass", eo);
            return eo;
        }

    },
    _getValue: function()
    {

        var div_id=document.getElementsByClassName("delivery_lookup")[0].value;

        //return this.Y.one(div_id).get('value');
        return div_id;

    },

    /**
     * Sample widget method.
     */
    methodName: function(){


    }

});


function setDelivery(del,sold_to_customer) {
    document.getElementsByClassName('delivery_lookup')[0].value=del;
    document.getElementsByClassName('sold_to_customer')[0].value=sold_to_customer;
    document.getElementById('autocomplete_delivery').innerHTML='';
    document.getElementById('delivery_details_obj_grid').style.display="none";
}

function sleep(milliSeconds){
    var startTime = new Date().getTime(); // get the current time
    while (new Date().getTime() < startTime + milliSeconds); // hog cpu
}

function setSoldToCustomer(name){
dom_stc=document.getElementsByClassName('sold_to_customer')[0];
dom_stc.value=name;
document.getElementById('sold_to_customer_suggestions').innerHTML="";
}

function setShipToCustomer(name){
dom_stc=document.getElementsByClassName('ship_to_customer')[0];
dom_stc.value=name;
document.getElementById('ship_to_customer_suggestions').innerHTML="";
}


function setProductNoToCustomer(name){
dom_stc=document.getElementsByClassName('product_no_to_customer')[0];
dom_stc.value=name;
document.getElementById('product_number_suggestions').innerHTML="";
}


