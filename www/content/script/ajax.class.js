/*
	UrlAjax.groupeDeFonctionSimiliare = '/api/Nom.ajax.php'
*/
var UrlAjax = UrlAjax || {};
UrlAjax.infoblox = '/api/infoblox.ajax.php';
UrlAjax.user = '/api/membre.ajax.php';
UrlAjax.connexion= '/api/connexion.ajax.php';
UrlAjax.googletoken = 'https://www.googleapis.com/oauth2/v3/tokeninfo';
UrlAjax.network = '/api/network.ajax.php';
UrlAjax.texte = '/api/texte.ajax.php';
UrlAjax.bugtracker = '/api/bugtracker.ajax.php';
UrlAjax.switch = '/api/switch.ajax.php';
UrlAjax.cache = '/api/cache.ajax.php';
UrlAjax.ip = '/api/ip.ajax.php';

/*
	@Prototype de l'objet ajax


	Creation de l'objet
	var obj = ReqAjax("nomDeLaRequette", paramettre_de_la_requette);

	Envoie de la requette :

	obj.send('UrlAjax.destination');


	obj.send('UrlAjax.destination', function(callback){
			callback = "Valeur de retour recuperable, si pas de retour callback =1 par defaut"
	});
*/
var ReqAjax = function(req,args=0)
{
	this.req = req;
	this.args = args;
}

ReqAjax.prototype.send = function(url, callback=1){
	$.get(url,
		{
			req : this.req,
			args : this.args
		}, 
		function(result=1)
		{
			if(callback === 1)
			{
				return 1;
			}else{
				callback(result);
			}
		}
	);
}

ReqAjax.prototype.sendPost = function(url, callback=1){
	$.post(url,
		{
			req : this.req,
			args : this.args
		}, 
		function(result=1)
		{
			if(callback === 1)
			{
				return 1;
			}else{
				callback(result);
			}
		}
	);
}

/*
	Extention de l'array js rajoutant removeAllOccurencesOf
	usage :
	
	array.removeAllOccurencesOf(0);

	Enleve tout les 0 numerique de l'array (attention au typage)

	array.removeAllOccurencesOf("0");

	Enleve tout les "0" au format string de l'array (attention au typage)

*/
Object.defineProperty(Array.prototype, "removeAllOccurrencesOf", {
    enumerable: false,
    value: function (itemToRemove) {
        var removeCounter = 0;
        for (var index = 0; index < this.length; index++) {
            if (this[index] === itemToRemove) {
                this.splice(index, 1);
                removeCounter++;
                index--;
            }
        }
        return removeCounter;
    }
});


/*
	dedoublone un array
*/
function uniq(a) {
    return a.sort().filter(function(item, pos, ary) {
        return !pos || item != ary[pos - 1];
    })
}

/*
	Compte le nombre d'occurence d'un element dans un array
*/
function findOccurrences(arr, val) {
    var i, j,
        count = 0;
    for (i = 0, j = arr.length; i < j; i++) {
        (arr[i] === val) && count++;
    }
    return count;
}

/*
    Permet la generation de couleurs aléatoires
*/
(function(){
    Colors = {};
    Colors.names = {
        aqua: "#00ffff",
        orange: "#FF6E41",
        blue: "#0000ff",
        brown: "#a52a2a",
        cyan: "#00ffff",
        darkcyan: "#008b8b",
        darkgrey: "#a9a9a9",
        darkgreen: "#006400",
        darkkhaki: "#bdb76b",
        darkmagenta: "#8b008b",
        darkolivegreen: "#556b2f",
        darkorange: "#ff8c00",
        darkorchid: "#9932cc",
        darkred: "#8b0000",
        darksalmon: "#e9967a",
        darkviolet: "#9400d3",
        fuchsia: "#ff00ff",
        green: "#008000",
        indigo: "#4b0082",
        khaki: "#f0e68c",
        lightblue: "#add8e6",
        lightcyan: "#e0ffff",
        lightgreen: "#90ee90",
        lightgrey: "#d3d3d3",
        lightpink: "#D91E18",
        lightyellow: "#ffffe0",
        lime: "#00ff00",
        magenta: "#ff00ff",
        maroon: "#800000",
        navy: "#000080",
        olive: "#808000",
        orange: "#ffa500",
        pink: "#ffc0cb",
        purple: "#800080",
        violet: "#800080",
        red: "#ff0000",
        silver: "#c0c0c0",
        yellow: "#ffff00"
    };
    Colors.random = function() {
        var result;
        var count = 0;
        for (var prop in this.names)
            if (Math.random() < 1/++count)
               result = prop;
        return { name: result, rgb: this.names[result]};
    };
})();

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};