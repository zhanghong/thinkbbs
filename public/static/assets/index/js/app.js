/**
 * 验证并AJAX提交表单
 * @Author   zhanghong(Laifuzi)
 * @param    string             nameSelector 表单名称选择器
 * @param    array              rules        字段验证规则
 * @param    array              messages     字段提示信息
 * @return   null                            [description]
 */
 function validAndSubmitForm(nameSelector, rules, messages){
  var $form = $(nameSelector);
  if (!$form.length) {
    return false;
  }

  $form.validate({
    ignore: '',
    rules: rules,
    messages: messages,
    errorElement: 'div',
    errorClass: 'invalid-feedback',
    errorPlacement: function (error, element) {
      //去掉unobtrusive在form-control上的错误类
      element.removeClass('invalid-feedback');
      element.parent().append(error);
    },
    highlight: function (element) {
      //不让unobtrusive处理此事件
    },
    success: function (label) {
      //不让unobtrusive处理此事件
    },
    invalidHandler: function (event, validato) {
      $form.addClass('was-validated');
    },
    submitHandler: function(form){
      var validator = this;
      $.ajax({
        url: $form.attr("action"),
        type: $form.attr("method"),
        dataType: "JSON",
        data: $form.serialize(),
        success: function(response){
          if (response.code) {
            location.href = response["url"];
          } else {
            var data = response["data"];
            if (typeof(data['errors']) == "undefined") {
              alert(response["msg"]);
            } else {
              validator.showErrors(data['errors']);
            }
          }
        }, error: function () {
          alert("数据执行错误！");
        }
      });
    }
  });
}
