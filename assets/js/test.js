function testAjax(msg){
  let dat = new FormData();
  dat.append("msg",msg);

  getAjax("echo",dat,(r) => console.log(r));
}
