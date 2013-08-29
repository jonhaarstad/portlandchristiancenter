 /**
 * @class Media.views.Welcome
 * @extends Ext.Panel
 * A Panel showing the welcome page.
 */
MonkMobile.views.WelcomePanel = Ext.extend(Ext.Panel, {
    cardSwtichAnimation:'slide',
	styleHtmlContent: false,
	layout:'fit',
	scroll:false,
    initComponent : function() {
		
		var p = this;
		p.tpl = new Ext.Template(MonkMobile.feature.tpl);
	   	p.store = new Ext.data.Store({
			model: "Media",
			autoLoad:true,
			listeners :{
				load: function(store, records, successful, operation){
					p.tpl.compile(); 
					var dta = store.first().data,
						play;
					p.update(dta);
					play = document.getElementById('playmedia');
					play.addEventListener("touchstart",function(event){ 
						event.preventDefault();
						Ext.Msg.confirm("Play Message","Are you sure?",function(response){
								if(response !== "no"){
								  window.location = play.getAttribute('data-url');  
								}
						});
					},false);
				}
			}
		});
		/*p.store.load(function(data){
			p.tpl.compile();
			var dta = data[0].data;
			//p.items = [p.tpl];
			p.update(dta);   
		});*/

        MonkMobile.views.WelcomePanel.superclass.initComponent.apply(this, arguments);
    }
});

Ext.reg('welcome-panel', MonkMobile.views.WelcomePanel);