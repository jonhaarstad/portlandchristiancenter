/**
 * @class Photos.
 * @extends Ext.Panel
 * A Panel showing the grid of photos.
 */
MonkMobile.views.PhotoGridPanel = Ext.extend(Ext.Panel, {
    cardSwtichAnimation:'slide',
	styleHtmlContent: false,
	layout:'fit',
	scroll:true,
	cls:"photogrid-panel",
    initComponent : function() {
		var p = this,
			slug = this.data.slug;
        p.dockedItems = {
            dock: 'top',
            xtype: 'toolbar',
			title: MonkMobile.viewport.dockedItems.map.tabBar.activeTab.text,
            items: {
                text: 'Back',
                ui: 'back',
                itemId: 'backButton'
            }            
        };
	   	p.store = new Ext.data.Store({
			model: "Photos",
			autoLoad:true,
			proxy:{
					type:'ajax',
					url:'/mobile/lib/mk-modules/photos.php?callback=?&galleryid='+slug,
					reader:{
						type:'json',
						root:'items'
					}
				}
		});
		var tpl = new Ext.XTemplate( 
		    '<div id="grid-wrap"><div id="grid">',
			'<tpl for=".">',
			'<div class="thumbwrap main-image" data-gallery="'+slug+'"><img src="{thumb}" width="100"/></div>',
			'</tpl>',
			'</div></div>' 
		); 
	    p.items = new Ext.DataView({
			store:p.store,
			tpl: tpl,
			autoHeight:true,
			multiSelect:false,
			overItemClass:'x-view-over',
			itemSelector:'div.thumbwrap',
			listeners:{
				itemtap:function(view,index,item,e){ 
					//console.log(index);
					Ext.dispatch({
						controller:'Galleries',
						action:'detail',
						data: {view:view,index:index,item:item}
					});
				}
			}
		});
		
		
        MonkMobile.views.PhotoGridPanel.superclass.initComponent.apply(this, arguments);
    }
});

Ext.reg('photogrid-panel', MonkMobile.views.PhotoGridPanel);