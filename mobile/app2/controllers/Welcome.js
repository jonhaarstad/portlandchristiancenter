/**
 * @class Welcome
 * @extends Ext.Controller
 * The Welcome controller
 */
  
Ext.regController("Welcome", {
	fullscreen:true,
    index: function() {
	   	if(!this.detailPanel){
		this.detailPanel = this.render({
			xtype: 'welcome-panel'
		});
			this.application.viewport.setActiveItem(this.detailPanel); 
		}else{
		   this.application.viewport.setActiveItem(this.detailPanel);  
		}
    }   
});  


