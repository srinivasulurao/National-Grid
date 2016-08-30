RightNow.namespace('Custom.Widgets.customer_feedback.DeliveryProductSelection');
Custom.Widgets.customer_feedback.DeliveryProductSelection = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
    	var target_instance_id="";
                           YUI().use('event','node-event-delegate', function (Y) {
          if(parseInt(document.getElementsByClassName('prod_cat_sel').length) > 0 ){
             var category_choosen= Y.all(".prod_cat_sel .rn_DisplayButton");          
             category_choosen.on("click", function (e) {
           	 document.getElementsByClassName('delivery_line_item_list')[0].style.display="none"; 
           	 document.getElementById('target_instance_id').value=category_choosen.get('id');
           	 
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
              showHideProductFields();
             });
             
            //Body Clicker Starts Here            
                document.getElementsByClassName('prod_cat_sel')[0].onclick=function(event){
            	//explode_length1=event.target.outerHTML.split('aria-level="2"').length;
            	//explode_length2=event.target.outerHTML.split('aria-level="3"').length;
            	tid=document.getElementById('target_instance_id').value;
            	tid_plus=tid.split('Category_Button').join("Button")+'_Visible_Text';
            	
            	select_cat=document.getElementById(tid_plus).innerHTML;
            	//alert(select_cat);
            	product_explode=select_cat.split('Product').length;
            	
            	if(parseInt(product_explode)==2){
            		
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
               
            	} //Body Clicker Ends Here
            	
            }

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
                        document.getElementById('product_related_fields').style.display="block";
                        }
                        else{
                        document.getElementById('delivery_line_item_list').innerHTML='';
                        document.getElementById('delivery_line_item_list').style.display='none';
                        document.getElementById('product_related_fields').style.display="none";
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

function showHideProductFields(){
var product_having_issue=document.getElementsByName('Incident.CustomFields.c.delivery_line_items')[0].value;
//alert(product_having_issue);
if(product_having_issue)	
document.getElementById('product_related_fields').style.display="block";
else
document.getElementById('product_related_fields').style.display="none";
}
