/**
 * @class Link
 * @extends Ext.Controller
 * The Link controller
 */
  
Ext.regController("Link", {
	fullscreen:true,
    index: function() { 
   		Ext.Msg.confirm("Leaving The App","Are you sure?",function(response){
				if(response !== "no"){
				  var homename = MonkMobile.viewport.dockedItems.map.tabBar.activeTab.text;
				  if(homename.toLowerCase() === "home" || homename.toLowerCase() === "full site"){
				          function setStorage(str,val){  
	          					if (typeof(localStorage) != 'undefined' ) {
	          						try { 
	          							localStorage.setItem(str, val); //saves to the database, "key", "value"
	          						} catch (e) { 
	          							//window.console.log("localStorage Error");
	          							if (e == QUOTA_EXCEEDED_ERR) {
	          								//window.console.log("Quota Exceeded");
	          							}   
	          						}   
	          					} 
	          				}
	          				function clearStorage(str){
	          					 if (typeof(localStorage) != 'undefined' ) { 
	          						localStorage.setItem(str,"");
	          					 }
	          				}
	          				setStorage("redirectmobile","off");    
				  }
				  window.location = MonkMobile.viewport.dockedItems.map.tabBar.activeTab.viewtpl.url;  
				}
		});
    }   
});