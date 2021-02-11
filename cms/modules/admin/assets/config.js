function editConfig(mod, path, val){
  let dat = new FormData();
  dat.append("mod",mod);
  dat.append("path",JSON.stringify(path));
  dat.append("val",JSON.stringify(val));

  getAjax("admin_editConfig",dat,(r) => console.log(r));
}
