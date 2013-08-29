/**
 * @class Media
 * @extends Ext.Controller
 * The Media controller
 */
  
Ext.regController("Media", {
	historyUrl: 'Media/index',
    index: function() {
		if(!this.listPanel){
			this.listPanel = this.render({
				xtype: 'listpanel',
				setModel: 'Media',//custom var 
				//cardSwitchAnimation:'slide',
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
			//determins if the previous active view was detail page if it wasn't it will not animate on tab change
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


