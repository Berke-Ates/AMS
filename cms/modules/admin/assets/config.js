$('table td[data-editable="true"]').on('change', function(evt, val){
    let dat = new FormData();
    dat.append("mod",$(this).data().mod);
    dat.append("path",JSON.stringify($(this).data().path));
    dat.append("val",val);

    getAjax("admin_editConfig",dat,(r) => showAjaxResponse(r.success, r.msg, "#adminCardAlert" + $(this).data().name));
		return true;
});
