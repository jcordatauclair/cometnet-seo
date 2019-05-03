jQuery.noConflict();
(function($) {

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    $(function() {

        /* Crawling des résultats Google pour la requête donnée (mots clés + url)*/
        $.ajax({
            url: "/app_dev.php/crawlGooglePython",
            method: "POST",
            data: {
                motsCles: $('#req').val(),
                url: $('#url').val()
            },

            /* Gestion d'erreur */
            error: function(msg) {
                $("#conteneurResultatsGlobaux").html('<h1>ERROR</h1>');
            },

            /* Apprentissage machine sur les balises SEO des résultats renvoyés */
            success: function(resultatsGoogleAsJson) {

                /* Affichage d'un loader */
                $("#loader #loaderGoogle").hide();
                $("#loader #loaderApprentissageEcho").show();

                /* SEO Echo */
                $.ajax({
                    url: "/app_dev.php/apprentissageEcho",
                    method: "POST",
                    data: {
                        motsCles: $('#req').val(),
                        url: $('#url').val()
                    },

                    /* Gestion d'erreur */
                    error: function(msg) {
                        $("#conteneurResultatsGlobaux").html('<h1>ERROR</h1>');
                    },

                    /* Affichage d'un loader */
                    success: function(msg) {
                        $("#loader #loaderApprentissageEcho").hide();
                    },

                    /* Affichage des résultats */
                    complete: function(msg) {
                        $("#loader").hide();
                        $("#mainResultats, #lienNouvelleRequete").show();


                        /* Affichage de graphes */

                        /* Options globales des graphes */
                        var optionsGlobalesGraph = {
                            textStyle: 'font-size:10px;',
                            textColor: '#aaa',
                            animationStep: getRandomInt(3, 6),
                            foregroundBorderWidth: 10,
                            backgroundBorderWidth: 10,
                            foregroundColor: "#ff9000"
                        };

                        /* Création du graphe de taux de performance */
                        optionsGraphTxPerf = optionsGlobalesGraph;
                        optionsGraphTxPerf.percent = parseFloat(msg.responseJSON.txPerf.replace(",", ".")) * 100;
                        optionsGraphTxPerf.text = "Tx performance";
                        $("#graphTxPerf").circliful(optionsGraphTxPerf);

                        /* Création du graphe d'indice de confiance' */
                        optionsGraphIndiceConf = optionsGlobalesGraph;
                        optionsGraphIndiceConf.percent = parseFloat(msg.responseJSON.posPrevue.indiceConf.replace(",", ".")) * 100;
                        optionsGraphIndiceConf.text = "Tx confiance";
                        $("#graphIndiceConf").circliful(optionsGraphIndiceConf);

                        /* Pour chaque prédicteur, construction du code html */
                        $.each(msg.responseJSON.predicteurs, function(key, value) {
                            if ((key % 2) == 0) var classLigne = 'odd';
                            else var classLigne = 'even';

                            var lignePredicateur = '';
                            lignePredicateur += '<div class="' + classLigne + '">';
                            lignePredicateur += '<div class="row">';
                            lignePredicateur += '<div class="col-xs-10">';
                            lignePredicateur += '<p class="labelRecommendation">Balise observ&eacute;e: ' + value.balise + '</p>';
                            lignePredicateur += '<p class="labelRecommendation">Terme(s) &agrave; valoriser: ' + value.terme + '</p>';
                            lignePredicateur += '<a class="toggle-trigger">En savoir +</a>';
                            lignePredicateur += '</div>';
                            lignePredicateur += '<div id="predicateur' + key + '" class="col-xs-2">';
                            lignePredicateur += '<div id="graphPredicateur' + key + '"></div>';
                            lignePredicateur += '</div>';
                            lignePredicateur += '</div>';
                            lignePredicateur += '<div class="row">';
                            lignePredicateur += '<div class="col-xs-12 toggle-wrap">';
                            lignePredicateur += '<p class="labelRecommendation">Valeurs observ&eacute;e sur votre page pour cette balise:</p>';
                            var myRegex = new RegExp('"' + value.terme + '"', "i");
                            $.each(value.balisesPageCible, function(key2, value2) {
                                if (myRegex.test(value2.terme)) lignePredicateur += '<font color=#FF9000><strong>' + value2.terme + '</strong></font>, ';
                                else lignePredicateur += value2.terme + ', ';
                            });

                            lignePredicateur += '</div>';
                            lignePredicateur += '</div>';
                            lignePredicateur += '</div>';

                            /* Injection du HTML dans le DOM */
                            $("#predicateurs").append($(lignePredicateur));

                            /* Création du graphe associé au prédicteur */
                            optionsGraphPredicteur = optionsGlobalesGraph;
                            optionsGraphPredicteur.percent = value.pourcentageSimilitude;
                            optionsGraphPredicteur.text = "";
                            optionsGraphPredicteur.percentageTextSize = 25;
                            optionsGraphPredicteur.iconPosition = 'middle';
                            $("#graphPredicateur" + key).circliful(optionsGraphPredicteur);
                        });

                        $(".toggle-trigger").click(function() {
                            $(this).parent().parent().parent().find('.toggle-wrap').toggle('slow');
                        });
                    }
                });
            }
        });
    });
})(jQuery);
