/**
 * @class Media
 * @extends Ext.data.Model
 * The Media model
 */
Ext.regModel("Media", {
    fields: [
        {name: "title", type: "string"},
        {name: "text", type: "string"},
        {name: "summary", type: "string"},
        {name: "preview", type: "string"},
        {name: "date", type: "string"},
		{name: "audio", type: "string"},
		{name: "video", type: "string"},
		{name: "embed", type: "string"},
		{name: "image", type: "string"},
		{name: "thmb", type: "string"},
		{name: "embedtype",type:"string",mapping:"embed.type"},
		{name: "embedsrc",type:"string",mapping:"embed.media_id"},
		{name: "embed",type:"string",mapping:"embed.code"}
		
    ],
    
     proxy:{
			type:'ajax',
			url:'/mobile/lib/mk-modules/sermons.php',
			reader:{
				type:'json',
				root:'items'
			}
		}
});