/**
 * @class Events
 * @extends Ext.data.Model
 * The Events model
 */
Ext.regModel("Events", {
    fields: [
        {name: "title", type: "string"},
        {name: "text", type: "string"},
		{name: "preview", type: "string"},
		{name: "image", type: "string"},
		{name: "slug", type: "string"},
		{name: "register", type: "string"},
		{name: "startDate", type:"string",mapping:"date.startDate"},
		{name: "month", type:"string",mapping:"date.month"},
		{name: "day", type:"string",mapping:"date.day"},
		{name: "end", type:"string",mapping:"date.end"},
		{name: "times", type:"string",mapping:"date.times"},
		{name: "locname", type:"string",mapping:"loc.name"},
		{name: "address", type:"string",mapping:"loc.address"},
		{name: "googlemap",type:"string",mapping:"loc.googlemap"},
		{name: "coordname", type:"string",mapping:"coord.name"},
		{name: "email", type:"string",mapping:"coord.email"},
		{name: "phone",type:"string",mapping:"coord.phone"}
    ],
  
     proxy:{
			type:'ajax',
			url:'/mobile/lib/mk-modules/events.php',
			reader:{
				type:'json',
				root:'items'
			}
		}
});
