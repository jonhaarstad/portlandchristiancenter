/* audio player controls replace native controls
   by adam@monkdev.com 2011
*/
function audioControls(ao){
      var wrap = document.createElement("div");
          wrap.setAttribute('class', 'auplayer');
          wrap.innerHTML = "<div class='play cntr playctr' title='play'></div><div class='track'><div class='scrub'></div><div class='loader'></div><div class='progress'></div></div>";

      var audioobj = ao,
          play = wrap.getElementsByClassName("playctr")[0], 
          track = wrap.getElementsByClassName("track")[0],
          loader = wrap.getElementsByClassName("loader")[0],
          progress = wrap.getElementsByClassName("progress")[0],
          scrub = wrap.getElementsByClassName("scrub")[0];
          
      audioobj.removeAttribute('controls');
      audioobj.style.display = 'none';
      
      this.togglePlay = function(e){
            if(audioobj.paused || audioobj.ended){
                if(audioobj.ended){
                    audioobj.currentTime = 0;
                }
                e.currentTarget.title = 'pause';
                audioobj.play();
            }else{
                e.currentTarget.title = 'play';
                audioobj.pause();
            } 
      }
      
      this.initControls = function(){
          if(document.createElement('audio').canPlayType('audio/mpeg')){
              loader.style.width = 0+'%';
              progress.style.width = 0+'%';
              play.title = 'play';
    
              play.addEventListener('touchstart',this.togglePlay,false); 
              scrub.addEventListener('touchstart',function(e){ 
                  setPlayProgress(e.targetTouches[0].pageX);
              },false);
              
              parent = audioobj.parentNode;
              parent.insertBefore(wrap, parent.childNodes[0]);
          }else{
               var playbutton = document.getElementById("droidplay");
                   playbutton.style.display = "block";
                   //playbutton.appendChild(document.createTextNode("PLAY"));
                   //playbutton.appendChild(document.createTextNode(src));
                   //audioobj.parentNode.appendChild(playbutton);
                   
          }  
      }
      
     audioobj.addEventListener('play',function(){
        play.title = 'pause';
         },false);
     audioobj.addEventListener('pause',function(){
         play.title = 'play';
     },false);
     audioobj.addEventListener('ended',function(e){
         e.currentTarget.pause();
     },false); 
     this.pause = function(){
         audioobj.pause();
     }
     var updateLoadState =  function(){
            var maxLoaded = 0.0;
            if(audioobj.buffered.length > 0){
                maxLoaded = audioobj.buffered.end(audioobj.buffered.length - 1);
            } 
            var percentLoaded = maxLoaded / audioobj.duration;
            loader.style.width = percentLoaded * 100 + "%";

        }
      var updateProgress = function(e){ 
            var length = audioobj.duration;
            var secs = audioobj.currentTime;
            var played = (secs / length) * 100;

            progress.style.width = played + "%"; 
        }
      var findPosX = function(obj) {
          var curleft = obj.offsetLeft;
          while(obj = obj.offsetParent) {
            curleft += obj.offsetLeft;
          }
          return curleft;
        }

      var setPlayProgress = function(xpos){ 
               var newPercent = Math.max(0, Math.min(1, (xpos - findPosX(track)) / track.offsetWidth));  
               audioobj.currentTime = newPercent * audioobj.duration;
               progress.style.width = newPercent * (track.offsetWidth -2) + "px";
        }

      audioobj.addEventListener('progress', updateLoadState, false);
      audioobj.addEventListener('timeupdate', updateProgress, false);
        
      this.initControls();    
}