RightNow.namespace('Custom.Widgets.customer_feedback.DeliveryProductSelection');
Custom.Widgets.customer_feedback.DeliveryProductSelection = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
                           YUI().use('event', function (Y) {
           if(parseInt(document.getElementsByClassName('ygtvitem').length) > 0 ){
           var category_choosen= Y.one(".ygtvitem");          
           category_choosen.on("click", function (e) {
               document.getElementById('delivery_line_item_list').innerHTML='';
               document.getElementById('delivery_line_item_list').style.display='none';
               delivery_id=document.getElementsByClassName('delivery_lookup')[0].value;
               incident_id=document.getElementById('form_incident_id').value;
               RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/deliveryLineItemList",{delivery_id:delivery_id,incident_id:incident_id}, {
                    successHandler: function (response) {
                        
                        if(response.responseText){
                        document.getElementById('delivery_line_item_list').innerHTML=response.responseText;
                        document.getElementById('delivery_line_item_list').style.display='block';
                        }
                        else{
                        document.getElementById('delivery_line_item_list').innerHTML='';
                        document.getElementById('delivery_line_item_list').style.display='none';
                        }

                            },
                    scope: this,
                    json: false,
                    type: "POST"
               });
             
            }); 
              }
             var IncidentDeliveryItem=Y.one(".rn_DeliveryProductSelection");
             IncidentDeliveryItem.on('click',function(e){
             cd=document.getElementsByClassName('incident_delivery_item');
              g=0; 
              product_selected=new Array(); 
               for(i=0;i<cd.length;i++){
                  dm=document.getElementsByClassName('incident_delivery_item')[i];
                  if(dm.checked)
                  product_selected[g++]=dm.value;
               }
              //alert(product_selected.join(','));
              document.getElementsByName('Incident.CustomFields.c.delivery_line_items')[0].value=product_selected.join(',');
             });
            

          }); //Yahoo Events here.


          //if there is incident_id & delivery_id, then prepopulate.
          if(document.getElementById('form_incident_id').value && document.getElementsByClassName('delivery_lookup')[0].value){
          delivery_id=document.getElementsByClassName('delivery_lookup')[0].value;
          incident_id=document.getElementById('form_incident_id').value;
          RightNow.Ajax.makeRequest("/cc/customerFeedbackSystem/deliveryLineItemList",{delivery_id:delivery_id,incident_id:incident_id}, {
                    successHandler: function (response) {
                        
                        if(response.responseText){
                        document.getElementById('delivery_line_item_list').innerHTML=response.responseText;
                        document.getElementById('delivery_line_item_list').style.display='block';
                        }
                        else{
                        document.getElementById('delivery_line_item_list').innerHTML='';
                        document.getElementById('delivery_line_item_list').style.display='none';
                        }

                            },
                    scope: this,
                    json: false,
                    type: "POST"
               });
           } 
         //Prepopulate thing ends here ..
           
    },

    /**
     * Sample widget method.
     */
    methodName: function() {
       
    }
});