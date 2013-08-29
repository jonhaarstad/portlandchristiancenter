/**
 * @class Media.views.ListPanel
 * @extends Ext.Panel
 * The panel containing our  list.
 */   

MonkMobile.views.ListPanel = Ext.extend(Ext.Panel,{
	layout:'fit',
	cardSwtichAnimation:false,
	scroll:true,
	initComponent: function(){ 
		
		this.store = new Ext.data.Store({
		  	autoLoad: true,
			model: this.setModel  //from controller
		});
		  
		this.dockedItems = [{
			xtype: 'toolbar',
			dock:'top',
			title: MonkMobile.viewport.dockedItems.map.tabBar.activeTab.text
		}];
		
		this.list = new Ext.List({
			itemTpl: MonkMobile.viewport.dockedItems.map.tabBar.activeTab.viewtpl.list , //data for template declared in setup.js
			store: this.store,
			grouped:false,
			indexBar:false
		}); 
		
		this.items = [this.list];
		
		MonkMobile.views.ListPanel.superclass.initComponent.apply(this,arguments);
		//this.enableBubble('selectionchange');  
		
	}
});

Ext.reg('listpanel', MonkMobile.views.ListPanel);