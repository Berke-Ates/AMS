function login(us,pw,msg){
  let dat = new FormData();
  dat.append("username", $("#" + us).val());
  dat.append("password", $("#" + pw).val());

  getAjax("admin_login",dat,(r) => {
    showAjaxResponse(r.success, r.msg, "#" + msg);
    if(r.success) setTimeout(() => location.reload(), 1000);
  });
}

function logout(){
  let dat = new FormData();
  getAjax("admin_logout",dat,(r) => location.reload());
}

function resetPW(us,msg){
  let dat = new FormData();
  dat.append("username", $("#" + us).val());

  getAjax("admin_resetPW",dat,(r) => {
    showAjaxResponse(r.success, r.msg, "#" + msg);
  });
}

function changePW(us,pw,pw2,key,msg){
  let dat = new FormData();
  dat.append("username", $("#" + us).val());
  dat.append("pw", $("#" + pw).val());
  dat.append("pw2", $("#" + pw2).val());
  dat.append("key", $("#" + key).val());

  getAjax("admin_changePW",dat,(r) => {
    showAjaxResponse(r.success, r.msg, "#" + msg);
    if(r.success) setTimeout(() => window.location.href = "?loc=admin", 1000);
  });
}

function delUser(id){
  let dat = new FormData();
  dat.append("id", id);
  getAjax("admin_delUser",dat,(r) => {
    showAjaxToast(r.success, r.msg);
    if(r.success) setTimeout(() => location.reload(), 1000);
  });
}

function editUser(id){
  let dat = new FormData();
  dat.append("id", id);
  dat.append("username", $("#editUserUsername" + id).val());
  dat.append("pw", $("#editUserPassword" + id).val());
  dat.append("email", $("#editUserEmail" + id).val());

  getAjax("admin_editUser",dat,(r) => {
    showAjaxToast(r.success, r.msg);
    if(r.success) setTimeout(() => location.reload(), 1000);
  });
}

function addUser(us, pw, email){
  let dat = new FormData();
  dat.append("username", $("#" + us).val());
  dat.append("pw", $("#" + pw).val());
  dat.append("email", $("#" + email).val());
  getAjax("admin_addUser",dat,(r) => {
    showAjaxToast(r.success, r.msg);
    if(r.success) setTimeout(() => location.reload() , 1000);
  });
}
