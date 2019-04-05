var exit=0; $(document).ready(function(){ $("#dop3").click(function () { $("#dop4").attr("checked", false); }); $("#dop4").click(function () { $("#dop3").attr("checked", false); });	setTimeout(Pereschet, 100); $(".DaySel").click(function () { $(this).toggleClass("DayEnabled"); });}); 
function ChangePrice(){ 
	var city=$("#city :selected").val(); var rid=$("#cat :selected").val(); if (rid!=0) {
	var ar=prices[rid].split(","); /* 1-самара, 2-новокуйбышевск*/ if (city==2) { ar[0]=Math.round(ar[0]*0.57); ar[1]=Math.round(ar[1]*0.68); ar[2]=Math.round(ar[2]*0.81); ar[3]=Math.round(ar[3]*0.83);  }
	$("#stoimost").html("0-30 символов: "+ar[0]+"р.<br>"+"31-50 символов: "+ar[1]+"р.<br>"+"51-100 символов: "+ar[2]+"р.<br>"+"101-150 символов: "+ar[3]+"р."); }
} 
  
function Pereschet(){ var sum=0; var city=$("#city :selected").val(); var rid=$("#cat :selected").val(); var txt1=$("#obj").val(); var txt2=$("#phone").val(); var len=txt1.length-(-txt2.length); $("#leng").html(len); if (len<151) { $("#leng").css("color","#333333");} else { $("#leng").css("color","red");} if (rid!=0) {  var ar=prices[rid].split(",");
if (city==2) { ar[0]=Math.round(ar[0]*0.57); ar[1]=Math.round(ar[1]*0.68); ar[2]=Math.round(ar[2]*0.81); ar[3]=Math.round(ar[3]*0.83); }
pr=ar[3]; if (len<150) { pr=ar[3]; } if (len<101) { pr=ar[2]; } if (len<51) { pr=ar[1]; } if (len<31) { pr=ar[0]; }} else { pr=0; }
if ($("#dop1").is(":checked")) { pr=pr*2.3;} if ($("#dop2").is(":checked")) { pr=pr*1.8;} if ($("#dop3").is(":checked")) { pr=pr*1.5;} if ($("#dop4").is(":checked")) { pr=pr*1.5;} sk=DatasProv(); $("#hs").val(sk); pr=pr*sk; skt=100-(sk*100);$("#sum").html(Math.round(pr)+"<b>р.</b>");$("#skidka").html(skt+"<b>%</b>");$("#sumall").html(Math.round(pr*exit)+"<b>р.</b>"); setTimeout(Pereschet, 100);}
function DatasProv() { al=0; exit=$(".DayEnabled").size(); cnt=1; $("#texit").html(exit); dts=""; var stp=0;  var datas=$('.DayEnabled'); for(var i=0; i<datas.length; i++) { el=datas[i]; dat=$(el).attr("title"); dts=dts+dat+","; }
if (exit>3) {var st=$(datas[0]).attr("title");  for(var i=1; i<datas.length; i++) { el=datas[i]; dat=$(el).attr("title"); if ((dat-st)!=7*24*60*60) { stp=1; } st=dat; }
if (stp!=1) { if (exit>3) { cnt=0.90; } if (exit>11) { cnt=0.85; } if (exit>26) { cnt=0.80; }}} $("#datss").val(dts); return(cnt); }
function SubmitForm(){ var rid=$("#cat :selected").val(); if (rid==0) { alert('Выберите раздел размещения объявления!'); return false;} var txt1=$("#obj").val(); if (txt1=="") { alert('Введите содержание объявления!'); return false;} var txt2=$("#phone").val(); if (txt2=="") { alert('Введите контактную информацию!'); return false;} if (exit==0) { alert('Выберите хотя бы одну дату выхода!'); return false;} return true;}

