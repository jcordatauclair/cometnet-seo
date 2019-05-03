console.log('##################### SEOecho\echoSEOBundle > default.js');

jQuery.noConflict();
(function ($) {
  $(function () {
    $.ajax({
      url: "/crawlGooglePython",
      method: "POST",
      data: { motsCles: $('#req').val() },
      error: function(msg) {
        $("#mainResultats").html('<h1>ERROR</h1>');
      },
      success: function(dossierCrawl) {
        $.ajax({
          url: "/parsingResultats/"+dossierCrawl,
          error: function(msg) {
            $("#mainResultats").html('<h1>ERROR</h1>');
          },
          success: function(resultatsGoogleAsJson) {
            $("#loader #loaderGoogle").hide();
            $("#loader #loaderApprentissageEcho").show();

            $.ajax({
              url: "/apprentissageEcho",
              method: "POST",
              data: { motsCles: $('#req').val()/*, resultatsGoogle: resultatsGoogleAsJson */},
              error: function(msg) {
                $("#mainResultats").html('<h1>ERROR</h1>');
              },
              success: function(msg) {
                $("#loader #loaderApprentissageEcho").hide();
                $("#mainResultats").append('<h2>Apprentissage OK</h2>'+msg);
              },
              complete: function(msg) {
                $("#loader").hide();
                $("#mainResultats, #lienNouvelleRequete").show();
              }
            });
          }
        });
      }
    });
  });
})(jQuery);
