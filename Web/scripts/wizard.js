(function() {
  $('#exampleBasic').wizard({
    onFinish: function() {
      //swal("Operation effectuée, merci d'imprimer le Bordereau ! .");
      document.getElementById('form1').submit();
    }
  });
})();
