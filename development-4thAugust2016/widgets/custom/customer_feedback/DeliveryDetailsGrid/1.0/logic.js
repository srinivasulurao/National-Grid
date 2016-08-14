RightNow.namespace('Custom.Widgets.customer_feedback.DeliveryDetailsGrid');
Custom.Widgets.customer_feedback.DeliveryDetailsGrid = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
          var result_html="Loading, Please Wait ...";
          document.getElementsByClassName('delivery_details_obj_play')[0].innerHTML = result_html;
          YUI().use('event', function (Y) {
           
           var ddo = Y.all('#delivery_detail_obj,#refresh_delivery_obj');
           var close=Y.one("#close_delivery_obj");

           close.on("click", function(e){
                document.getElementById('delivery_details_obj_grid').style.display = 'none';
                document.getElementsByClassName('delivery_details_obj_play')[0].innerHTML = "";
           });

           ddo.on("click", function (e) {
           var del_id=document.getElementsByClassName('delivery_lookup')[0].value;

           document.getElementsByClassName('delivery_details_obj_play')[0].innerHTML = result_html;
           if(e.currentTarget.get('id')=="delivery_detail_obj"){ 
             if (document.getElementById("delivery_details_obj_grid").style.display == "block") 
    	       document.getElementById('delivery_details_obj_grid').style.display = 'none';
    	     else
    	       document.getElementById('delivery_details_obj_grid').style.display = 'block';
           }
         
           
           
           if(del_id){

            RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/getDeliveryDetails",{delivery_no:del_id}, {
                    successHandler: function (response) {                      
                        if(response.responseText){
                        result_html=response.responseText;
                        document.getElementsByClassName('delivery_details_obj_play')[0].innerHTML = result_html;
                        }
                       
                            },
                    scope: this,
                    json: false,
                    type: "POST"
                  });

           }
           else{
           result_html="<font color='red' size='2'>Please Enter Delivery Id to get the details !</font>";
           document.getElementsByClassName('delivery_details_obj_play')[0].innerHTML = result_html;
           }
           
           
          });
      });// YUI Event handler ends here..      
           
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});