 /**
 * @class MonkMobile.views.SectionDetail
 * @extends Ext.Panel
 * A Panel showing the details of a section.
 */
MonkMobile.views.SectionDetail = Ext.extend(Ext.Panel, {
	baseCls:"detailpage",
	styleHtmlContent: true,
	scroll:'vertical',
	layout:'fit', 
	cardSwitchAnimation:false,
    initComponent : function() { 
    
        this.dockedItems = {
            dock: 'top',
            xtype: 'toolbar',
            title:MonkMobile.viewport.dockedItems.map.tabBar.activeTab.text            
        };
		var params = MonkMobile.viewport.dockedItems.map.tabBar.activeTab.viewtpl.find;
	    Ext.Ajax.request({
			url:'/mobile/lib/mk-modules/sections.php?id='+params,
			success: function(rs){
				this.update(rs.responseText, "<script>document.write('hi section detail');</script>");
				this.doLayout();
			},
			scope:this
		});

        MonkMobile.views.SectionDetail.superclass.initComponent.apply(this);
    }
});

Ext.reg('section-detail', MonkMobile.views.SectionDetail);