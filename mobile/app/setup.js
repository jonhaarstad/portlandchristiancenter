MonkMobile.templates = {
	defaultList:"{title}",
	defaultDetail:"<h2>{title}</h2> {text}",
	eventDetail:"<h2>{title}</h2><h5>{times}</h5> {text}",
	mediaDetail: "<h2>{title}</h2><h4>{date}</h4><audio src='{audio}' controls /><a href='{audio}' id='droidplay' style='display:none;'>PLAY</a><p>{text}</p>",
	embedVideoDetail: '<h2>{title}</h2><h4>{date}</h4><tpl if="embedsrc"><div class="vidembed" style="width:300;"><iframe frameborder="0" src="{embedsrc}" width="300"></iframe></div></tpl>{text}',
	mediaList: "{title}",
	articleList: "{title}",
	eventList: '<div class="date"><span class="month">{month}</span><span class="day">{day}</span></div> {title}<tpl if="locname" <p class="subline">@ {locname}</p></tpl>',
	imageList:'<div class="avatar"<tpl if="thmb"> style="background-image: url({thmb}); width:50px; height:50px; overflow:hidden; display:block; float:left; margin-right:10px;"</tpl>></div><span class="name">{title}</span>',
	galleryList:'<div class="avatar"<tpl if="thmb"> style="background-image: url({thmb}); width:50px; height:50px; overflow:hidden; display:block; float:left; margin-right:10px;"</tpl>></div><span class="name">{title}<p class="subline"><span class="imgnumber">{number}</span> images</p></span>' 
}
MonkMobile.feature = {
	tpl:'<div class="latest"><div class="media-wrap"><div  id="playmedia" data-url="{audio}" class="listen"><img src="/mobile/public/resources/images/play_btn.png"/></div><img src="{image}" class="latest-image" width="300" height="300" /></div><h6>Latest Message <time>{date}</time></h6><h3>{title}</h3><p class="preview">{preview}</p></div><div id="moretabinfo">SWIPE TAB BAR TO SEE MORE</div>'
}
MonkMobile.setup = {
	tabs :[ 
   {
            iconCls:       'headphones',
            text:          'Media',
            controller:    'Media',
			viewtpl:{
				list:      MonkMobile.templates.imageList,
				detail:    MonkMobile.templates.mediaDetail
			}
        },  
         {
            iconCls:      'info2',
            text:         'Church Info',
            controller:   'Section',
			viewtpl:{
				find:     'mobile-church-info'
			}
        }, 
        {
            iconCls:      'compass1',
            text:         'Directions',
            controller:   'Section',
			viewtpl:{
				find:     'mobile-directions'
			}
        },
		{
			iconCls:      'calendar',
		    text:         'Events',
		    controller:   'Events',
			viewtpl:{
				list:     MonkMobile.templates.eventList,
				detail:   MonkMobile.templates.eventDetail
			}
		},        
        {
            iconCls:      'mail',
            text:         'Contact Us',
            controller:   'Section',
			viewtpl:{
				find:     'mobile-contact-us'
			}
        },    
		{
            iconCls:      'doc2',
            text:         'Articles',
            controller:   'Articles',
			viewtpl:{
				list:     MonkMobile.templates.defaultList,
				detail:   MonkMobile.templates.defaultDetail
        	}
		},    
		{
            iconCls:      'shop2',
            text:         'Giving',
            controller:   'Section',
			viewtpl:{
				find:	  'mobile-giving'
        	}
		},
		{
            iconCls:      'home',
            text:         'home',
            controller:   'Link',
			viewtpl:{
				url:      '/' //can be full url
			}
        }
	]
}

