<html>
<head>
<script src="scripts/utils.js"></script>
<script src="scripts/artist.js"></script>
<script src="scripts/genres.js"></script>
<script src="scripts/config.js"></script>
<script src="wordcloud2.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<style>
html, body {
  width:  100%;
  height: 100%;
  margin: 0px;
}

</style>
</head>

<body >
<div id="canvasContainer">
<canvas id="myCanvas"></canvas>
<div id="genreDescription">
</div>
</div>
<script type="application/javascript">
    function resizeCanvas() {
        var canvas = document.getElementById("myCanvas");
        canvas.setAttribute("height", window.innerHeight);
        canvas.setAttribute("width", window.innerWidth);
    }
</script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : config.appId,
      xfbml      : true,
      version    : 'v2.4'
    });
	function onLogin(response) {
	  if (response.status == 'connected') {
	    FB.api('/me?fields=first_name', function(data) {
	      var welcomeBlock = document.getElementById('fb-welcome');
	      //welcomeBlock.innerHTML = 'Hello, ' + data.first_name + '!';
	    });
	  }
	}

	FB.getLoginStatus(function(response) {
	  // Check login status on load, and if the user is
	  // already logged in, go directly to the welcome message.
	  if (response.status == 'connected') {
	    onLogin(response);
	  } else {
	    // Otherwise, show Login dialog first.
	    FB.login(function(response) {
	      onLogin(response);
	    }, {scope: 'public_profile, user_friends, email, user_likes'});
	  }
	});

	function createArtists(musicList){
	  resultarray=musicList
	  arraylength=resultarray.length;
	  var artists = [];
	  for(var i=0; i<arraylength; i++){
	  	try{
	  		var el = resultarray[i];
	  		var artist = createArtistByFacebookID(el.id,el.name,el.created_time);
	  		if (artist)
	  			artists.push(artist)
	  	}
	  	catch(err){
	  		console.log(err+"i");
	  	}
	  }
	  return artists	
	}
	var genreDescriptions = getGenres()
	var x = "hello"
	window.drawBox = function drawBox(item, dimension) {
		if (item!==undefined){
		var bottombox =	document.getElementById("genreDescription")
		bottombox.innerHTML = '<a href='+genreDescriptions[item[0]].url+' target=_blank>'+item[0]+'</a><br>'
		var desc = genreDescriptions[item[0]].description
		bottombox.innerHTML += desc.substring(0,desc.length -1)
		bottombox.innerHTML += '<br>Associated Artists: ';
		assocArtists = genreToArtistsGlob[item[0]];
			for(i=0; i<assocArtists.length; i++){
				bottombox.innerHTML += '<a href=\"https://www.facebook.com/'+ assocArtists[i].id+'\" target=_blank>'+assocArtists[i].title+'</a> ';
			}
		}
	// if (item!==undefined)
	// 	
	};
	window.alertBox = function alertBox(item, dimension){
		if (item!==undefined){
			alert(genreToArtistsGlob[item[0]])
		}
	};

	function createGenres(artists){
		var genreCounts = {}
		var genreToArtists = {}
		for(i=0; i<artists.length; i++){
			artist = artists[i];
			var artistGenres = artist.genres;
			for (j=0; j<artistGenres.length; j++){
				var artistGenre = artistGenres[j];
				genreCounts[artistGenre] = genreCounts[artistGenre] ? genreCounts[artistGenre]+1 : 1;
				if (genreToArtists[artistGenre]===undefined){
					genreToArtists[artistGenre] = [{"id":artist.facebook_id,"title":artist.echonest_title}]
				}
				else{

					genreToArtists[artistGenre].push({"id":artist.facebook_id,"title":artist.echonest_title})
				}
			}
		}
		return {"genreCounts":genreCounts,"genreToArtists":genreToArtists};
	}
	var genreToArtistsGlob;
	FB.getLoginStatus(function(response){
		FB.api('/me/music', fields={limit:100},function(response) {
		  	//console.log(response);
		  	console.log(response.data)
			var artists = createArtists(response.data)
			var genreOutput= createGenres(artists);
			var genreHistogram = genreOutput.genreCounts
			var genreToArtists = genreOutput.genreToArtists
			genreToArtistsGlob = genreToArtists;
			console.log(genreToArtistsGlob)
			var genreList = [];
			for (var key in genreHistogram) {
  				if (genreHistogram.hasOwnProperty(key)) {
    			genreList.push([key,genreHistogram[key]])
  				}
			}
			document.getElementById("myCanvas").width  = window.innerWidth;
			document.getElementById("myCanvas").height = window.innerHeight*0.8;
			WordCloud(document.getElementById('myCanvas'), 	{ list: genreList,
															  gridSize: 18,
															  weightFactor: 3,
															  //fontFamily: 'Finger Paint, cursive, sans-serif',
															  //color: '#f0f0c0',
															  hover: window.drawBox,
															  click: window.alertBox,
  															  //backgroundColor: '#001f00',
															  weightFactor:16,
															} 
			);
		});
	});


    // ADD ADDITIONAL FACEBOOK CODE HERE
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>


</body>
</html>