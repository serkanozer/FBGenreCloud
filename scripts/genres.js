function getGenres(){
	endpoint = "https://developer.echonest.com/api/v4/genre/list";
	params = {api_key:config.apiKey,bucket:["description","urls"],format:"json",results:"1381"};
	response = makeGet(endpoint,params);
	var result = JSON.parse(response)
	var genres = result.response.genres;
	var genreList ={}
	for(i=0; i<genres.length; i++){
		genre = genres[i]
		url_='';
		if ('wikipedia_url' in genre.urls){
			url_ = genre.urls.wikipedia_url
		}
		genreList[genre.name]={"url":url_,"description":genre.description}
	}
	return genreList
}
