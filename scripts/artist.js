var numOfRequests = 0;
function Artist(facebook_id,create_date,facebook_title,echonest_id,echonest_title,genres){
	this.facebook_id = facebook_id;
	this.create_date = create_date;
	this.echonest_id = echonest_id;
	this.facebook_title= facebook_title;
	this.echonest_title = echonest_title;
	this.genres = genres;
}
function parseEchonestArtistResponse(Response){
	var result = JSON.parse(Response)
	status_code = result.response.status.code;
	if (status_code == '0'){
		artist = result.response.artist;
		echonest_id = artist.id
		echonest_title = artist.name
		genre_pairs = artist.genres;
		genres = []
		for (i=0; i<genre_pairs.length; i++){
			genres.push(genre_pairs[i].name)
		}
		return {"id":echonest_id,"title":echonest_title,"genres":genres};
	}
	return null;
}
function createArtistByFacebookID(facebook_id,facebook_name,facebook_date){
	if (numOfRequests == 120){
		numOfRequests = 0
		console.log("waiting");
		setTimeout(createArtistByFacebookID(facebook_id,facebook_name,facebook_date),1000*120
			);
		console.log("complete");

	}
	else{
		endpoint = "https://developer.echonest.com/api/v4/artist/profile";
		params = {api_key:config.apiKey,id:"facebook:artist:"+facebook_id,format:"json",bucket:"genre"};
		response = makeGet(endpoint,params);
		numOfRequests = numOfRequests+1;
		var res = parseEchonestArtistResponse(response);
		if (res){
			return new Artist(facebook_id,facebook_date,facebook_name,res.id,res.title,res.genres)
		}
	}
	return null;	
}

//createArtistByFacebookID("106094549422037");