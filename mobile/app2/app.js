/**
 * This file sets application-wide settings and launches the application when everything has
 * been loaded onto the page. By default we just render the applications Viewport inside the
 * launch method (see app/views/Viewport.js).
 */  
Ext.TabBarEasyScroll = Ext.extend(Ext.TabBar, {   
    initEvents : function() {
            if (this.sortable) {
                this.sortable = new Ext.util.Sortable(this.el, {
                    itemSelector: '.x-tab',
                    direction: 'horizontal',
                    delay: this.sortHoldThreshold,
                    constrain: true
                });
                this.mon(this.sortable, 'sortchange', this.onSortChange, this);
            }
    
            this.mon(this.el, {
                tap: this.onTap,
                scope: this
            });
   
            Ext.TabBar.superclass.initEvents.call(this);
        },
    
    onTap: function(e, t) {
            t = e.getTarget('.x-tab');
            if (t) {
                this.onTabTap(Ext.getCmp(t.id));
            }
    }
});


Ext.regApplication('MonkMobile',{
    defaultTarget: "viewport",
    defaultUrl : 'Welcome/index',
    name: "MonkMobile",
    useHistory : true,
    launch: function() {
        this.viewport = new MonkMobile.Viewport({
	    	application:this
		});
    }
});
