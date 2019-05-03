jQuery.noConflict();
(function($) {

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    $(function() {

        /* Crawling des résultats Google pour la requête donnée (mots clés +
           url) */
        $.ajax({
            url: "/app_dev.php/crawlGooglePython",
            method: "POST",
            data: {
                motsCles: $('#req').val(),
                url: $('#url').val()
            },

            /* Gestion d'erreur */
            error: function(msg) {
                console.log(msg.responseJSON);
                $("#conteneurResultatsGlobaux").html('<h1>CRAWLING ERROR</h1>');
            },

            /* Apprentissage machine sur les balises SEO des résultats
               renvoyés */
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
                        console.log(msg);
                        $("#conteneurResultatsGlobaux").html('<h1>MACHINE LEARNING ERROR</h1>');
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

                            var lignePredicteur = '';
                            lignePredicteur += '<div class="' + classLigne + '">';
                            lignePredicteur += '<div class="row">';
                            lignePredicteur += '<div class="col-xs-10">';
                            lignePredicteur += '<p class="labelRecommendation">Balise observ&eacute;e: ' + value.balise + '</p>';
                            lignePredicteur += '<p class="labelRecommendation">Terme(s) &agrave; valoriser: ' + value.terme + '</p>';
                            lignePredicteur += '<a class="toggle-trigger">En savoir +</a>';
                            lignePredicteur += '</div>';
                            lignePredicteur += '<div id="Predicteur' + key + '" class="col-xs-2">';
                            lignePredicteur += '<div id="graphPredicteur' + key + '"></div>';
                            lignePredicteur += '</div>';
                            lignePredicteur += '</div>';
                            lignePredicteur += '<div class="row">';
                            lignePredicteur += '<div class="col-xs-12 toggle-wrap">';
                            lignePredicteur += '<p class="labelRecommendation">Valeurs observ&eacute;e sur votre page pour cette balise:</p>';
                            var myRegex = new RegExp('"' + value.terme + '"', "i");
                            $.each(value.balisesPageCible, function(key2, value2) {
                                if (myRegex.test(value2.terme)) lignePredicteur += '<font color=#FF9000><strong>' + value2.terme + '</strong></font>, ';
                                else lignePredicteur += value2.terme + ', ';
                            });

                            lignePredicteur += '</div>';
                            lignePredicteur += '</div>';
                            lignePredicteur += '</div>';

                            /* Injection du HTML dans le DOM */
                            $("#Predicteurs").append($(lignePredicteur));

                            /* Création du graphe associé au prédicteur */
                            optionsGraphPredicteur = optionsGlobalesGraph;
                            optionsGraphPredicteur.percent = value.pourcentageSimilitude;
                            optionsGraphPredicteur.text = "";
                            optionsGraphPredicteur.percentageTextSize = 25;
                            optionsGraphPredicteur.iconPosition = 'middle';
                            $("#graphPredicteur" + key).circliful(optionsGraphPredicteur);
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
"use strict";
! function(a) {
    a.fn.circliful = function(b, c) {
        var d = a.extend({
            foregroundColor: "#3498DB",
            backgroundColor: "#ccc",
            pointColor: "none",
            fillColor: "none",
            foregroundBorderWidth: 15,
            backgroundBorderWidth: 15,
            pointSize: 28.5,
            fontColor: "#aaa",
            percent: 75,
            animation: 1,
            animationStep: 5,
            icon: "none",
            iconSize: "30",
            iconColor: "#ccc",
            iconPosition: "top",
            target: 0,
            start: 0,
            showPercent: 1,
            percentageTextSize: 22,
            textAdditionalCss: "",
            targetPercent: 0,
            targetTextSize: 17,
            targetColor: "#2980B9",
            text: null,
            textStyle: null,
            textColor: "#666",
            multiPercentage: 0,
            percentages: null,
            textBelow: !1,
            noPercentageSign: !1,
            replacePercentageByText: null,
            halfCircle: !1,
            animateInView: !1,
            decimals: 0,
            alwaysDecimals: !1
        }, b);
        return this.each(function() {
            function y() {
                var a = window.setInterval(function() {
                    r >= x ? (window.clearInterval(a), t = 1, "function" == typeof c && c.call(this)) : (r += s, u += v), r / 3.6 >= e && 1 == t && (r = 3.6 * e), u > d.target && 1 == t && (u = d.target), null == d.replacePercentageByText && (w = d.halfCircle ? parseFloat(100 * r / 360 * 2) : parseFloat(100 * r / 360), w = w.toFixed(d.decimals), !d.alwaysDecimals && (0 == e || e > 1 && 1 != t) && (w = parseInt(w))), o.attr("stroke-dasharray", r + ", 20000"), 1 == d.showPercent ? p.find(".number").text(w) : (p.find(".number").text(u), p.find(".percent").text(""))
                }.bind(o), q)
            }

            function z() {
                var b = navigator.userAgent.toLowerCase().indexOf("webkit") != -1 ? "body" : "html",
                    c = a(b).scrollTop(),
                    d = c + a(window).height(),
                    e = Math.round(o.offset().top),
                    f = e + o.height();
                return e < d && f > c
            }

            function A() {
                o.hasClass("start") || z(o) && (o.addClass("start"), setTimeout(y, 250))
            }

            function B(b, c) {
                a.each(b, function(a, d) {
                    a.toLowerCase() in c && (b[a] = c[a.toLowerCase()])
                })
            }
            var b = a(this);
            B(d, b.data());
            var j, k, l, e = d.percent,
                f = 83,
                g = 100,
                h = 100,
                i = 100,
                m = d.backgroundBorderWidth;
            if (d.halfCircle ? "left" == d.iconPosition ? (g = 80, f = 100, i = 117, h = 100) : d.halfCircle && (f = 80, h = 100) : "bottom" == d.iconPosition ? (f = 124, h = 95) : "left" == d.iconPosition ? (g = 80, f = 110, i = 117) : "middle" == d.iconPosition ? 1 == d.multiPercentage ? "object" == typeof d.percentages ? m = 30 : (f = 110, k = '<g stroke="' + ("none" != d.backgroundColor ? d.backgroundColor : "#ccc") + '" ><line x1="133" y1="50" x2="140" y2="40" stroke-width="2"  /></g>', k += '<g stroke="' + ("none" != d.backgroundColor ? d.backgroundColor : "#ccc") + '" ><line x1="140" y1="40" x2="200" y2="40" stroke-width="2"  /></g>', i = 228, h = 47) : (f = 110, k = '<g stroke="' + ("none" != d.backgroundColor ? d.backgroundColor : "#ccc") + '" ><line x1="133" y1="50" x2="140" y2="40" stroke-width="2"  /></g>', k += '<g stroke="' + ("none" != d.backgroundColor ? d.backgroundColor : "#ccc") + '" ><line x1="140" y1="40" x2="200" y2="40" stroke-width="2"  /></g>', i = 170, h = 35) : "right" == d.iconPosition && (g = 120, f = 110, i = 80), d.targetPercent > 0 && (h = 95, k = '<g stroke="' + ("none" != d.backgroundColor ? d.backgroundColor : "#ccc") + '" ><line x1="75" y1="101" x2="125" y2="101" stroke-width="1"  /></g>', k += '<text text-anchor="middle" x="' + i + '" y="120" style="font-size: ' + d.targetTextSize + 'px;" fill="' + d.targetColor + '">' + d.targetPercent + (d.noPercentageSign && null == d.replacePercentageByText ? "" : "%") + "</text>", k += '<circle cx="100" cy="100" r="69" fill="none" stroke="' + d.backgroundColor + '" stroke-width="3" stroke-dasharray="450" transform="rotate(-90,100,100)" />', k += '<circle cx="100" cy="100" r="69" fill="none" stroke="' + d.targetColor + '" stroke-width="3" stroke-dasharray="' + 3.6 * d.targetPercent + ', 20000" transform="rotate(-90,100,100)" />'), null != d.text && (d.halfCircle ? d.textBelow ? k += '<text text-anchor="middle" x="100" y="120" style="' + d.textStyle + '" fill="' + d.textColor + '">' + d.text + "</text>" : 0 == d.multiPercentage ? k += '<text text-anchor="middle" x="100" y="115" style="' + d.textStyle + '" fill="' + d.textColor + '">' + d.text + "</text>" : 1 == d.multiPercentage && (k += '<text text-anchor="middle" x="228" y="65" style="' + d.textStyle + '" fill="' + d.textColor + '">' + d.text + "</text>") : d.textBelow ? k += '<text text-anchor="middle" x="100" y="190" style="' + d.textStyle + '" fill="' + d.textColor + '">' + d.text + "</text>" : 0 == d.multiPercentage ? k += '<text text-anchor="middle" x="100" y="115" style="' + d.textStyle + '" fill="' + d.textColor + '">' + d.text + "</text>" : 1 == d.multiPercentage && (k += '<text text-anchor="middle" x="228" y="65" style="' + d.textStyle + '" fill="' + d.textColor + '">' + d.text + "</text>")), "none" != d.icon && (l = '<text text-anchor="middle" x="' + g + '" y="' + f + '" class="icon" style="font-size: ' + d.iconSize + 'px" fill="' + d.iconColor + '">&#x' + d.icon + "</text>"), d.halfCircle) {
                var n = 'transform="rotate(-180,100,100)"';
                b.addClass("svg-container").append(a('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 194 186" class="circliful">' + k + '<clipPath id="cut-off-bottom"> <rect x="100" y="0" width="100" height="200" /> </clipPath><circle cx="100" cy="100" r="57" class="border" fill="' + d.fillColor + '" stroke="' + d.backgroundColor + '" stroke-width="' + m + '" stroke-dasharray="360" clip-path="url(#cut-off-bottom)" transform="rotate(-90,100,100)" /><circle class="circle" cx="100" cy="100" r="57" class="border" fill="none" stroke="' + d.foregroundColor + '" stroke-width="' + d.foregroundBorderWidth + '" stroke-dasharray="0,20000" ' + n + ' /><circle cx="100" cy="100" r="' + d.pointSize + '" fill="' + d.pointColor + '" clip-path="url(#cut-off-bottom)" transform="rotate(-90,100,100)" />' + l + '<text class="timer" text-anchor="middle" x="' + i + '" y="' + h + '" style="font-size: ' + d.percentageTextSize + "px; " + j + ";" + d.textAdditionalCss + '" fill="' + d.fontColor + '"><tspan class="number">' + (null == d.replacePercentageByText ? 0 : d.replacePercentageByText) + '</tspan><tspan class="percent">' + (d.noPercentageSign || null != d.replacePercentageByText ? "" : "%") + "</tspan></text>"))
            } else b.addClass("svg-container").append(a('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 194 186" class="circliful">' + k + '<circle cx="100" cy="100" r="57" class="border" fill="' + d.fillColor + '" stroke="' + d.backgroundColor + '" stroke-width="' + m + '" stroke-dasharray="360" transform="rotate(-90,100,100)" /><circle class="circle" cx="100" cy="100" r="57" class="border" fill="none" stroke="' + d.foregroundColor + '" stroke-width="' + d.foregroundBorderWidth + '" stroke-dasharray="0,20000" transform="rotate(-90,100,100)" /><circle cx="100" cy="100" r="' + d.pointSize + '" fill="' + d.pointColor + '" />' + l + '<text class="timer" text-anchor="middle" x="' + i + '" y="' + h + '" style="font-size: ' + d.percentageTextSize + "px; " + j + ";" + d.textAdditionalCss + '" fill="' + d.fontColor + '"><tspan class="number">' + (null == d.replacePercentageByText ? 0 : d.replacePercentageByText) + '</tspan><tspan class="percent">' + (d.noPercentageSign || null != d.replacePercentageByText ? "" : "%") + "</tspan></text>"));
            var o = b.find(".circle"),
                p = b.find(".timer"),
                q = 30,
                r = 0,
                s = d.animationStep,
                t = 0,
                u = 0,
                v = 0,
                w = e,
                x = 3.6 * e;
            d.halfCircle && (x = 3.6 * e / 2), null != d.replacePercentageByText && (w = d.replacePercentageByText), d.start > 0 && d.target > 0 && (e = d.start / (d.target / 100), v = d.target / 100), 1 == d.animation ? d.animateInView ? a(window).scroll(function() {
                A()
            }) : y() : (o.attr("stroke-dasharray", x + ", 20000"), 1 == d.showPercent ? p.find(".number").text(w) : (p.find(".number").text(d.target), p.find(".percent").text("")))
        })
    }
}(jQuery);
