RightNow.namespace('Custom.Widgets.customer_feedback.FormFunctionalityProvider');
Custom.Widgets.customer_feedback.FormFunctionalityProvider = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
            var prod_sample_ret=document.getElementById('prod_sample_ret');
            var prod_taken=document.getElementsByName('Incident.CustomFields.c.product_sample_taken')[0];
            var prod_not_taken=document.getElementsByName('Incident.CustomFields.c.product_sample_taken')[1];
           
            prod_taken.onclick=stb;
            prod_not_taken.onclick=htb;


            //#######################################################################################
            //###########################Don't Show Delivery Lookup in the edit page ################
            //#######################################################################################
            var inc_id=document.getElementById('form_incident_id').value;
             document.getElementsByName('Incident.CustomFields.c.sold_to_customer_name')[0].disabled="true";
            if(inc_id){
                document.getElementsByClassName("delivery_lookup")[0].disabled="true";  // Disable delivery no field.
                document.getElementsByClassName("delivery_lookup")[0].style.width="85%";
                document.getElementById('ddgb').style.display="none";
                
                
                document.getElementsByName('Incident.CustomFields.c.request_type')[0].disabled="true"; //Disable Request Type.
               
            }
            
            //Preload The product related fields for edit page.
            if(prod_taken.checked==true){
            stb();
            }
            
            
            YUI().use('event', function (Y) {

                // vardom=Y.one("#use_your_id_here");
                // vardom.on("click",function(e){
                // alert("hello");
                // });
                
           
            });//Event Ends here

    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});


function htb(){
document.getElementById('prod_sample_ret').style.display="none";
}

function stb(){
document.getElementById('prod_sample_ret').style.display="block";
}

