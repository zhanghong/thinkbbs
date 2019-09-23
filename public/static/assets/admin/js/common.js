var u = $(".active").parent('ul');

var uc = u.attr("class");//

if (uc == 'submenu') {
    u.parent().attr("class", "open active");
    if(u.parent().parent().attr('class')=='submenu'){
        u.parent().parent().parent().attr("class","open active");
    }
}

//弹出图片
function alert_img(url, width, heigth, title) {
    art.dialog({
        padding: 0,
        title: title,
        content: '<img src="' + url + '" width="' + width + '" height="' + heigth + '" />',
        lock: true
    });
}

//弹出确认操作
function alert_del(clickItem) {
    var $clickItem = $(clickItem);

    var url = $clickItem.data('del-href');
    if(typeof(url) == "undefined"){
        return false;
    }

    var data = [];
    var id = $clickItem.data('del-id');
    if(typeof(id) != "undefined"){
        data['id'] = id;
    }

    var art = dialog({
        title: '删除警告',
        content: '确认要删除吗？',
        okValue: '确定',
        ok: function () {
            this.title('提交中…');
            $.ajax({
                url: url,
                type: "DELETE",
                dataType: "JSON",
                data: data,
                success: function(data){
                    if(data["code"]){
                        // location.href = data["url"];
                    }else{
                        alert(data["msg"]);
                    }
                    location.reload();
                }, error: function () {
                    alert("数据执行错误！");
                }
            });
            return false;
        },
        cancelValue: '取消',
        cancel: function () {}
    });
    art.showModal();
}

function validAndSubmitForm(nameSelector, rules, messages){
    $form = $(nameSelector);
    if(!$form.length){
        return false;
    }

    $form.validate({
        ignore: '',
        rules: rules,
        messages: messages,
        errorElement: "span",
        errorClass: "help-inline input-error",
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form){
            var validator = this;
            $.ajax({
                url: $form.attr("action"),
                type: $form.attr("method"),
                dataType: "JSON",
                data: $form.serialize(),
                success: function(response){
                    if(response.code){
                        location.href = response["url"];
                    }else{
                        var data = response["data"];
                        if(typeof(data["errors"]) == "undefined"){
                            alert(response["msg"]);
                        }else{
                            console.log(data["errors"]);
                            validator.showErrors(data["errors"]);
                        }
                    }
                }, error: function () {
                    alert("数据执行错误！");
                }
            });
        }
    });
}