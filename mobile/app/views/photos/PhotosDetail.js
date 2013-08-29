/**
 * @class Photos.
 * @extends Ext.Panel
 * A Panel showing the grid of photos.
 */
MonkMobile.views.PhotoPanel = Ext.extend(Ext.Panel, {
    cardSwtichAnimation:'slide',
	layout:'fit',
    initComponent : function() {
		//console.log(MonkMobile.viewport.dockedItems.map.tabBar.activeTab.viewtpl.detail);
		var p = this,
			data = this.data;
			//console.log(data);
        p.dockedItems = {
            dock: 'top',
  			overlay: false,
            xtype: 'toolbar',
			//style:'opacity: 0.8',
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
			listeners:{
				load:function(store,records,successful,operation){
					    
						var items = [];
						store.each(function(dta){
							var item = new Ext.Template( 
								'<div class="photo" style="display:block, width:100%,height:inherit, position:relative"><img src="{photo}" style="width:100%"/></div>',
								{compile:true}
							); 
					        
							items.push({
								html: item.apply(dta.data),
								cls:  dta.data.title
							});
						});

	                   var carousel = new Ext.Carousel({
							items:items,
							cls: "carousel-panel",
							ui:'light',
							//scroll:'both',
							modal:true,
							 layout:{
							  type:'hbox',
							  align:'middle'
							},
							activeItem: data.idx,
							/*afterRender: function() {
							       // var imagePosY = Ext.getDom('img').style.y /2;
							        //Ext.getDom('img').style.y = Ext.getDom('photo').style.y;
							        MonkMobile.views.PhotoPanel.superclass.afterRender.call(this);
							
							        this.mon(this.el, {
							            touchstart: this.handleEvent,
							            touchend: this.handleEvent,
							            touchmove: this.handleEvent,
							            touchdown: this.handleEvent,
							            dragstart: this.handleEvent,
							            drag: this.handleEvent,
							            dragend: this.handleEvent,
							            singletap: this.handleEvent,
							            tap: this.handleEvent,
							            doubletap: this.handleEvent,
							            taphold: this.handleEvent,
							            tapcancel: this.handleEvent,
							            swipe: this.handleEvent,
							            pinch: this.handleEvent,
							            pinchstart: this.handleEvent,
							            pinchend: this.handleEvent,
							            scrollend:this.handleEvent,
							            scope: this
							        });
							    },*/
							
							    /*handleEvent: function(e) {
							        //this.fireEvent('dragstart');
							        //console.log(e);
							        this.fireEvent('log', e.type, e);
							        var image = e.target;
							        console.log(e.type);
							        if(e.type === "pinch"){
							        image.scale = 1.0;
							        image.startScale = 1.0;
							        image.scale = image.StartScale * e.scale;
							        image.style.webkitTransform = 
							        'translate3d('+image.x+'px, '+ image.y + 'px, 0) ' + 'scale('+ image.scale + ')';
							        }
							        if(e.type == 'scrollend'){
							                        Ext.getDom('img').x = (e.targetTouches[0].pageX + e.targetTouches[1].pageX) / 2;
							                        Ext.getDom('img').y = (e.targetTouches[0].pageY + e.targetTouches[1].pageY) / 2;
							                    }
							        
							    },*/
							listeners:{

							    /*cardswitch:function(cont,newcard,oldcard,idx){
							        //console.log(newcard);
							        carousel.setScrollable(false);
							        var card = document.getElementById(newcard.id);
							        var image = card.getElementsByTagName("img")[0];
							        image.scale =  1.0;
							        image.startScale = 1.0;

							        							        
							        function touchhandler(event){
							            console.log(event);
							            console.log(event.target);
							        }
							        function transformImage(){
							            //console.log(image.style);
							            image.style.webkitTransform = 
							            'translate3d('+image.x+'px, '+ image.y + 'px, 0) ' + 'scale('+ image.scale + ')';
							            
							            //carousel.doComponentLayout();
							        }
							        function gesturechange(e){
							            //console.log(e.type);
							            if(e.type === "gesturechange"){
							                image.scale = image.startScale * e.scale;
    							            transformImage();
    							        }else if(e.type === "gesturestart"){
    							            //carousel.suspendEvents(true);
    							            
    							        }else if(e.type === "gestureend"){
    							            //courousel.resumeEvents();
    							           
    							        }
							        }
							        
							       image.addEventListener("gesturestart",gesturechange,false);
							       image.addEventListener("gesturestart", gesturechange, false); 
							       image.addEventListener("gesturechange",gesturechange, false);
							    
							    }*/
							}
						});
					   
					   p.add(carousel); //adds carousel to panel
					   p.doLayout();//refreshes layout 
					
					}
			},
			 proxy:{
					type:'ajax',
					url:'/mobile/lib/mk-modules/photos.php?galleryid='+data.id,
					reader:{
						type:'json',
						root:'items'
					}
				}
		});
		
		
        MonkMobile.views.PhotoPanel.superclass.initComponent.apply(this, arguments);
    }
});

Ext.reg('photo-panel', MonkMobile.views.PhotoPanel);

