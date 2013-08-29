/**
 * @class Galleries
 * @extends Ext.data.Model
 * The Galleries model
 */
Ext.regModel("Galleries", {
    fields: [
        {name: "title", type: "string"},
        {name: "thmb", type: "string"},
        {name: "preview", type: "string"},
		{name: "date", type: "string"},
		{name: "number", type: "string"},
		{name: "slug", type: "string"}
    ],
    
     proxy:{
			type:'ajax',
			url:'/mobile/lib/mk-modules/galleries.php',
			reader:{
				type:'json',
				root:'items'
			}
		}
});
