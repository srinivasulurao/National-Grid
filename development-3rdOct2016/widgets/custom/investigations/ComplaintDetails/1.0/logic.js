RightNow.namespace('Custom.Widgets.investigations.ComplaintDetails');
Custom.Widgets.investigations.ComplaintDetails = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {

    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});

function showTabContent(val){
		document.getElementById('tab1').className="tabs";
		document.getElementById('tab2').className="tabs";
		document.getElementById('tab3').className="tabs";
		document.getElementById('tab4').className="tabs";
		if(val==2){
			document.getElementById('complaint_content').style.display="none";
			document.getElementById('customer_content').style.display="block";
			document.getElementById('delivery_content').style.display="none";
			document.getElementById('investigation_content').style.display="none";
			document.getElementById('tab2').className="tabs tab-active";
		}
		else if(val==3){
			document.getElementById('complaint_content').style.display="none";
			document.getElementById('customer_content').style.display="none";
			document.getElementById('delivery_content').style.display="block";
			document.getElementById('investigation_content').style.display="none";
			document.getElementById('tab3').className="tabs tab-active";
		}
		else if(val==4){
			document.getElementById('complaint_content').style.display="none";
			document.getElementById('customer_content').style.display="none";
			document.getElementById('delivery_content').style.display="none";
			document.getElementById('investigation_content').style.display="block";
			document.getElementById('tab4').className="tabs tab-active";
			
		}
		else{
			document.getElementById('complaint_content').style.display="block";
			document.getElementById('customer_content').style.display="none";
			document.getElementById('delivery_content').style.display="none";
			document.getElementById('investigation_content').style.display="none";
			document.getElementById('tab1').className="tabs tab-active";
		}
	}
	
function playToggle(x){
	YUI().use('transition','event','panel', function(Y) {
			            	add_button_text=document.getElementById('plus_minus'+x).innerHTML;
			            	if(add_button_text=="+"){
			            	Y.one('.investigation-playstation-'+x).show(true);
			            	document.getElementById('plus_minus'+x).innerHTML="-";
			            	}
			            	else{
			            	Y.one('.investigation-playstation-'+x).hide(true);
			            	document.getElementById('plus_minus'+x).innerHTML="+";
			            	}
     });
}
