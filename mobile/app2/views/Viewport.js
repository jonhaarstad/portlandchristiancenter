/**
 * @class MonkMobile.Viewport
 * @extends Ext.Panel
 * This is a default generated class which would usually be used to initialize your application's
 * main viewport. By default this is simply a welcome screen that tells you that the app was 
 * generated correctly.
 */        

MonkMobile.Viewport = Ext.extend(Ext.TabPanel, {
    id : 'viewport',
    layout:'card',
    tabBarDock:'bottom',
    cardSwitchAnimation:false,
	scroll:false,
    fullscreen:true,
	initComponent: function(){ 
	  Ext.apply(this,{
		dockedItems: [ 
			new Ext.TabBarEasyScroll({
				id:'tabBar',
		        dock : 'top',
		        ui   : 'dark',
		        layout: { pack: 'center'},
		        cardSwitchAnimation: false,
				scroll: {
		            direction: 'horizontal',
		            useIndicators: false
		        },
	   			//activeItem: 0,
		        items: MonkMobile.setup.tabs, //defined in setup.js
				listeners: {
			        afterrender: function() {
			            this.on('change', function(tabBar, tab, card){ 
			                Ext.dispatch({
			                    controller: tab.controller,
			                    action: 'index'
			                });
			            }, this);
			        }
			    }
		    })
		]
		});
	   	Ext.override(Ext.TabBarEasyScroll, {
		    // @private 
		    onTabTap: function(tab) {
		        this.activeTab = tab;
		        this.items.each(function(item) {
		            item.deactivate();
		        });
		        tab.activate();
		        this.fireEvent('change', this, tab);
		    }/*,
		    onRender: Ext.util.Functions.createSequence(Ext.TabBar.prototype.onRender, function() {
		        if (this.activeItem != null) {
		            this.items.getAt(this.activeItem).activate();
		        }  
		    })*/
		}); 
		MonkMobile.Viewport.superclass.initComponent.apply(this,arguments);  
	}
    
});

 /*Ext.Dispatcher.on('dispatch', function(interaction) {
            // If on a normal tab, select the tab button  
			//console.log(interaction.controller.id + " | " + MonkMobile.controllers.ABOUT_CONTROLLER_ID);
            if(interaction.controller && interaction.controller.id != MonkMobile.controllers.ABOUT_CONTROLLER_ID) {
                this.tabBar.setSelected(interaction.controller.id);
            } 
}, this);*/


