jQuery.noConflict();
(function($) {

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    $(function() {

        $("#header").hide();

        // Script Python : crawling des résultats Google pour la requête donnée
        $.ajax({
            url: "/app_dev.php/crawlGooglePython",
            method: "POST",
            data: {
                motsCles: $('#req').val(),
                url: $('#url').val()
            },

            // Gestion d'erreur
            error: function(msg) {
                console.log(msg);
                $("#conteneurResultatsGlobaux").html('<p>Une erreur est survenue (scraping)</p>');
            },

            success: function(resultatsGoogleAsJson) {

                // Affichage d'un loader & masquage du header
                $("#header").hide();
                $("#loader #cs-loader").show();

                // Script PHP : apprentissage machine à partir des données renvoyées précédemment
                $.ajax({
                    dataType: 'json',
                    url: "/app_dev.php/apprentissageEcho",
                    method: "POST",
                    data: {
                        motsCles: $('#req').val(),
                        url: $('#url').val()
                    },

                    // Gestion d'erreur
                    error: function(msg) {
                        console.log(msg);
                        $("#conteneurResultatsGlobaux").html('<p>Une erreur est survenue (echo)</p>');
                    },

                    // Affichage d'un loader & masquage du header
                    success: function(msg) {
                        console.log(msg);
                        $("#header").hide();
                        $("#loader #cs-loader").show();
                    },

                    // Affichage des résultats & masquage du loader
                    complete: function(msg) {
                        $("#loader").hide();
                        $("#header").show();
                        $("#mainResultats, #lienNouvelleRequete").show();

                        // Affichage de la position estimée
                        var position = '';
                        position += '<b>Position estimée :</b> ';
                        position += '<span>';
                        position += msg.responseJSON.posPrevue.position.slice(0, -1);
                        position += '</span>';
                        $('#position').append($(position));

                        // Affichage des meilleures URLs
                        var bestURLs = '';
                        bestURLs += '<div class="titreBestURLs">';
                        bestURLs += 'Les pages les mieux classées : <br />';
                        bestURLs += '</div>';
                        $.each(msg.responseJSON.bestURLs, function(key, value) {
                            bestURLs += '<span>' + '</span>';
                            bestURLs += '<a target=_blank href="' + value + '" >';
                            if (value.length > 40) {
                                bestURLs += value.substring(0, 40) + '...';
                            } else {
                                bestURLs += value;
                            }
                            bestURLs += '</a><br />';
                        });
                        $('#bestURLs').append($(bestURLs));


                        //*************** Affichage de graphes ***************//

                        // Fonction chargée de calculer la couleur liée à un pourcentage donné
                        function getColorGraph(value) {
                            // pourcentage (entre 0 et 1)
                            var hue = (value * 120).toString(10);
                            return ["hsl(", hue, ", 90%, 70%)"].join("");
                        }

                        // Options globales des graphes
                        var optionsGlobalesGraph = {
                            textStyle: 'font-size:8px;',
                            textColor: '#aaa',
                            animationStep: getRandomInt(3, 6),
                            foregroundBorderWidth: 5,
                            backgroundBorderWidth: 5
                        };

                        // Création du graphe de taux de performance
                        optionsGraphTxPerf = optionsGlobalesGraph;
                        optionsGraphTxPerf.percent = parseFloat(msg.responseJSON.txPerf.replace(",", ".")) * 100;
                        optionsGraphTxPerf.text = 'Taux de performance';
                        optionsGraphTxPerf.foregroundColor = getColorGraph(parseFloat(msg.responseJSON.txPerf.replace(",", ".")));
                        $("#graphTxPerf").circliful(optionsGraphTxPerf);

                        // Création du graphe d'indice de confiance
                        optionsGraphIndiceConf = optionsGlobalesGraph;
                        optionsGraphIndiceConf.percent = parseFloat(msg.responseJSON.posPrevue.indiceConf.replace(",", ".")) * 100;
                        optionsGraphIndiceConf.text = 'Taux de confiance';
                        optionsGraphIndiceConf.foregroundColor = getColorGraph(parseFloat(msg.responseJSON.posPrevue.indiceConf.replace(",", ".")));
                        $("#graphIndiceConf").circliful(optionsGraphIndiceConf);

                        //********** Affichage des meilleurs termes **********//

                        // Fonction chargée de calculer la couleur liée à un score donné
                        function getColorBT(value) {
                            // score (entre 0 et l'infini)
                            var hue = ((1 - value * 0.03) * 120).toString(10);
                            return ["hsl(", hue, ", 90%, 80%)"].join("");
                        }

                        var groupePredicteurs = '';

                        // Balise "title"
                        groupePredicteurs += '<div class="row">';

                        groupePredicteurs += '<p class="labelBalise">';
                        groupePredicteurs += 'Titre (balise <b>title</b>) :';
                        groupePredicteurs += '</p>';

                        groupePredicteurs += '<div class="listePredicteurs">';
                        $.each(msg.responseJSON.predicteurs.title, function(key, value) {
                            groupePredicteurs += '<span class="unPredicteur"><code style="background-color:' + getColorBT(key) + ';">';
                            groupePredicteurs += '<b>' + value + '</b></code></span>';
                        });
                        groupePredicteurs += '</div>';

                        groupePredicteurs += '<form action="">';
                        groupePredicteurs += '<textarea name="titleModif" id="titleModif">';
                        var valeursUtilisateur = '';
                        $.each(msg.responseJSON.pageUtilisateur[0].title, function(key, value) {
                            valeursUtilisateur += value + ', ';
                        });
                        groupePredicteurs += valeursUtilisateur.slice(0, -2);
                        groupePredicteurs += '</textarea>';
                        groupePredicteurs += '</form>';

                        groupePredicteurs += '</div>';

                        // Mots-clés constituant l'URL
                        groupePredicteurs += '<div class="row">';

                        groupePredicteurs += '<p class="labelBalise">';
                        groupePredicteurs += 'URL (mots-clés présents dans le <b>lien</b>) :';
                        groupePredicteurs += '</p>';

                        groupePredicteurs += '<div class="listePredicteurs">';
                        $.each(msg.responseJSON.predicteurs.url, function(key, value) {
                            groupePredicteurs += '<span class="unPredicteur"><code style="background-color:' + getColorBT(key) + ';">';
                            groupePredicteurs += '<b>' + value + '</b></code></span>';
                        });
                        groupePredicteurs += '</div>';

                        groupePredicteurs += '<form action="">';
                        groupePredicteurs += '<textarea name="urlModif" id="urlModif">';
                        var valeursUtilisateur = '';
                        $.each(msg.responseJSON.pageUtilisateur[0].iurl, function(key, value) {
                            valeursUtilisateur += value + ', ';
                        });
                        groupePredicteurs += valeursUtilisateur.slice(0, -2);
                        groupePredicteurs += '</textarea>';
                        groupePredicteurs += '</form>';

                        groupePredicteurs += '</div>';

                        // Balise "h1"
                        groupePredicteurs += '<div class="row">';

                        groupePredicteurs += '<p class="labelBalise">';
                        groupePredicteurs += 'H1 (balise <b>h1</b>) :';
                        groupePredicteurs += '</p>';

                        groupePredicteurs += '<div class="listePredicteurs">';
                        $.each(msg.responseJSON.predicteurs.h1, function(key, value) {
                            groupePredicteurs += '<span class="unPredicteur"><code style="background-color:' + getColorBT(key) + ';">';
                            groupePredicteurs += '<b>' + value + '</b></code></span>';
                        });
                        groupePredicteurs += '</div>';

                        groupePredicteurs += '<form action="">';
                        groupePredicteurs += '<textarea name="h1Modif" id="h1Modif">';
                        var valeursUtilisateur = '';
                        $.each(msg.responseJSON.pageUtilisateur[0].h1, function(key, value) {
                            valeursUtilisateur += value + ', ';
                        });
                        groupePredicteurs += valeursUtilisateur.slice(0, -2);
                        groupePredicteurs += '</textarea>';
                        groupePredicteurs += '</form>';

                        groupePredicteurs += '</div>';

                        // Balise "h2"
                        groupePredicteurs += '<div class="row">';

                        groupePredicteurs += '<p class="labelBalise">';
                        groupePredicteurs += 'H2 (balise <b>h2</b>) :';
                        groupePredicteurs += '</p>';

                        groupePredicteurs += '<div class="listePredicteurs">';
                        $.each(msg.responseJSON.predicteurs.h2, function(key, value) {
                            groupePredicteurs += '<span class="unPredicteur"><code style="background-color:' + getColorBT(key) + ';">';
                            groupePredicteurs += '<b>' + value + '</b></code></span>';
                        });
                        groupePredicteurs += '</div>';

                        groupePredicteurs += '<form action="">';
                        groupePredicteurs += '<textarea name="h2Modif" id="h2Modif">';
                        var valeursUtilisateur = '';
                        $.each(msg.responseJSON.pageUtilisateur[0].h2, function(key, value) {
                            valeursUtilisateur += value + ', ';
                        });
                        groupePredicteurs += valeursUtilisateur.slice(0, -2);
                        groupePredicteurs += '</textarea>';
                        groupePredicteurs += '</form>';

                        groupePredicteurs += '</div>';

                        // Balise "h3"
                        groupePredicteurs += '<div class="row">';

                        groupePredicteurs += '<p class="labelBalise">';
                        groupePredicteurs += 'H3 (balise <b>h3</b>) :';
                        groupePredicteurs += '</p>';

                        groupePredicteurs += '<div class="listePredicteurs">';
                        $.each(msg.responseJSON.predicteurs.h3, function(key, value) {
                            groupePredicteurs += '<span class="unPredicteur"><code style="background-color:' + getColorBT(key) + ';">';
                            groupePredicteurs += '<b>' + value + '</b></code></span>';
                        });
                        groupePredicteurs += '</div>';

                        groupePredicteurs += '<form action="">';
                        groupePredicteurs += '<textarea name="h3Modif" id="h3Modif">';
                        var valeursUtilisateur = '';
                        $.each(msg.responseJSON.pageUtilisateur[0].h3, function(key, value) {
                            valeursUtilisateur += value + ', ';
                        });
                        groupePredicteurs += valeursUtilisateur.slice(0, -2);
                        groupePredicteurs += '</textarea>';
                        groupePredicteurs += '</form>';

                        groupePredicteurs += '</div>';

                        // Balise "strong"
                        groupePredicteurs += '<div class="row">';

                        groupePredicteurs += '<p class="labelBalise">';
                        groupePredicteurs += 'Strong (balise <b>strong</b>) :';
                        groupePredicteurs += '</p>';

                        groupePredicteurs += '<div class="listePredicteurs">';
                        $.each(msg.responseJSON.predicteurs.strong, function(key, value) {
                            groupePredicteurs += '<span class="unPredicteur"><code style="background-color:' + getColorBT(key) + ';">';
                            groupePredicteurs += '<b>' + value + '</b></code></span>';
                        });
                        groupePredicteurs += '</div>';

                        groupePredicteurs += '<form action="">';
                        groupePredicteurs += '<textarea name="strongModif" id="strongModif">';
                        var valeursUtilisateur = '';
                        $.each(msg.responseJSON.pageUtilisateur[0].strong, function(key, value) {
                            valeursUtilisateur += value + ', ';
                        });
                        groupePredicteurs += valeursUtilisateur.slice(0, -2);
                        groupePredicteurs += '</textarea>';
                        groupePredicteurs += '</form>';

                        groupePredicteurs += '</div>';

                        // Balise "a"
                        groupePredicteurs += '<div class="row">';

                        groupePredicteurs += '<p class="labelBalise">';
                        groupePredicteurs += 'Liens (balise <b>a</b>) :';
                        groupePredicteurs += '</p>';

                        groupePredicteurs += '<div class="listePredicteurs">';
                        $.each(msg.responseJSON.predicteurs.a, function(key, value) {
                            groupePredicteurs += '<span class="unPredicteur"><code style="background-color:' + getColorBT(key) + ';">';
                            groupePredicteurs += '<b>' + value + '</b></code></span>';
                        });
                        groupePredicteurs += '</div>';

                        groupePredicteurs += '<form action="">';
                        groupePredicteurs += '<textarea name="aModif" id="aModif">';
                        var valeursUtilisateur = '';
                        $.each(msg.responseJSON.pageUtilisateur[0].a, function(key, value) {
                            valeursUtilisateur += value + ', ';
                        });
                        groupePredicteurs += valeursUtilisateur.slice(0, -2);
                        groupePredicteurs += '</textarea>';
                        groupePredicteurs += '</form>';

                        groupePredicteurs += '</div>';

                        // Balise "img", propriété "alt"
                        groupePredicteurs += '<div class="row">';

                        groupePredicteurs += '<p class="labelBalise">';
                        groupePredicteurs += 'Images (balise <b>alt</b>) :';
                        groupePredicteurs += '</p>';

                        groupePredicteurs += '<div class="listePredicteurs">';
                        $.each(msg.responseJSON.predicteurs.alt, function(key, value) {
                            groupePredicteurs += '<span class="unPredicteur"><code style="background-color:' + getColorBT(key) + ';">';
                            groupePredicteurs += '<b>' + value + '</b></code></span>';
                        });
                        groupePredicteurs += '</div>';

                        groupePredicteurs += '<form action="">';
                        groupePredicteurs += '<textarea name="altModif" id="altModif">';
                        var valeursUtilisateur = '';
                        $.each(msg.responseJSON.pageUtilisateur[0].alt, function(key, value) {
                            valeursUtilisateur += value + ', ';
                        });
                        groupePredicteurs += valeursUtilisateur.slice(0, -2);
                        groupePredicteurs += '</textarea>';
                        groupePredicteurs += '</form>';

                        groupePredicteurs += '</div>';

                        // Balise "p"
                        groupePredicteurs += '<div class="row">';

                        groupePredicteurs += '<p class="labelBalise">';
                        groupePredicteurs += 'Espace sémantique :';
                        groupePredicteurs += '</p>';

                        groupePredicteurs += '<div class="listePredicteurs">';
                        groupePredicteurs += '<p class="espaceSemantique">';
                        var espaceSemantique = '';
                        $.each(msg.responseJSON.predicteurs.text, function(key, value) {
                            espaceSemantique += '<span><code style="background-color:' + getColorBT(key) + ';">';
                            espaceSemantique += '<b>' + value + '</b></code></span>';
                        });
                        groupePredicteurs += espaceSemantique;
                        groupePredicteurs += '</p>';
                        groupePredicteurs += '</div>';

                        groupePredicteurs += '<form action="">';
                        groupePredicteurs += '<textarea name="textModif" id="textModif">';
                        var valeursUtilisateur = '';
                        $.each(msg.responseJSON.pageUtilisateur[0].text, function(key, value) {
                            valeursUtilisateur += value + ', ';
                        });
                        groupePredicteurs += valeursUtilisateur.slice(0, -2);
                        groupePredicteurs += '</textarea>';
                        groupePredicteurs += '</form>';

                        groupePredicteurs += '</div>';

                        $("#predicteurs").append($(groupePredicteurs));

                        //************** Affichage des boutons ***************//

                        // Bouton pour recalculer la position de la page avec les changements opérés
                        var buttonModif = '';
                        buttonModif += '<button type="button" id="buttonModif">';
                        buttonModif += 'Calculer la nouvelle position';
                        buttonModif += '</button>';
                        buttonModif += '<span>&nbsp&nbsp&nbsp</span>';
                        $("#buttons").append($(buttonModif));

                        // Fonction de nettoyage de chaîne
                        function clean(myString) {
                            var newString = "";
                            if (myString && myString != "") {
                                newString = myString.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");
                                newString = newString.replace(/[!#$%&\\()\*+,\-\.\/:;<=>?@\[\\\]\^_`{|}~"]/g, "").replace("'", " ").replace(/\s{2,}/g, " ");
                            }
                            return newString;
                        }

                        var newTabResSiteCible;

                        // Création d'un nouvel objet JSON lorsque l'on clique sur le bouton
                        $("#buttonModif").click(function() {

                            // On récupère les paramètres entrés par l'utilisateur
                            var newTitle = $('textarea#titleModif').val();
                            var newUrl = $('textarea#urlModif').val();
                            var newH1 = $('textarea#h1Modif').val();
                            var newH2 = $('textarea#h2Modif').val();
                            var newH3 = $('textarea#h3Modif').val();
                            var newStrong = $('textarea#strongModif').val();
                            var newA = $('textarea#aModif').val();
                            var newAlt = $('textarea#altModif').val();
                            var newText = $('textarea#textModif').val();

                            // On génère un fichier JSON
                            var newArrayUserSite = new Object();
                            newArrayUserSite.title = [clean(newTitle)];
                            newArrayUserSite.iurl = [clean(newUrl)];
                            newArrayUserSite.h1 = [clean(newH1)];
                            newArrayUserSite.h2 = [clean(newH2)];
                            newArrayUserSite.h3 = [clean(newH3)];
                            newArrayUserSite.strong = [clean(newStrong)];
                            newArrayUserSite.a = [clean(newA)];
                            newArrayUserSite.alt = [clean(newAlt)];
                            newArrayUserSite.text = [clean(newText)];
                            newTabResSiteCible = JSON.stringify(newArrayUserSite);

                            // On appelle la fonction PHP permettant d'exécuter echo_pos avec le nouveau JSON
                            $('#modifResults').html('Calcul en cours ...').load('refreshEcho', {
                                "newJSON": newTabResSiteCible
                            });
                        });

                        // Bouton permettant d'exporter les données de chacun des champs de texte sous forme d'un fichier JSON
                        var buttonExport = '';
                        buttonExport += '<span>&nbsp&nbsp&nbsp</span>';
                        buttonExport += '<button type="button" id="buttonExport">';
                        buttonExport += 'Exporter les changements';
                        buttonExport += '</button>';
                        $("#buttons").append($(buttonExport));

                        $("#buttonExport").click(function() {
                            var downloadLink = document.createElement("a");
                            var blob = new Blob(["\ufeff", newTabResSiteCible]);
                            var url = URL.createObjectURL(blob);
                            downloadLink.href = url;
                            downloadLink.download = "data.json";

                            document.body.appendChild(downloadLink);
                            downloadLink.click();
                            document.body.removeChild(downloadLink);
                        });
                    }
                });
            }
        });
    });
})(jQuery);
