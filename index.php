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

        // Fire off the boat horn every hour )
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

    // Define backstretch / rotating images
    $.backstretch(["boat.jpg", "boat2.jpg", "boat3.jpg", "boat4.jpg", "boat5.jpg", "boat6.jpg", "boat7.jpg", "boat8.jpg", "boat9.jpg"], {duration: 10000, fade: 500});

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