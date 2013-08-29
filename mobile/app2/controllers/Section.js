/**
 * @class Media
 * @extends Ext.Controller
 * The Media controller
 */
  
Ext.regController("Section", {
	fullscreen:true,
    index: function() { 
		this.detailPanel = this.render({
			xtype: 'section-detail'
		});
		this.application.viewport.setActiveItem(this.detailPanel); 
    }   
});  


