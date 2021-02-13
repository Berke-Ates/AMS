$('table td[data-editable="true"]').on('change', function(evt, val){
    let dat = new FormData();
    dat.append("mod",$(this).data().mod);
    dat.append("path",JSON.stringify($(this).data().path));
    dat.append("val",val);

    getAjax("admin_editConfig",dat,(r) => showAjaxToast(r.success, r.msg));
		return true;
});
