MonkTouch version 2.0
Built with Sencha Touch 1.1
by Adam Randlett - adam@mokdevelopment.com


MonkTouch is a mobile application developed for the Ekklesia360 CMS.  http://www.ekklesia360.com

## SETUP 
The /app/__setup.js__ file determines application tabs and Ekklesia360 modules to populate the content pane along with the content layout html. Below will explain the api tags available as well as tag object structure.  


### MonkMobile.templates
>These templates are the html with Ekklesia360 api reference to use in the display panels. HTML is allowed and is pre-styled.  

### Module Variables 
#### Sermons/Articles

| __Name__              | __Usage__     | __Description__                                                                  |
| --------------------- | ------------- | -------------------------------------------------------------------------------- |
| Title                 | {title}       | Title of sermon/article record                                                   |
| Image                 | {image}       | Image of sermon/article record                                                   |
| Preview               | {preview}     | Preview of sermon/article text (200 characters)                                  |
| Author                | {author}      | Author name of sermon/article                                                    |
| Date                  | {date}        | Date of sermon/article in format (Jan 01 2011)                                   |
| Text                  | {text}        | Full Content Text                                                                |
| Summary               | {summary}     | Content from summary field                                                       |
| Slug                  | {slug}        | Slug format of sermon/article name (sermon-name-slug)                            |
| Audio                 | {audio}       | Audio url of sermon/article                                                      |
| Video                 | {video}       | Video url of sermon/article                                                      |
| Embed                 | {embed}       | Embed code of sermon/article (Vimeo or Youtube) *must use iframe embed code      | 
| Embed Src             | {embedsrc}    | Embed iframe src url of sermon/article (Vimeo or Youtube) *must use iframe embed code      | 
| Embed Type            | {embedtype}   | Embed type of sermon/article either (vimeo or youtube) *must use iframe embed code      | 
| Thumb                 | {thmb}        | Thumbnail image of sermon/article (size 50 x 50)                                 | 

#### Events

| __Name__              | __Usage__     | __Description__                                                                  |
| --------------------- | ------------- | -------------------------------------------------------------------------------- |
| Title                 | {title}       | Title of event record                                                            |
| Image                 | {image}       | Image of event record                                                            |
| Preview               | {preview}     | Preview of event text (200 characters)                                           |
| Text                  | {text}        | Full text of event content                                                       | 
| Name                  | {name}        | Name of event coordinator                                                        |
| Email                 | {email}       | Email of event coordinator                                                       |
| Phone                 | {phone}       | Phone number of event coordinator                                                |
| Start Date            | {startDate}   | Start Date of event in format (Jan 01 2011)                                      |
| Date Month            | {month}       | Month of event in format (Jan)                                                   |
| Date Day              | {day}         | Day of event in format (01)                                                      |
| Date End              | {end}         | End Date of event in format (Jan 01 2011)                                        |
| Times                 | {times}       | Full date and time of event in format (Jan 01 2011 10:30am - Jan 14 2011 5:00pm )|
| Location Name         | {locname}     | Location name of the event                                                       |
| Location Address      | {address}     | Location Address of the event                                                    |
| Google Map            | {googlemap}   | Google map of location                                                           | 
| Slug                  | {slug}        | Slug format of sermon name (event-name-slug)                                     | 
| Registration          | {register}    | Registration Link with linktext = Register                                       | 

#### Blogs

| __Name__              | __Usage__     | __Description__                                                                  |
| --------------------- | ------------- | -------------------------------------------------------------------------------- |
| Title                 | {title}       | Title of blog record                                                             |
| Image                 | {image}       | Image of blog record                                                             |
| Preview               | {preview}     | Preview of blog text (200 characters)                                            |
| Summary               | {summary}     | Summary of blog (200 characters)                                                 |
| Author                | {author}      | Author name of blog                                                              |
| Date                  | {date}        | Date of bloge in format (Jan 01 2011)                                            |
| Text                  | {text}        | Blog full text                                                                   |
| Slug                  | {slug}        | Slug format of blog name (blog-name-slug)                                        | 

#### Link List

| __Name__              | __Usage__     | __Description__                                                                  |
| --------------------- | ------------- | -------------------------------------------------------------------------------- |
| Title                 | {title}       | Title of list record                                                             |
| Image                 | {image}       | Image of list record                                                             |
| Url                   | {url}         | List item url                                                                    |
| Text                  | {text}        | Content from description field                                                   |
| Slug                  | {slug}        | Slug format of link name (link-name-slug)                                        | 

 

### MonkMobile.feature
> The feature is the initial welcome screen upon load of the application.  The default content pulls from the sermons module. If a different module is requested the /app/views/defaults/Welcome.js file will need to be updated to pull from a different model. The model pulls data from specific Ekklesia360 module.  *Please keep the 'moretabinfo' div for explanation of tab bar swipe action.*

#### Default Code
        <div class="latest">
            <div class="media-wrap">
                <div  id="playmedia" data-url="{audio}" class="listen">
                <img src="/mobile/public/resources/images/play_btn.png"/>
                </div>
                <img src="{image}" class="latest-image" width="320" height="150" />
            </div>
            <h6>Latest Message <time>{date}</time></h6>
            <h3>{title}</h3>
            <p class="preview">{preview}</p>
        </div>
        <div id="moretabinfo">SWIPE TABBAR TO SEEM MORE</div>



## Redirect Code 
> This code should be placed in the header of the websites index.php file. To see the home welcome panel the redirect location must go to "/mobile/#Welcome", otherwise it will not show feature sermon or what ever was added to feature layout template html.

<pre><code><script>
//window.console.log(navigator.userAgent);
if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/android/i)) || (navigator.userAgent.match(/Palm/i))) {
if (typeof(localStorage) != 'undefined' ) {
	try {
	    if(localStorage.getItem("redirectmobile") != "off"){
	        window.location = "/mobile/#Welcome";
	    }
	    localStorage.setItem("redirectmobile","");  
	} catch (e) {
		if (e == QUOTA_EXCEEDED_ERR) {
			//window.console.log("Quota Exceeded");
		}   
	}   
}
}
</script></code></pre>




