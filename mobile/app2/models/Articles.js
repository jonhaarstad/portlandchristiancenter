/**
 * @class Articles
 * @extends Ext.data.Model
 * The Articles model
 */
Ext.regModel("Articles", {
    fields: [
        {name: "title", type: "string"},
        {name: "text", type: "string"},
        {name: "date", type: "string"},
		{name: "author", type: "string"},
		{name: "preview", type: "string"},
		{name: "image", type: "string"},
		{name: "embedtype",type:"string",mapping:"embed.type"},
		{name: "embedsrc",type:"string",mapping:"embed.media_id"},
		{name: "embed",type:"string",mapping:"embed.code"},
		{name: "slug", type: "string"}
    ],
    
     proxy:{
			type:'ajax',
			url:'/mobile/lib/mk-modules/articles.php',
			reader:{
				type:'json',
				root:'items'
			}
		}
});