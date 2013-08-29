/**
 * @class Media
 * @extends Ext.data.Model
 * The Media model
 */
Ext.regModel("Blogs", {
    fields: [
        {name: "title", type: "string"},
        {name: "text", type: "string"},
        {name: "date", type: "string"},
		{name: "author", type: "string"},
		{name: "preview", type: "string"},
		{name: "image", type: "string"},
		{name: "slug", type: "string"},
		{name: "summary", type: "string"}
    ],
    
     proxy:{
			type:'ajax',
			url:'/mobile/lib/mk-modules/blogs.php?blogname=all',
			reader:{
				type:'json',
				root:'items'
			}
		}
});