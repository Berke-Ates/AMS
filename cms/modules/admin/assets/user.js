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
  getAjax("admin_logout",dat,(r) => {location.reload();});
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
    if(r.success) setTimeout(() => $("#userRow" + id).remove(), 750);
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
    if(r.success) setTimeout(() => $("#userEditModal" + id).modal('hide'), 1000);
  });
}

function addUser(us, pw, email){
  let dat = new FormData();
  dat.append("username", $("#" + us).val());
  dat.append("pw", $("#" + pw).val());
  dat.append("email", $("#" + email).val());
  getAjax("admin_addUser",dat,(r) => {
    showAjaxToast(r.success, r.msg);
    if(r.success) setTimeout(() => {
      addUserToTable(r.id,$("#" + us).val(),$("#" + email).val());
      $("#" + us).val("");
      $("#" + pw).val("")
      $("#" + email).val("")
    }, 750);
  });
}

function addUserToTable(id,username,email){
  content = '<tr valign="top" id="userRow' + id + '">';
  content += '<td>' + id + '</td>';
  content += '<td>' + username + '</td>';
  content += '<td>' + email + '</td>';
  content += '<td><a class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#userEditModal'+id+'"><i class="fas fa-pencil-alt"></i> Edit</a></td>';
  content += '<td><a class="btn btn-danger btn-sm btn-block" onclick="delUser(' + id + ')"><i class="fas fa-trash-alt"></i> Delete</a></td>';
  content += '</td></tr>';
  content += '<div class="modal fade" id="userEditModal' + id + '"><div class="modal-dialog"><div class="modal-content">';
  content += '<div class="modal-header"><h5 class="modal-title">Edit ' + username + '</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>';
  content += '<div class="modal-body"><form><div class="form-group"><label class="small mb-1" for="editUserUsername'+id+'">Username</label><input class="form-control py-4" id="editUserUsername'+id+'" type="text" placeholder="Enter username" value="'+username+'" data-entertrigger="userEditButton'+id+'"/></div>';
  content += '<div class="form-group"><label class="small mb-1" for="editUserPassword'+id+'">Password</label><input class="form-control py-4" id="editUserPassword'+id+'" type="password" placeholder="Enter password" data-entertrigger="userEditButton'+id+'" /></div>';
  content += '<div class="form-group"><label class="small mb-1" for="editUserEmail'+id+'">Email</label><input class="form-control py-4" id="editUserEmail'+id+'" type="email" placeholder="Enter email" value="'+email+'" data-entertrigger="userEditButton'+id+'"/></div></form></div>';
  content += '<div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="userEditButton' + id + '" onclick="editUser(\'' + id + '\')">Save changes</button></div></div></div></div>';
  $("#userTable").append(content);
}
