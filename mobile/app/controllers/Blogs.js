/**
 * @class Articles
 * @extends Ext.Controller
 * The Articles controller
 */
  
Ext.regController("Blogs", {
	historyUrl: 'Blogs/index',
    index: function() {
		if(!this.listPanel){
			this.listPanel = this.render({
				xtype: 'listpanel',
				setModel: 'Blogs', //custom var
				listeners: {
					list: {
						select: this.show,
						scope: this
					},
					activate: function(listPanel){
						listPanel.list.getSelectionModel().deselectAll();
					}
				}
			}); 
			
			this.application.viewport.setActiveItem(this.listPanel);
		}else{
            var ai = this.application.viewport.getActiveItem();
			if(ai.xtype === "listdetail-panel"){
	            this.application.viewport.setActiveItem(this.listPanel, {
	                type: 'slide',
	                direction: 'right'
	            }); 
			}else{
	            this.application.viewport.setActiveItem(this.listPanel);
			} 
		}
    },
    show: function(list,record) { 
         var details = this.render({
            xtype: 'listdetail-panel',
            data: record.data,
			cardSwitchAnimation:'slide',
            listeners: {
                deactivate: function(details) { 
                    details.destroy();
                }
            }
        });
      
        details.query('#backButton')[0].on({
            tap: this.index,
            scope: this
        });
 	   this.application.viewport.setActiveItem(details, 'slide');   
    }   
});  


