RightNow.namespace('Custom.Widgets.action_items.StatusFilter');
Custom.Widgets.action_items.StatusFilter = RightNow.Widgets.extend({
    /**
     * Widget constructor.
     */
    constructor: function() {

          YUI().use('event', function (Y) {
              sf=Y.one("#complaint_status_filter");
              sf.on("change", function (e) {
              document.status_filter_form.submit();
          });

        });
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});
