<html lang="en">

<head>
	<link rel="stylesheet" href="flickr_styles.css">
</head>

<body>

	<div id="wrapper">
		<div id="title"></div>
		<div id="picContainer">
			<a href="" id="A1" target="_blank">
				<img class="picContainer-item" id="1" src="" alt="">
			</a>
			<a href="" id="A2" target="_blank">
				<img class="picContainer-item" id="2" src="" alt="">
			</a>
			<a href="" id="A3" target="_blank">
				<img class="picContainer-item" id="3" src="" alt="">
			</a>
			<a href="" id="A4" target="_blank">
				<img class="picContainer-item" id="4" src="" alt="">
			</a>
			<a href="" id="A5" target="_blank">
				<img class="picContainer-item" id="5" src="" alt="">
			</a>
			<a href="" id="A6" target="_blank">
				<img class="picContainer-item" id="6" src="" alt="">
			</a>
			<a href="" id="A7" target="_blank">
				<img class="picContainer-item" id="7" src="" alt="">
			</a>
			<a href="" id="A8" target="_blank">
				<img class="picContainer-item" id="8" src="" alt="">
			</a>
			<a href="" id="A9" target="_blank">
				<img class="picContainer-item" id="9" src="" alt="">
			</a>
			<a href="" id="A10" target="_blank">
				<img class="picContainer-item" id="10" src="" alt="">
			</a>
			<a href="" id="A11" target="_blank">
				<img class="picContainer-item" id="11" src="" alt="">
			</a>
			<a href="" id="A12" target="_blank">
				<img class="picContainer-item" id="12" src="" alt="">
			</a>
			<a href="" id="A13" target="_blank">
				<img class="picContainer-item" id="13" src="" alt="">
			</a>
			<a href="" id="A14" target="_blank">
				<img class="picContainer-item" id="14" src="" alt="">
			</a>
			<a href="" id="A15" target="_blank">
				<img class="picContainer-item" id="15" src="" alt="">
			</a>
			<a href="" id="A16" target="_blank">
				<img class="picContainer-item" id="16" src="" alt="">
			</a>
			<a href="" id="A17" target="_blank">
				<img class="picContainer-item" id="17" src="" alt="">
			</a>
			<a href="" id="A18" target="_blank">
				<img class="picContainer-item" id="18" src="" alt="">
			</a>
			<a href="" id="19" target="_blank">
				<img class="picContainer-item" id="19" src="" alt="">
			</a>
			<a href="" id="20" taget="_blank">
				<img class="picContainer-item" id="20" src="" alt="">
			</a>

		</div>
		<iframe src="https://player.vimeo.com/video/172100516" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<p><a href="https://vimeo.com/172100516">Banking and Insurance Summit 2016</a> from <a href="https://vimeo.com/bankingcheck">BankingCheck</a> on <a href="https://vimeo.com">Vimeo</a>.</p>
	<ul class="list-e">
					<li class="vm"><a rel="external" href=""></a></li>
					<li class="yt"><a rel="external" href=""></a></li>
				</ul>
	</div>


	<script>
		function shuffle(id) {
			var j, x, i;
			for (i = id.length; i; i -= 1) {
				j = Math.floor(Math.random() * i);
				x = id[i - 1];
				id[i - 1] = id[j];
				id[j] = x;
			}
		}

		var url = "https://api.flickr.com/services/rest/";
		var method = "?method=flickr.photosets.getPhotos";
		var apiKey = "09b1da9bc6f309062c8bc100749cfece";
		var photoSetID = "72157668688984946";
		var userID = "125591374%40N02";
		jsonCall = url + method + "&api_key=" + apiKey + "&photoset_id=" + photoSetID + "&user_id=" + userID + "&format=json&nojsoncallback=1";
		var i = 1;
		var j = 0;
		var temp = [];
		var tmp;
		var id = [];
		var jsonCall;
		var divNumb;
		var request = new XMLHttpRequest();

		request.open('GET', jsonCall, true);

		request.onload = function () {

			if (request.status >= 200 && request.status < 400) {
				// Success!
				var data = JSON.parse(request.responseText);
				console.log(data);
				for (j; j < data.photoset.photo.length; j++) {
					id.push(data.photoset.photo[j].farm + "/" + data.photoset.photo[j].server + "/" + data.photoset.photo[j].id + "_" + data.photoset.photo[j].secret);
				}
				shuffle(id);
				divNumb = document.getElementsByTagName("img").length;

				for (i; i <= divNumb; i++) {
					tmp = id[i];
					temp[i] = tmp.substring(7, 18);
					document.getElementById(i).src = "https://c2.staticflickr.com/" + id[i] + "_q.jpg";
					document.getElementById("A" + i).href = "http://www.flickr.com/bankingcheck/" + temp[i];
				}

			} else {
				// We reached our target server, but it returned an error
				console.log('Server erreicht, aber fehler beim Laden der json Datei');

			}
		};

		request.onerror = function () {
			console.log('Fehler beim Laden der json Datei');
		};

		request.send();
	</script>
</body>

</html>