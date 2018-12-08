

<!doctype html>
<html>
<head>
    <script src="https://api.mapy.cz/loader.js"></script>
    <script>Loader.load()</script>
</head>

<body>

<h1 style="text-align: center">KONTAKT</h1>

<div class="flex-wrap-map">

<div class="card"  id="mapa"></div>


    <section style="text-align: left"  class="card">

        <address style="font-size: large">
            Lukášovo, s. r. o.<br>
            1997 Vyskytná <br>
            CZ<br>
            +420 123 456 789<br>
            E-mail: <a href="mailto: st52557@student.upce.cz">
                st52557@student.upce.cz</a><br>
        </address>
    </section>

</div>















<script type="text/javascript">
		var stred = SMap.Coords.fromWGS84(15.3630992, 49.4334075);
		var mapa = new SMap(JAK.gel("mapa"), stred, 9);
		mapa.addDefaultLayer(SMap.DEF_BASE).enable();


		var center = SMap.Coords.fromWGS84(15.3630992, 49.4334075);
var m = new SMap(JAK.gel("mapa"), center, 9);
m.addDefaultLayer(SMap.DEF_BASE).enable();
m.addDefaultControls();

var layer = new SMap.Layer.Marker();
m.addLayer(layer);
layer.enable();

var card = new SMap.Card();
card.getHeader().innerHTML = "<strong>Zde nás najdete</strong>";
card.getBody().innerHTML = "49.4334075N, 15.3630992E";

var options = {
    title: "Dobré ráno"
};
var marker = new SMap.Marker(center, "myMarker", options);
marker.decorate(SMap.Marker.Feature.Card, card);
layer.addMarker(marker);


	</script>



</body>
</html>