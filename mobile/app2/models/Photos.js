/**
 * @class Photos
 * @extends Ext.data.Model
 * The Photos model
 */
Ext.regModel("Photos", {
    fields: [
        {name: "title", type: "string"},
        {name: "description", type: "string"},
		{name: "photo", type: "string"}, 
		{name: "thumb", type: "string"},
		{name: "number", type: "string"}
    ]
});
