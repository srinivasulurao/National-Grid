RightNow.namespace('Custom.Widgets.action_items.ProductLineFilter');
Custom.Widgets.action_items.ProductLineFilter = RightNow.Widgets.extend({
    /**
     * Widget constructor.
     */
    constructor: function() {

      YUI().use('event', function (Y) {
          plf=Y.one("#complaint_productline_filter");
          plf.on("change", function (e) {
          document.productline_filter_form.submit();
      });
      });

    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});
