RightNow.namespace('Custom.Widgets.customer_feedback.ComplaintReportFilter');
Custom.Widgets.customer_feedback.ComplaintReportFilter = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
     
          YUI().use('event', function (Y){
            
              var complaint_filter = Y.one('#complaint_filter');
              complaint_filter.on("change", function (e) {       
              document.complaint_filer_form.submit();
              });        
          }); 
 
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});