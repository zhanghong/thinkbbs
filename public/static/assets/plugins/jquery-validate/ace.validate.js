$.validator.setDefaults({
  errorElement: "span",
  errorClass: "help-block",
  highlight: function (element, errorClass, validClass) {
      $(element).closest('span.block').addClass('has-error');
  },
  unhighlight: function (element, errorClass, validClass) {
      $(element).closest('span.block').removeClass('has-error');
  },
  errorPlacement: function (error, element) {
      if (element.parent('span.block').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
          error.insertAfter(element.parent());
      } else {
          error.insertAfter(element);
      }
  }
});