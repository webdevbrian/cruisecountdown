<!DOCTYPE html> 
<html lang="en-us">
<head>
  <meta charset="utf-8">
  <title>Cruise Countdown</title>
  <meta name="viewport" content="width=device-width, minimum-scale=0.5, maximum-scale=1.0, user-scalable=no"/>
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="stylesheet" href="css/reset.css" /> 
  <link rel="stylesheet" href="css/normalize.css" />
  <link rel="stylesheet" href="css/style.css" /> 
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/backstretch.js"></script>
  <script type="text/javascript" src="js/weather.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {

    // Scroll and hide address bar on load
    window.addEventListener("load",function() {
        setTimeout(function(){
            window.scrollTo(0, 1);
        }, 0);
    });

    // Disable touch dragging
    document.ontouchstart = function(e){ 
        e.preventDefault(); 
    }

    // Let's set the count down date and time
    var cruiseEnd = new Date('03/14/2014 12:01 AM');

    var _second = 1000;
    var _minute = _second * 60;
    var _hour = _minute * 60;
    var _day = _hour * 24;
    var timer;

    function showRemaining() {
        var now = new Date();
        var timeRemaining = cruiseEnd - now;

        // Count down is over, let's show a message that it's cruise time!
        if (timeRemaining < 0) {
            clearInterval(timer);
            $('#countdown').html('<img src="cruisetime.png" />');
            return;
        };

        var days = Math.floor(timeRemaining / _day);
        var hours = Math.floor((timeRemaining % _day) / _hour);
        var minutes = Math.floor((timeRemaining % _hour) / _minute);
        var seconds = Math.floor((timeRemaining % _minute) / _second);

        $('#countdown').html('<div id="days">' + days + ' days' + '</div><div id="hours">' + hours + ' hrs' + '</div><div id="minutes">' + minutes + ' mins' + '</div><div id="seconds">' + seconds + ' secs' + '</div>');

        // Fire off the boat horn every hour
        if(minutes=='0' && seconds =='0'){
          boatHonk();
        };
    };

    function boatHonk() {
      // Interesting way to get an iOS device to just fire off an audio clip (older iOS (v4) that is, not the newer HTML5 audio capable devices)
      $('iframe').remove();
      var ifr = document.createElement("iframe");

      ifr.setAttribute('src', "alarm.mp3");
      ifr.setAttribute('width', '1px');
      ifr.setAttribute('height', '1px');
      ifr.setAttribute('scrolling', 'no');
      ifr.style.border="0px";

      document.body.appendChild(ifr);
    };

    function weatherUpdate() {

      // Clear the old weather content
      $('#weather').html('');

      // Init Simple Weather and populate #weather
      $.simpleWeather({
        location: 'Miami, FL',
        unit: 'f',
        success: function(weather) {
          html = 'Current weather in Miami:<br/>' + weather.temp + 'F, ' + weather.currently + '<img src="'+weather.thumbnail+'" />';
          $("#weather").html(html);
        },
        error: function(error) {
          $("#weather").html('<p>'+error+'</p>');
        }
      });

    };

    // Init timer
    timer = setInterval(showRemaining, 1000);

    // Init weather update every 20 minutes
    updateWeather = setInterval(weatherUpdate, 1200000);

    // Init weather & time remaining on first load
    weatherUpdate();
    showRemaining();

    // Init Flickr API, and create backstretch instance
    var makeFlickrRequest = function(options, cb) {
      var url, item, first;

      url = "http://api.flickr.com/services/rest/";
      first = true;
      $.each(options, function(key, value) { 
        url += (first ? "?" : "&") + key + "=" + value;
        first = false; 
      });

      $.get(url, function(data) { cb(data); });

    };

    var options = { 
      "api_key": "b1e28f8678b531648d4601d5db96adfb",
      "method": "flickr.photos.search",
      "format": "json",
      "safe_search": "1",
      "content_type": "1",
      "nojsoncallback": "1",
      "text": "carnival breeze"
    };

    makeFlickrRequest(options, function(data) { 

      // We need to construct an array of the correctly formed image urls for backstretch
      var flickrImgs = [];
      for (var i=0;i<50;i++) {
        var url = 'http://farm' + data['photos']['photo'][i].farm + '.staticflickr.com/' + data['photos']['photo'][i].server + '/' + data['photos']['photo'][i].id + '_' + data['photos']['photo'][i].secret + '_z.jpg';
        flickrImgs.push(url);
      };

      // Let's shuffle the flickr images to make them random from image to image
      function shuffle(array) {
        var currentIndex = array.length
          , temporaryValue
          , randomIndex
          ;

        // While there remain elements to shuffle...
        while (0 !== currentIndex) {

          // Pick a remaining element...
          randomIndex = Math.floor(Math.random() * currentIndex);
          currentIndex -= 1;

          // And swap it with the current element.
          temporaryValue = array[currentIndex];
          array[currentIndex] = array[randomIndex];
          array[randomIndex] = temporaryValue;
        }

        return array;
      }

      // Shuffle the flickr images
      shuffle(flickrImgs);

      // Define backstretch / rotating images
      $.backstretch([flickrImgs[0],flickrImgs[1],flickrImgs[2],flickrImgs[3],flickrImgs[4],flickrImgs[5],flickrImgs[6],flickrImgs[7],flickrImgs[8],flickrImgs[9],flickrImgs[10],flickrImgs[11],flickrImgs[12],flickrImgs[13],flickrImgs[14],flickrImgs[15],flickrImgs[16],flickrImgs[17],flickrImgs[18],flickrImgs[19],flickrImgs[20],flickrImgs[21],flickrImgs[22],flickrImgs[23],flickrImgs[24],flickrImgs[25],flickrImgs[26],flickrImgs[27],flickrImgs[28],flickrImgs[29],flickrImgs[30],flickrImgs[31],flickrImgs[32],flickrImgs[33],flickrImgs[34],flickrImgs[35],flickrImgs[36],flickrImgs[37],flickrImgs[38],flickrImgs[39],flickrImgs[40],flickrImgs[41],flickrImgs[42],flickrImgs[43],flickrImgs[44],flickrImgs[45],flickrImgs[46],flickrImgs[47],flickrImgs[48],flickrImgs[49]], {duration: 4000, fade: 500});

    });

  }); // DOM Ready
  </script>
</head> 
<body>
  <div id="content">
    <div id="countdown"></div>
    <div id="weather"></div>
  </div>
</body>
</html>