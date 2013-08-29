/**
 * @class Galleries
 * @extends Ext.Controller
 * The Galleries controller
 */ 
Ext.regController("Galleries", {
	historyUrl: 'Gallery/index',
	griddata: '',
    index: function() {
		if(!this.listPanel){
			this.listPanel = this.render({
				xtype: 'listpanel',
				setModel: 'Galleries', 
				listeners: {
					list: {
						select: this.grid,
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
			if(ai.xtype === "photogrid-panel"){
	            this.application.viewport.setActiveItem(this.listPanel, {
	                type: 'slide',
	                direction: 'right'
	            });
    			
			}else{
	            this.application.viewport.setActiveItem(this.listPanel);
			} 
		}
    },
    grid: function(list,record) {
		     var dta = record.data ? record.data : this.griddata;
		     var ai = this.application.viewport.getActiveItem();
	         this.details = this.render({
	            xtype: 'photogrid-panel',
	            data: dta,
				cardSwitchAnimation:{ type:"slide", direction:"left"},
				listeners:{
					deactivate:function(details){
						details.destroy();
					} 
				}
	        });
		
			this.griddata = dta; //sava data to controller variable griddata for repopulate of data.
		
	        this.details.query('#backButton')[0].on({
	            tap: this.index,
	            scope: this
	        });
	           
	        this.application.viewport.setActiveItem(this.details, {type:"slide",direction:"right"});	
    },
    detail:function(record){
		 var el = Ext.get(record.data.item);
	   	 var detail = this.render({
            xtype: 'photo-panel',
            data: {id:el.getAttribute("data-gallery"),idx:record.data.index},
			cardSwitchAnimation:'slide',
            listeners: {
                deactivate: function(detail) { 
                    detail.destroy();
                }
            }
        });
      
        detail.query('#backButton')[0].on({
            tap: this.grid,
            scope: this
        });
 	   this.application.viewport.setActiveItem(detail, 'slide'); 
	}
});  


