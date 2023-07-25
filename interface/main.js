function openAlbumInfo(albumtoshowcase){
    //język
    var langapp = (lang) ? "l=en&" : "";
    
	//pokaż albumdetails
	$('.albumdetails').addClass('unvanished');
	//rozmyj tył
	$('.albumdetails').prev().addClass('blurred');

	//wypełnij albumdetails danymi
	$('.albumdetails .albumcontainer>img').attr({ "src":"/library/"+albumtoshowcase+".png", "alt":albumtoshowcase });
	$('.albumdetails h2').text(albumtoshowcase);
	$('.albumdetails p').html(albumy[albumtoshowcase]['opis']);
	$('.albumdetails ul li:nth-child(1)').text(albumy[albumtoshowcase]['rok']);
	$('.albumdetails a').attr('href', "?"+langapp+"a="+albumtoshowcase.replace(/'/g, "%27"));
	if(albumtoshowcase == "brak albumu") $('.albumdetails ol').addClass("numberless");
		else $('.albumdetails ol').removeClass("numberless");
	// insecure // history.pushState("Album: "+albumtoshowcase, "Album: "+albumtoshowcase, "http://brzoskwinia.wpww.pl/?"+langapp+"a="+albumtoshowcase.replace(/'/g, "%27"));
	
	//przekoloruj detale
	$('.albumdetails h1').css("background-image", "linear-gradient(to right bottom, #000000ee, "+albumy[albumtoshowcase]['kolor']+"77)");
	$('.albumdetails h2').css("border-bottom-color", albumy[albumtoshowcase]['kolor']);
	$('.albumdetails h3').css("background", albumy[albumtoshowcase]['kolor']+"77");
	
	//utwórz listę utworów
	for(i=0; i<albumy[albumtoshowcase]['utwory'].length; i++){
		$('.albumdetails ol li:nth-child('+(i+1)+')').css("display", "list-item").text(albumy[albumtoshowcase]['utwory'][i]); }
	for(i=albumy[albumtoshowcase]['utwory'].length; i<20; i++){
		$('.albumdetails ol li:nth-child('+(i+1)+')').css("display", "none"); }
}

function openSongInfo(songtoshowcase){
    //TŁUMACZENIA
    var texts = [
        (lang) ? "from “" : "z albumu „",
        (lang) ? "Project: " : "Projekt: ",
        (lang) ? "Recorded: " : "Nagrany: ",
        (lang) ? "The Muse (pl): " : "Inspiracja: "
        ];
    //KONIEC TŁUMACZEŃ
    //język
    var langapp = (lang) ? "l=en&" : "";
    
	//pokaż songdetails
	$('.songdetails').addClass('unvanished');
	//rozmyj info o albumie
	$('.songdetails').prev().addClass('blurred');
	
	//wypełnij songdetails danymi
	$('.songdetails .albumcontainer>img').attr({ "src":"/library/"+piosenki[songtoshowcase]['album']+".png", "alt":piosenki[songtoshowcase]['album']});
	$('.songdetails h2').text(songtoshowcase);
	$('.songdetails h3').text(texts[0]+piosenki[songtoshowcase]['album']+"”, #"+piosenki[songtoshowcase]['nr']);
		//jeżeli nie ma albumu, to nie ma "z albumu „brak albumu”"
		if(piosenki[songtoshowcase]['album'] == "brak albumu"){ $('.songdetails h3').text(""); }
	$('.songdetails li:nth-child(1)').text(texts[1]+piosenki[songtoshowcase]['idproj']);
	$('.songdetails li:nth-child(2)').text(texts[2]+piosenki[songtoshowcase]['datanagr']);
	$('.songdetails li:nth-child(3)').text(texts[3]+piosenki[songtoshowcase]['inspir']);
	$('.songdetails a').attr('href', "?"+langapp+"s="+songtoshowcase.replace(/'/g, "%27"));
	// insecure // history.pushState("Song: "+songtoshowcase, "Song: "+songtoshowcase, "http://brzoskwinia.wpww.pl/?"+langapp+"s="+songtoshowcase.replace(/'/g, "%27"));
	
	//przekoloruj detale
	$('.songdetails .info').css("background", "linear-gradient(to right bottom, #000000ee, "+albumy[piosenki[songtoshowcase]['album']]['kolor']+"77)");
}

function openBoth(album, song){
	openAlbumInfo(album);
	openSongInfo(song);
}

function closeInfo(whatinfo){
	$(whatinfo).removeClass('unvanished');
	$('.green-tick').removeClass('confirmed'); //pozwól na ponowienie animacji
	$(whatinfo).prev().removeClass('blurred'); //usuń rozmycie z panelu wcześniej
	if(whatinfo == ".albumdetails"){ //jeśli zamykam ostatnią, wyczyść adres
	    // insecure // history.pushState("Song list", "Song list", "http://brzoskwinia.wpww.pl/");
	}else{ //jeśli nie, to zakładam, że to album, więc szukam h2, żeby z niej wyciągnąć teleport
	    var albumtoshowcase = $(whatinfo).prev().find("h2").text();
	    var langapp = (lang) ? "l=en&" : "";
	    // insecure // history.pushState("Album: "+albumtoshowcase, "Album: "+albumtoshowcase, "http://brzoskwinia.wpww.pl/?"+langapp+"a="+albumtoshowcase.replace(/'/g, "%27"));
	}
	
}

function loadSong(song, album){
    //TŁUMACZENIA
    var texts = [
        (lang) ? "Player error" : "Błąd odtwarzacza",
        (lang) ? "Try again" : "Spróbuj ponownie"
        ];
    //KONIEC TŁUMACZEŃ
    
	//dodaj piosenkę do listy
	$('#player>audio').attr("src", "library/"+album+"/"+song+".ogg");
	$('#player .load').css('display', 'initial');
	$('#player>audio').on('canplay canplaythrough', function(){ $('#player>audio')[0].play(); $('#player .load').css('display', 'none'); });
	$('#player>audio').on('error', function(){
		labelsong(texts[0], '-');
		$('#player .load').css('display', 'none');
		$('#player p').text(texts[1]);
	});
	
	labelsong(song, album);
	$('#player .pause').html("<img src='/interface/controls/pause.svg' alt='pause'>");
}
function loadNext(song, album){
    //TŁUMACZENIA
    var texts = [
        (lang) ? "This track is the last from “" : "Ten utwór jest ostatnim z albumu „"
        ];
    //KONIEC TŁUMACZEŃ
    
    //użyj tego, co gram teraz, żeby załadować kolejny utwór
	song = albumy[album]['utwory'][piosenki[song]['nr']];
	if(song !== undefined){ loadSong(song, album); return false; }
	else{ /* alert(texts[0]+album+"”."); */ return true; }
}

function labelsong(song, album = 0){ //oetykietuj
    //TŁUMACZENIA
    var texts = [
        (lang) ? "Playback ended." : "Odtwarzanie albumu zakończone.",
        (lang) ? "Thank you for listening!" : "Dziękuję za odsłuch!",
        (lang) ? "Select track..." : "Wybierz utwór...",
        (lang) ? "Song library" : "Biblioteka utworów",
        (lang) ? "Now playing:" : "Teraz odtwarzam:"
        ];
    //KONIEC TŁUMACZEŃ
    
	if(song == "reset"){
		$('#player p').text(texts[0]);
		$('#player h4').text(texts[1]).css("color", "unset");
		$('#player h5').text("");
		$('#player .album').attr({"src":"library/brak albumu.png", "alt":texts[2]});
		$(document).prop('title', texts[3]+" | WPWW");
	}else{
		$('#player p').text(texts[4]);
		$('#player h4').text(song).css("color", albumy[album]['kolor']);
		$('#player h5').text(album);
		$('#player .album').attr({"src":"library/"+album+".png", "alt":album});
		$(document).prop('title', song+" • "+album+" | "+texts[3]+" WPWW");
	}
}

function audiocontroler(whattodo){
	var player = $('#player>audio')[0];
	if(whattodo == 'pause'){
		if(player.paused){ player.play(); $('#player .pause').html("<img src='/interface/controls/pause.svg' alt='pause'>"); }
		else{ player.pause(); $('#player .pause').html("<img src='/interface/controls/play.svg' alt='play'>"); }
	}
}

//////////////////////////////

$(function(){

$('.singlealbum').click(function(){ openAlbumInfo($("img", this).attr("alt")); });
$('#albumdetailsclose').click(function(){ closeInfo('.albumdetails'); });
$('.albumdetails ol li').click(function(){ openSongInfo($(this).text()); });
$('#songdetailsclose').click(function(){ closeInfo('.songdetails'); });

//nowinki
$('.newthing .hoverplay, .newthing img').click(function(){ loadSong($('.newthing div:nth-child(2) h3').text(), $('.newthing img').attr("alt")); });
$('.newthing div:nth-child(2) h3').click(function(){ openBoth($(this).next("h4").text(), $(this).text()); });
$('.newthing div:nth-child(2) h4').click(function(){ openAlbumInfo($(this).text()); });

//usuwacz hintów
$('.songdetails .hoverplay, .songdetails img').click(function(){ $('.hinto').addClass("vanished"); });

$('.songdetails .hoverplay, .songdetails img').click(function(){ loadSong($('.songdetails img').parent().next().children('h2').text(), $('.songdetails img').attr("alt")); });
$('.albumdetails .hoverplay, .albumdetails img').click(function(){ loadSong(albumy[$('.albumdetails img').attr("alt")]['utwory'][0], $('.albumdetails img').attr("alt")); });

$('#player .pause').click(function(){ audiocontroler('pause'); });
$('#player .next').click(function(){ loadNext($('#player h4').text(), $('#player h5').text()); });
$('#player>audio').on('ended', function(){ if(loadNext($('#player h4').text(), $('#player h5').text())){ labelsong("reset"); } });
$('#player h4').click(function(){ openBoth(piosenki[$(this).text()]['album'], $(this).text()); });
$('#player h5').click(function(){ closeInfo('.songdetails'); closeInfo('.albumdetails'); openAlbumInfo($(this).text()); });

$('.albumdetails a, .songdetails a').click(function(e){ e.preventDefault();
	//tymczasowa kopiarka
	var $temp = $("<input>");
	$("body").append($temp);
	
	//przygotuj adres
	var adres = new Array(window.location.origin+"/", $(this).attr('href'));
	adres[1] = adres[1].replace(/ /g, "%20");
	adres[1] = adres[1].replace(/'/g, "%27");
	adres = adres.join("");
	
	//kontynuuj kopiowanie
	$temp.val(adres).select();
	document.execCommand("copy");
	$temp.remove();
	
	//oznacz, że gotowe
	$(this).next().addClass('confirmed');
});

//$('#player>audio').prop("volume", 0.75);
$("#player input[type='range']").on("input", function(){
	$("#player>audio")[0].volume = this.value/100;
});

});