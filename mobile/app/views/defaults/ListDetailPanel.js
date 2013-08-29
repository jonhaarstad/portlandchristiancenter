 /**
 * @class Media.views.ContactDetails
 * @extends Ext.Panel
 * A Panel showing the details of a media.
 */
MonkMobile.views.ListDetailPanel = Ext.extend(Ext.Panel, {
    cardSwtichAnimation:'slide',
    //tpl:"<h2>{title}</h2><h4>{date}</h4><audio src='{audio}' controls /><p>{text}</p>",
	styleHtmlContent: true,
	layout:'fit',
	scroll:true,
    initComponent : function() {
		//console.log(MonkMobile.viewport.dockedItems.map.tabBar.activeTab.viewtpl.detail);
             
		this.tpl =  MonkMobile.viewport.dockedItems.map.tabBar.activeTab.viewtpl.detail
        this.dockedItems = {
            dock: 'top',
            xtype: 'toolbar',
			title: MonkMobile.viewport.dockedItems.map.tabBar.activeTab.text,
            items: {
                text: 'Back',
                ui: 'back',
                itemId: 'backButton'
            }            
        };
        this.listeners = {
           afterrender:function(){
               //var currentPanel = Ext.get(this.id);
               var audioElm = document.getElementsByTagName('audio');
               for(var i = 0; i < audioElm.length; i++){
                   var audioctr = new audioControls(audioElm[i]);
               }
            },
            beforehide:function(){
                var audioElm = document.getElementsByTagName('audio');
                for(var i = 0; i < audioElm.length; i++){
                    audioElm[i].pause();
                }
            }
        };
        
        MonkMobile.views.ListDetailPanel.superclass.initComponent.apply(this, arguments);
    }
});

Ext.reg('listdetail-panel', MonkMobile.views.ListDetailPanel);