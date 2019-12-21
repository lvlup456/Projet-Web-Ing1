call = function() {
    var val = $("#category option:selected").text();
    $("#formations select").hide();
    $("#formations .noneform").hide();
    $("#formations .unselectform").hide();
    val = ("search["+val+"]").toLowerCase();
    var select = $("#formations select[name*='"+val+"']");
    if (select.length > 0 && select[0].length > 1){
        select.show()
    } else {
        if ($("#category option:selected").size() > 0 && $("#category option:selected")[0].index == 0){
            $("#formations .unselectform").show();
        } else {
            $("#formations .noneform").show();
        }
    }
}
$(document).ready(function () {
    //on masque le select classique
    if ($("#map-selector").size() == 0)
        return
    $("#map-selector").css("display", "none");
    call();
    //on ajoute un div #container-map-selector qui contiendra la carte
    $("#map-selector").parent().append("<div id='container-map-selector'></div>");
    //on initie la carte sur cet élément
    var map = new jvm.Map({
        container: $("#container-map-selector"),
        map: 'fr_merc',
        regionsSelectable: true,
        //à chaque clic sur un département
        onRegionSelected: function () {
            //on vide le select
            $("#map-selector").val("");
            //et on sélectionne chaque options correspondant au département sélectionné sur la carte
            $.each(map.getSelectedRegions(), function (index, region) {
                $("#map-selector option[value=" + region.replace("FR-", "") + "]").prop("selected", true);
            });
        }
    });
    //au départ si des options du select sont présélectionnés, on les sélectionnes sur la carte
    $("#map-selector option:selected").each(function () {
        map.setSelectedRegions("FR-"+$(this).val());
    });
});
$('#category').change(call);



var cards = $('.carddata');
b = true;
lis = new Map();
i = cards.size();
j = 0;
var map;
function exec(here){
    here.card = {
        "lat" : 0,
        "lon" : 0,
        "mail" : $(here).find(".mail")[0].innerHTML,
        "city" : $(here).find(".city")[0].innerHTML,
        "address" : $(here).find(".address")[0].innerHTML,
        "postal_code" : $(here).find(".postal_code")[0].innerHTML,
        "diplome" : $(here).find(".diplome")[0].innerHTML,
        "diplome_domaine" : $(here).find(".diplome_domaine")[0].innerHTML,
        "phone_number" : $(here).find(".phone_number")[0].innerHTML,
        "price" : $(here).find(".price")[0].innerHTML,
        "lien" : $(here).find(".lien")[0].innerHTML
    }
    var web ='https://nominatim.openstreetmap.org/search?format=json&limit=1&q=';
    here.gpsOb = $(here).find(".gps");
    $.getJSON(web + here.card["address"] + " " + here.card["city"] + ","+ here.card["postal_code"], function(result) { //On limite au résultat le plus pertinent
        if ($.isEmptyObject(result)) {
        }else{
            $.each(result, function(key, val) {
                here.card["lat"] = val.lat;
                here.card["lon"]  = val.lon;
                gps = val.lat + ";" + val.lon;
                here.gpsOb[0].innerHTML = gps;
                if (!lis.has(gps)){
                    lis.set(gps, [here.card]);
                } else {
                    lis.get(gps).push(here.card);
                }
            });
        };
        j++;
        if (j >= i){
            long = 0;
            lat = 0;
            function logMapElements(valeur, cle, map) {
                lat = cle.split(";")[0];
                long = cle.split(";")[1];
            }
            lis.forEach(logMapElements);


            if ($('.notfound').size() > 0){
                map = L.map('mapid').setView([lat, long], 6);
            } else {

                map = L.map('mapid').setView([lat, long], 10);
            }

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            function logMapElements2(valeur, cle, map2) {
                lat = cle.split(";")[0];
                long = cle.split(";")[1];
                tmp = "";
                for (var i = 0; i < valeur.length; i++){
                    tmp += valeur[i]["diplome"] + " ("+valeur[i]["diplome_domaine"]+")<br \>"+
                        valeur[i]["address"] + " "+ valeur[i]["city"] + " " + valeur[i]["postal_code"] + "<br \>"+
                        valeur[i]["mail"] + " " + valeur[i]["phone_number"] + "<br \>" +
                        valeur[i]["price"]+ "€"+  "<br \> " + "<a type='button' href=" + valeur[i]["lien"] + ">En savoir plus/S'inscrire</a>" + "<br \><br \>"
                }
                L.marker([lat, long]).addTo(map)
                    .bindPopup(tmp)
                    .openPopup();
            }
            lis.forEach(logMapElements2);
        }

    });
}
cards.each(function (p){
    exec(this);
})

$('.buttoncentrer').click(function() {
    gps = $(this.parentNode.parentNode.parentNode.parentNode).find('.gps')[0].innerHTML.split(";");
    map.setView(gps, 17);
})  
