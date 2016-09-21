$(document).ready(function () {

  $('.table_id').DataTable();

  $('input[value="Réinitialiser"]').css( "display", "none" );

 

  $("#modifier_apparence").click(function(){
    if( $("#selecteur_theme").css('display') == 'none'){
          $("#selecteur_theme").slideDown();
    }else{
    $("#selecteur_theme").slideUp();
    }
  });
  $("#save_color").click(function(){
    $("#selecteur_theme").slideUp();
  });
  $("#close_theme").click(function(){
    $("#selecteur_theme").slideUp();
  });
  
  




});
