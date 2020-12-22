
$(document).ready(function(){
  // Set idle time
  $(document).idleTimer(420000);
});

$(document).on("idle.idleTimer",function(event, elem, obj){
  window.location = "config/actions.php?q=deconnexion";
});
