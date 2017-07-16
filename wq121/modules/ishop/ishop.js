old_s = 0;
var tsort=0;
function recalc_tcnt(){
	$.ajax({url: 'modules/ishop/ajax.php?act=recalc_tcnt',cache: false,success: function(html)
	{alert(html);}});
}
function tmp_send(){
	$.ajax({url: 'modules/ishop/ajax.php?act=tmp_send',cache: false,success: function(html)
	{alert(html);}});
}
function check_chpu(){
	$.ajax({url: 'modules/ishop/ajax.php?act=check_chpu',cache: false,success: function(html)
	{alert(html);}});
}
function reset_views(){
	$.ajax({url: 'modules/ishop/ajax.php?act=reset_views',cache: false,success: function(html)
	{alert(html);}});
}
function change_urls(id){
		$.ajax({url: 'modules/ishop/ajax.php?act=change_urls&cat_id='+id,cache: false,success: function(html){
			$('#mc6').html($('#mc6').html()+'<br>'+html);
		$('#mc5').show(); }});
}
function change_urlsprd(id){
		$.ajax({url: 'modules/ishop/ajax.php?act=change_urlsprd&cat_id='+id,cache: false,success: function(html){
			$('#mc6').html($('#mc6').html()+'<br>'+html);
		$('#mc5').show(); }});
}
function SetGp(el)
{
	hs.anchor = 'auto';
	hs.preserveContent = false;
   	hs.htmlExpand(el, { 
			objectType: 'ajax', width: '200',src: 'modules/ishop/ajax.php?act=qgp', 
		 wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Установить ГП'} );

}
function SaveGp(el)
{
	var sel='';
	sel=get_selects('boxcat');
	$.ajax({
		url: 'modules/ishop/ajax.php?act=save_gp&gp='+$('#igp').val()+'&cats='+sel,
		cache: false,
		success: function (html){
			if(parseInt(html) > 0)
			{
				ex=hs.getExpander();
				if(ex!=null){ex.close();}
				
			} else $('#gpmes').html(html);
		}
	});
	return false;
}


function change_urlt(id){
	if (!(id>0)){
		if (!(tinymce.activeEditor === null)) { 
		var text = encodeURI(tinyMCE.activeEditor.getContent()); 
		tinymce.activeEditor = null; 
		}
		var frem = $('#cat_frm').serialize() + '&cat_desc1=' + text; 
		var res='';
		$.ajax({
			url: 'modules/ishop/ajax.php?act=save_cat',
			cache: false,
			type:'POST',
			data: frem,
			success: function (html){
				if (html.search('save_ok')!=-1) {
					res=html.replace('save_ok','');
					$('#tid').html('ID:'+res+' ');
					$('#cid').val(res);	
					
					$.ajax({url:'modules/ishop/ajax.php?act=change_urlt&cat_id='+res,cache: false,success: function(html){
					if (html!='Err'){
						$('#vlink').val(html);							
					} else{
						$('#vlink').val('Ошибка!');	
					}
					}});
					
				} else {
					eval(html);
					return;
				}
			}
		});
		
	}else{
		$.ajax({url:'modules/ishop/ajax.php?act=change_urlt&cat_id='+id,cache: false,success: function(html){
		if (html!='Err'){
			$('#vlink').val(html);	
			$('#btn55').prop('disabled',false);
		} else{
			$('#vlink').val('Ошибка!');	
		}
		}});
	}
}


function save_cat(id){
	if (!(tinymce.activeEditor === null)) { 
	var text = encodeURIComponent(tinyMCE.activeEditor.getContent()); 
	tinymce.activeEditor = null; 
	}
	var frem = $('#cat_frm').serialize() + '&cat_desc1=' + text; 
	$.ajax({
		url: 'modules/ishop/ajax.php?act=save_cat',
		cache: false,
		type:'POST',
		data: frem,
		success: function (html){
			if (html.search('save_ok')!=-1) {
				$('#mc6').html($('#mc6').html()+'<br>Успешно сохранено.')
				$('#mc5').show();
			} else {
				eval(html);
			}
		}
	});

}
function mtcl(event){
	event = event || window.event
	var clickedElem = event.target || event.srcElement
	tel=clickedElem.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
     if (hasClass(clickedElem, 'iclc')){
		setaclose(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'check')){
		btn_state(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'idel')){
		delete_r(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'iop')){
		if (tel.indexOf('prd_row_')==0){
			gotourl('include.php?place=ishop#update_prd_html('+id+')');
		}
		if (tel.indexOf('cat_row_')==0){
			update_cat_html(id);
		}
		return;
	}
    if (hasClass(clickedElem, 'dir_icon')){
		if (tel.indexOf('cat_row_')==0){
			if (!(event.ctrlKey)){
				gotourl('include.php?place=ishop#open_sub_cats('+id+',1)');
			}else{
				gotourl('include.php?place=ishop#update_cat_html('+id+')');
			}
		}
		return;
	}
    if (hasClass(clickedElem, 'cat_ttl')){
    	if (hasClass(clickedElem, 'src2')){
			id=clickedElem.id;
		}
		if (tel.indexOf('prd_row_')==0){
			gotourl('include.php?place=ishop#update_prd_html('+id+')');
		}
		return;
	}	
    if (hasClass(clickedElem, 'enb') || hasClass(clickedElem, 'vsb')){
		on_off(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'acl') ){
		on_off_acl(clickedElem);
		return;
	}
}
function btn_state(el){
	cd=$("input[name^='box']:checked").length;
		$('#btncop').prop('disabled',!(cd>0));
		$('#btnmv').prop('disabled',!(cd>0));
		$('#btndel').prop('disabled',!(cd>0));
	cp=$("input[name^='boxprd']:checked").length;
	cc=$("input[name^='boxcat']:checked").length;
		$('#btncon').prop('disabled',!(cp>1));
		$('#btnpar').prop('disabled',!(cc>0));
		$('#btngp').prop('disabled',!(cc>0));
		$('#btnnw').prop('disabled',!(cp>0));
		$('#btnsp').prop('disabled',!(cp>0));
}
function open_prdinord(page){
	if($('#mc3').length){
		$.ajax({url: 'modules/ishop/ajax.php?act=open_prdinord&page='+page,cache: false,
				success: function(html){$('#mc3').html(html);}});
	}
}
function set_pos(el) {
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	type=tel.substr(0,3);
	oval = el.value;
	if (el.value=='...'){
		return false;
	}
	el.value = '...';
	$.ajax({
			url: 'modules/ishop/ajax.php?act=sort_cats' + '&id=' + id + '&value=' + oval + '&type=' + type,
			cache: false,
			success: function(html){
				el.value = parseInt(html);
			}
		});
}
function mtdc(el){
	tel=el.parentElement.parentElement.id;
	if (tel.indexOf('prd_row_')==0){
		tel=tel.substr(8,tel.length-8);
		gotourl('include.php?place=ishop#update_prd_html('+tel+')');
	}
	if (tel.indexOf('cat_row_')==0){
		tel=tel.substr(8,tel.length-8);
		event = event || window.event;
		if ($(el).hasClass('dir_icon') && !(event.ctrlKey)){
			gotourl('include.php?place=ishop#open_sub_cats('+tel+',1)');
		}else{
			gotourl('include.php?place=ishop#update_cat_html('+tel+')');
		}
	}
	return false;
}
function rec_cat_spl(vid){
	var g1 = document.getElementById('cat_s_' + old_s);
	var g2 = document.getElementById('cat_s_' + vid);
	if(g1) g1.style.display = "none";
	if(g2) g2.style.display = "block";
  	$('#rt1 #'+old_s).children('.Trc').removeClass("hig");
	old_s = vid;
  	$('#rt1 #'+vid).children('.Trc').addClass("hig");
	if(!$('#cat_s_' + vid).length){
		$.ajax({url: 'include.php?place=ishop&action=cr_ajax_cats&id=' + vid + '&prd_id=' + $('#prd_id').val(),
				cache: false,
				success: function(html){
					$('.recom_prdds').append(html);
					$('div[id^="cat_s_"]').hide();
					$('#cat_s_' + vid).show();
				}
			});
	}
}
function update_cat_html(id){
	if($('#mc4').length){
		$.ajax({url: 'modules/ishop/ajax.php?act=update_cat_html' + '&id=' + id,cache: false,
				success: function(html){
					if ($('#mc4').html().length==0){
						$('#mc4').html($('#mc3').html());
					}
					$('#mc3').html(html);
				}});
	}
}
function update_prd_html(id){
	if($('#mc3').length){
		$.ajax({url: 'modules/ishop/ajax.php?act=update_prd_html' + '&id=' + id + '&cat=' + $('#curent_cat').val(),cache: false,success: function(html){$('#mc3').html(html);scroll_top();}});
	}
}
var pid=0;
var pel=undefined;
function readtree(el,id, st){
	pid=id;
	pel=el;
	if(st==undefined){st=11;}
	hs.preserveContent = false;
	hs.Expander.prototype.onAfterExpand = function (sender) {
		$('#tre0 #'+id).parents('li').removeClass('TEc').addClass('TEo');
	 	destination = $('#tre0 #'+id).offset().top-$('.highslide-body').offset().top-140;
	  	$('#tre0 #'+id).children('.Trc').addClass("hig");
	  	$('.highslide-body').parent().animate({ scrollTop: destination}, 500 ); 
		return true;};
	hs.htmlExpand(el, {anchor:'top left',
		objectType: 'ajax', width: '600',height:'350',src: 'modules/ishop/ajax.php?act=readtree&st='+st,
		wrapperClassName: 'titlebar',targetX: null, targetY: 'pexp 30px', headingText: 'Дерево'});
}
function changepath(el,id){
	if (id==undefined){
		id=11;
	}
	hs.Expander.prototype.onBeforeExpand = function (sender) {
		$('#tre0 #'+id).parents('li').removeClass('TEc').addClass('TEo');
//	 	destination = $('#tre0 #'+id).offset().top-$('.highslide-body').offset().top-80;
	  	$('#tre0 #'+id).children('.Trc').addClass("hig");
//	  	$('.highslide-body').parent().animate({ scrollTop: destination}, 500 ); 
		return true;};
/*	hs.Expander.prototype.onAfterExpand = function (sender) {
		$('#tre0 #'+id).parents('li').removeClass('TEc').addClass('TEo');
	 	destination = $('#tre0 #'+id).offset().top-$('.highslide-body').offset().top-80;
	  	$('#tre0 #'+id).children('.Trc').addClass("hig");
	  	$('.highslide-body').parent().animate({ scrollTop: destination}, 500 ); 
		return true;};*/
	return hs.htmlExpand(el,
     { contentId: 'ftree',width:550,height:400} );
}
var cb=function(){
	$('#'+arguments[0]).val(arguments[1]);
	ex=hs.getExpander();
	elid="#"+ex.thumbsUserSetId;
	if(ex!=null){ex.close();}
	if ($(elid).length>0){
		gotourl('include.php?place=ishop#open_sub_cats(' + arguments[1] + ', 1)');
		return false;
	}
	if ($('#pexp').length>0) {
		$.ajax({url: 'modules/ishop/ajax.php?&act=getcatnm' + '&id=' + arguments[1],cache: false,
			success: function(html){$('#pexp').html(html);}});
	}
}
function cb1(){
	$('#'+arguments[0]).val(arguments[1]);
	$('#rt2 *').removeClass("hig");
  	$('#rt2 #'+arguments[1]).children('.Trc').addClass("hig");	
}
function on_off_acl(el){
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	type=tel.substr(0,3);
	url='';
	$.ajax({url: 'modules/ishop/ajax.php?&act=part_on_acl&id=' + id + '&type='+type,cache: false,
		success: function(html){
			if (html == 'isacl'){$(el).removeClass('acldis');}
			if (html == 'acloff'){if (!($(el).hasClass('acldis'))){$(el).addClass('acldis');}}}
	});
}

function on_off(el){
	tel=el.parentElement.parentElement.id;
	id=tel.substr(8,tel.length-8);
	type=tel.substr(0,3);
	url='';
	if ($(el).hasClass('vsb')){
		url='1';
	}	
	$.ajax({url: 'modules/ishop/ajax.php?&act=part_on' + url + '&id=' + id + '&type='+type,cache: false,
		success: function(html){
			if (html == 'Включен'){$(el).removeClass('edis');}
			if (html == 'Выключен'){if (!($(el).hasClass('edis'))){$(el).addClass('edis');}}
			if (html == 'Видимый'){$(el).removeClass('eunv');}
			if (html == 'Скрытый'){if (!($(el).hasClass('eunv'))){$(el).addClass('eunv');}}}
	});
}
function enb_clc(el){
	id=$(el).parent().prop('id');
	$.ajax({url: 'modules/ishop/ajax.php?&act=part_on' + '&id=' + id + '&type=cat',cache: false,
		success: function(html){
			if (html == 'Включен'){$(el).parent().removeClass('edis');}
			if (html == 'Выключен'){if (!($(el).parent().hasClass('edis'))){$(el).parent().addClass('edis');}}}
	});
}
function vsb_clc(el){
	id=$(el).parent().prop('id');
	$.ajax({url: 'modules/ishop/ajax.php?&act=part_on1' + '&id=' + id + '&type=cat',cache: false,
		success: function(html){
			if (html == 'Видимый'){$(el).parent().removeClass('eunv');}
			if (html == 'Скрытый'){if (!($(el).parent().hasClass('eunv'))){$(el).parent().addClass('eunv');}}}
	});
}
function tree_toggle(event,id) {
	event = event || window.event
	var clickedElem = event.target || event.srcElement
	
    if (hasClass(clickedElem, 'Trc') && cb!=undefined && id=='tre0'){
		cb('pid',$(clickedElem).parent().prop('id'));
		return;
	}
    if (hasClass(clickedElem, 'Trc') && id=='rt1'){
		rec_cat_spl($(clickedElem).parent().prop('id'));
		return;
	}
    if (hasClass(clickedElem, 'Trc') && id=='rt2'){
		cb1('pid',$(clickedElem).parent().prop('id'));
		return;
	}
    if (hasClass(clickedElem, 'Trc') && id=='mtr'){
		if (event.ctrlKey){
			gotourl('include.php?place=ishop#update_cat_html('+$(clickedElem).parent().prop('id')+')');
		}else{
			gotourl('include.php?place=ishop#open_sub_cats('+$(clickedElem).parent().prop('id')+', 1)');	
		}
		return;
	}
   if (hasClass(clickedElem, 'enb')){
		enb_clc(clickedElem);
		return;
	}
    if (hasClass(clickedElem, 'vsb')){
		vsb_clc(clickedElem);
		return;
	}

	if (!hasClass(clickedElem, 'TRe')) {
		return; // клик не там
	}

	// Node, на который кликнули
	var node = clickedElem.parentNode
	if (hasClass(node, 'TEl')) {
		return; // клик на листе
	}
	if (hasClass(node, 'TEc') && event.ctrlKey) {
		setop($(node));
		return;
	}
	if (hasClass(node, 'TEo') && event.ctrlKey) {
		setcl($(node));
		return;
	}

	// определить новый класс для узла
	var newClass = hasClass(node, 'TEo') ? 'TEc' : 'TEo'
	// заменить текущий класс на newClass
	// регексп находит отдельно стоящий open|close и меняет на newClass
	var re = new  RegExp('(^|\\s)(TEo|TEc)(\\s|$)');
	node.className = node.className.replace(re, '$1'+newClass+'$3')
}
function setop(el){
	if ($(el).hasClass('TEc')){
		$(el).removeClass('TEc').addClass('TEo');
	}
	if ($(el).children('ul').children('li').length>0){
		setop($(el).children('ul').children('li'));
	}
}
function setcl(el){
	if ($(el).hasClass('TEo')){
		$(el).removeClass('TEo').addClass('TEc');
	}
	if ($(el).children('ul').children('li').length>0){
		setcl($(el).children('ul').children('li'));
	}
}
function hasClass(elem, className) {
	return new RegExp("(^|\\s)"+className+"(\\s|$)").test(elem.className)
}
function load_price(val){
	$.ajax({url: 'modules/ishop/ajax.php?act=load_price&cat_id=' + val,	cache: false,success: function(html){
		$('#mc3').html(html);}});
}
function load_opl(){
	$.ajax({url: 'modules/ishop/ajax.php?act=load_opl',cache: false,success: function(html){
		$('#module_html').html(html);}});
}
function save_price(){
	$.ajax({url: 'modules/ishop/ajax.php?act=save_price',cache: false,success: function(html){
		$('#module_html').html(html);}});
}
function run_sql(){
	$.ajax({url: 'modules/ishop/ajax.php?act=run_sql',cache: false,	success: function(html){
		$('#module_html').html(html);}});
}
function run_sql_run(){
	$.ajax({url: 'modules/ishop/ajax.php?act=sqlrun&tsql='+$("#tsql").val(),cache: false,
			success: function(html){$('#status').html(html);}});
}
function add_rec(){	
	$(".all_selects").children("option:selected").each(function(){
		if($(this).parent().parent().css('display') == 'block' )	{
			$(this).clone().removeAttr('selected').appendTo($("select[name='recom_products_all']"));
		}
	});
	update_r();
}
function back_change_urls(){
	if (confirm('Вы действительно хотите убрать URL страницы из названий?')) {
		$.ajax({url: 'modules/ishop/ajax.php?act=back_change_urls',cache: false,success: function(html){alert('Готово');}});
	}
}
function upd_feed(){
	$.ajax({url: 'modules/ishop/ajax.php?act=upd_feed',cache: false,success: function(html){alert('Готово');}});
}
function del_rec(){
	$("select[name='recom_products_all']").children("option:selected").each(function(){	$(this).remove();});
	update_r();
}
function update_r(){
	var str = '';
	$("select[name='recom_products_all']").children("option").each(function(){
		str += $(this).val() + ',';});
	$("input[name='r_prds']").val(str);
}
function apply_in_Sub(val){
	$.ajax({url: 'modules/ishop/ajax.php?act=update_titles' + '&id=' + val + '&title=' + escape($("#pagetitle").val()),
			cache: false,success: function(html){}});
}
function save_price_file(){
	var op = 0;
	if ($("#o_op").prop('checked')){
		op=1;
	}
	var type = 1;
	if ($("#s_grp").prop('checked')){
		type=2;
	}
	var sfilt='';
	if ($("#title_tf").val().length>0 && $("#o_tf").prop('checked')){
		sfilt='&value='+escape($("#title_tf").val());
	} 
	$.ajax({url: 'modules/ishop/ajax.php?act=save_csv&out_tf='+$("#out_tf").val()+'&type='+type+'&f_op='+op+sfilt,
			cache: false,success: function(html){$('#status').html(html);}});
}
function load_opl_file(){
	$.ajax({url: 'modules/ishop/ajax.php?act=load_opl_file&load_file='+$("#url_abs_nohost").val(),
			cache: false,success: function(html){$('#status').html(html);}});
}
function load_file(prd_id){
	if (!($('#url_abs_nohost_adv').val().length>5)){
		alert('Не выбрано фото!');
		return false;
	}
	$.ajax({url: 'modules/ishop/ajax.php?act=add_adv_foto' + '&name=' + $('#url_abs_nohost_adv').val() + '&id=' + prd_id,
			cache: false,success: function(html){$('#adv_photosc').html(html);}});
}
function del_color(value){
	$('#add_mat_btn').html('...');

	$.ajax({
			url: 'modules/ishop/ajax.php?act=del_color' + '&id=' + value + '&id_mat=' + $('#edit_id').val(),
			cache: false,
			success: function(html){
				$('#module_html').html(html);
			}
		});
}
function update_color(id, el){
	var oldv = el.value;
	el.value = '...';
	tinyMCEDeinit('editor1');
	$.ajax({
			url: 'modules/ishop/ajax.php?act=update_color' + '&id=' + id + '&name=' + oldv + '&id_mat=' + $('#edit_id').val(),
			cache: false,
			success: function(html){
				$('#module_html').html(html);
				tinyMCEInit('editor1');
			}
		});
}
function add_color(value){
	tinyMCEDeinit('editor1');
	$('#add_mat_btn').html('...');

	$.ajax({
			url: 'modules/ishop/ajax.php?act=add_color' + '&id_mat=' + $('#edit_id').val() + '&name=' + value,
			cache: false,
			success: function(html){
				$('#module_html').html(html);
				tinyMCEInit('editor1');
			}
		});
}
function back_mat(){
	tinyMCEDeinit('editor1');
	$.ajax({
			url: 'modules/ishop/ajax.php?act=back_material',
			cache: false,
			success: function(html){
				$('#module_html').html(html);
			}
		});
}
function del_mat(value){
	$('#add_mat_btn').html('...');
	$.ajax({
			url: 'modules/ishop/ajax.php?act=del_material' + '&id=' + value,
			cache: false,
			success: function(html){
				$('#module_html').html(html);
			}
		});
}
function edit_mat(id){
	$.ajax({
			url: 'modules/ishop/ajax.php?act=edit_material' + '&id=' + id,
			cache: false,
			success: function(html){
				$('#module_html').html(html);
			}
		});
}
function update_mat(id, el){
	var oldv = el.value;
	el.value = '...';

	$.ajax({
			url: 'modules/ishop/ajax.php?act=update_material' + '&id=' + id + '&name=' + oldv,
			cache: false,
			success: function(html){
				$('#module_html').html(html);
			}
		});
}
function add_material(value){
	$('#add_mat_btn').html('...');
	$.ajax({
			url: 'modules/ishop/ajax.php?act=add_material' + '&name=' + value,
			cache: false,
			success: function(html){
				$('#module_html').html(html);
			}
		});
}
function del_adv_foto(foto_id){
	$('#del_l_v_' + foto_id).html('Удаляется...');
	$.ajax({
			url: 'modules/ishop/ajax.php?act=del_adv_foto' + '&foto_id=' + foto_id,
			cache: false,
			success: function(html){
				$('#adv_foto_' + foto_id).remove();
			}
		});
}
function upd_adv_sort(foto_id){
	var oval = $('#sort_adv_v_' + foto_id).val();
	$('#sort_adv_v_' + foto_id).attr({'value' : '...'});

	$.ajax({
			url: 'modules/ishop/ajax.php?act=sort_adv_foto' + '&foto_id=' + foto_id + '&value=' + oval,
			cache: false,
			success: function(html){
				$('#sort_adv_v_' + foto_id).attr({'value' : oval});
			}
		});
}
function insert_char(value, cat){
	$.ajax({
			url: 'modules/ishop/ajax.php?act=add_char' + '&name=' + value + '&cat=' + cat,
			cache: false,
			success: function(html){
				update_cat_mod_html(cat);
			}
		});
}
function return_i(id){
	if (id==undefined || id==-1)
	{
		gotourl('include.php?place=ishop#open_sub_cats(' + $('#pid').val() + ')');
	}
	else
	{
		gotourl('include.php?place=ishop#open_sub_cats1('+id + ')');
	}
}
function del_comments(prd_id){
	$.ajax({
			url: 'modules/ishop/ajax.php?act=del_comments&id=' + prd_id,
			cache: false,
			success: function(html){
				$('#prd_comm_' + prd_id).remove();
			}
		});
}
function onof_comm(par_name, thid){
	$(thid).html('...');
	$.ajax({
			url: 'modules/ishop/ajax.php?act=comments_on&id=' + par_name,
			cache: false,
		success: function(html){
			$(thid).html((html == 1) ? 'Включено' : 'Выключено');
			$('#dc_'+par_name).prop('checked',(html == 1));
		}
		});
}
function Deleting(){
	var str = get_selects('boxcat');
	var str2 = get_selects('boxprd');
	$.ajax({
			url: 'modules/ishop/ajax.php?act=delete_cats' + '&cats=' + str + '&prds=' + str2,
			cache: false,
			success: function(html){
				if(parseInt(html) > 0)
				{
					if(str != '')
					{
						var h = explode('_', str);
						for(var t=0; t<h.length;t++)
						{
							if($('#cat_row_' + h[t]))  $('#cat_row_' + h[t]).remove();
						}
					}

					if(str2 != '')
					{
						var h2 = explode('_', str2);
						for(var t=0; t<h2.length;t++)
						{
							if($('#prd_row_' + h2[t]))  $('#prd_row_' + h2[t]).remove();
						}
					}
				}
			}
		});
}
function SpecDeleting(id){
	//var str = get_selects('boxcat');
	var str2 = get_selects('boxprd');

	$.ajax({
			url: 'modules/ishop/ajax.php?act=delete_spec' + '&prds=' + str2,
			cache: false,
			success: function(html){
				if(parseInt(html) > 0)
				{
					if(str2 != '')
					{
						var h2 = explode('_', str2);
						for(var t=0; t<h2.length;t++)
						{
							if($('#prd_row_' + h2[t]))  $('#prd_row_' + h2[t]).remove();
						}
					}
				}
			}
		});
}
function NewDeleting(id){
	//var str = get_selects('boxcat');
	var str2 = get_selects('boxprd');

	$.ajax({
			url: 'modules/ishop/ajax.php?act=delete_new' + '&prds=' + str2,
			cache: false,
			success: function(html){
				if(parseInt(html) > 0)
				{
					if(str2 != '')
					{
						var h2 = explode('_', str2);
						for(var t=0; t<h2.length;t++)
						{
							if($('#prd_row_' + h2[t]))  $('#prd_row_' + h2[t]).remove();
						}
					}
				}
			}
		});
}
function HitDeleting(id){
	//var str = get_selects('boxcat');
	var str2 = get_selects('boxprd');

	$.ajax({
			url: 'modules/ishop/ajax.php?act=delete_hit' + '&prds=' + str2,
			cache: false,
			success: function(html){
				if(parseInt(html) > 0)
				{
					if(str2 != '')
					{
						var h2 = explode('_', str2);
						for(var t=0; t<h2.length;t++)
						{
							if($('#prd_row_' + h2[t]))  $('#prd_row_' + h2[t]).remove();
						}
					}
				}
			}
		});
}
function setaclose(el){
	if (confirm('Вы действительно хотите установить автозакрытие для всех товаров в категории?')) {
		tel=el.parentElement.parentElement.id;
		id=tel.substr(8,tel.length-8);
		type=tel.substr(0,3);
		$.ajax({
				url: 'modules/ishop/ajax.php?act=setaclose' + '&id=' + id,
				cache: false,
				success: function(html){
					if(parseInt(html) > 0)
					{
						
					}
				}
			});
	}
}

function delete_r(el){
	if (confirm('Вы действительно хотите удалить ?')) {
		tel=el.parentElement.parentElement.id;
		id=tel.substr(8,tel.length-8);
		type=tel.substr(0,3);
		$.ajax({
				url: 'modules/ishop/ajax.php?act=delete_cats' + '&' + type + 's=' + id,
				cache: false,
				success: function(html){
					if(parseInt(html) > 0)
					{
						$("#"+tel).remove();
					}
				}
			});
	}
}
function Coping(cats, prds, dvalue){
	$.ajax({
			url: 'modules/ishop/ajax.php?act=copy_cats' + '&cats=' + cats  + '&prds=' + prds + '&cat_id=' + dvalue,
			cache: false,
			success: function(html){
				$('#module_html').html(html);
			}
		});
}
function CopingRec(cats){
	$.ajax({url: 'modules/ishop/ajax.php?act=set_recpar' + '&cats=' + cats,
			cache: false,success: function(html){if (html=='1'){alert('Успешно');}}});
}
function MakeGr(val){
	$.ajax({url: 'modules/ishop/ajax.php?act=makegr' + '&prds=' + val,
			cache: false,success: function(html){$('#module_html').html(html);}});
}
function makegr_ok(prds,val,gr){
	if (val==undefined){
		alert('Необходимо выбрать элемент!');
		return false;
	}
	$.ajax({url: 'modules/ishop/ajax.php?act=makegr_ok' + '&prds=' + prds+ '&val='+val,
			cache: false,success: function(html){
				gotourl('include.php?place=ishop#open_sub_cats('+gr+',0)');
			}});
}
function Moving(dvalue){
	$.ajax({
			url: 'modules/ishop/ajax.php?act=move_cats' + '&cats=' + get_selects('boxcat')  + '&prds=' + get_selects('boxprd') + '&cat_id=' + dvalue,
			cache: false,
			success: function(html){
				$('#module_html').html(html);
			}
		});
}
function copy_to(b, c, kolvo){
	$.ajax({
			url: 'modules/ishop/ajax.php?act=copy_to' + '&cats=' + b + '&prds=' + c + '&id=' + $('#pid').val() + '&kolvo=' + kolvo,
			cache: false,
			success: function(html){
				gotourl('include.php?place=ishop#open_sub_cats(' + $('#pid').val() + ')');
			}
		});
}
function move_to(b, c){
	$.ajax({
			url: 'modules/ishop/ajax.php?act=move_to' + '&cats=' + b + '&prds=' + c + '&id=' + $('#pid').val(),
			cache: false,
			success: function(html){
				gotourl('include.php?place=ishop#open_sub_cats(' + $('#pid').val() + ')');
			}
		});
}
function open_upak(id, page,find){
	if($('#mc3')) {
		surl='modules/ishop/ajax.php?act=get_upak' + '&id=' + id + '&page=' + page;
		if (find!=undefined) surl=surl+'&find=' + find;
		$.ajax({url: surl,cache: false,success: function(html){	$('#mc3').html(html);scroll_top();}
			});
	}
}
function open_tper(id, page,find){
	if($('#mc3')) {
		surl='modules/ishop/ajax.php?act=get_tper' + '&id=' + id + '&page=' + page;
		if (find!=undefined) surl=surl+'&find=' + find;
		$.ajax({url: surl,cache: false,success: function(html){	$('#mc3').html(html);scroll_top();}
			});
	}
}
function SaveUpak(el){
	var frem=$('#fuser').serialize();
	var ff=el.children[0].children[0].children[1].innerHTML;
	var hj=$("#fid");
	if ($("#fid").attr('readonly')=='readonly')
	{
		$("#fuser .fbinp").each(function(){ $(this).removeAttr("readonly");});
		el.children[0].children[0].children[1].innerHTML='Сохранить';
		return false;
	}
	$.ajax({
			url: 'modules/ishop/ajax.php?act=save_upak',
			cache: false,
			type:'POST',
			data: frem,
			success: function (html){
				if(parseInt(html) > 0)
				{
					el.children[0].children[0].children[1].innerHTML='Успешно!';
					$("#fuser .fbinp").each(function(){ $(this).attr({readonly:"readonly"});});
				}
			}
		});
	return false;
}
function SaveTper(el){
	var frem=$('#fuser').serialize();
	var ff=el.children[0].children[0].children[1].innerHTML;
	var hj=$("#fid");
	if ($("#fid").attr('readonly')=='readonly')
	{
		$("#fuser .fbinp").each(function(){ $(this).removeAttr("readonly");});
		el.children[0].children[0].children[1].innerHTML='Сохранить';
		return false;
	}
	$.ajax({
			url: 'modules/ishop/ajax.php?act=save_tper',
			cache: false,
			type:'POST',
			data: frem,
			success: function (html){
				if(parseInt(html) > 0)
				{
					el.children[0].children[0].children[1].innerHTML='Успешно!';
					$("#fuser .fbinp").each(function(){ $(this).attr({readonly:"readonly"});});
				}
			}
		});
	return false;
}

function view_upak(el,inid){
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onBeforeClose = function (sender) {
		$("#fuser .fbinp").each(function(){ $(this).attr({readonly:"readonly"});});
		$("td .btn_c:contains('Сохранить')").html('Редактировать');
		return true;};
	hs.htmlExpand(el, {
			objectType: 'ajax', width: '650',src: 'modules/ishop/ajax.php?act=edit_upak&ID='+inid,
			wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Cтандарт поставки '+inid});

}
function view_tper(el,inid){
	var html=el.innerHTML;
	if ( ! ( inid > ' ') ) inid = html;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.Expander.prototype.onBeforeClose = function (sender) {
		$("#fuser .fbinp").each(function(){ $(this).attr({readonly:"readonly"});});
		$("td .btn_c:contains('Сохранить')").html('Редактировать');
		return true;};
	hs.htmlExpand(el, {
			objectType: 'ajax', width: '650',src: 'modules/ishop/ajax.php?act=edit_tper&ID='+inid,
			wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Группировка заказов '+inid});

}

function NewUpak(el){
	var html=el.innerHTML;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.htmlExpand(el, {
			objectType: 'ajax', width: '650',src: 'modules/ishop/ajax.php?act=new_upak',
			wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Новый Cтандарт поставки'});
}

function NewTper(el){
	var html=el.innerHTML;
	hs.anchor = 'auto';
	hs.preserveContent = false;
	hs.htmlExpand(el, {
			objectType: 'ajax', width: '650',src: 'modules/ishop/ajax.php?act=new_tper',
			wrapperClassName: 'titlebar',targetX: 'posip 20px', targetY: null, headingText: 'Новая Группировка заказов'});
}

function SaveNewUpak(el){
	var frem=$('#fuser').serialize();
	var ff=el.children[0].children[0].children[1].innerHTML;
	$.ajax({
			url: 'modules/ishop/ajax.php?act=save_new_upak',
			cache: false,
			type:'POST',
			data: frem,
			success: function (html){
				if (html.search('23000')!=-1){
					alert('Запись с таким кодом уже существует!');
				}
				if(parseInt(html) > 0)	el.children[0].children[0].children[1].innerHTML='Успешно!';
				ex=hs.getExpander();
				if(ex!=null){ex.close();}

			}
		});
	return false;

}
function SaveNewTper(el){
	var frem=$('#fuser').serialize();
	var ff=el.children[0].children[0].children[1].innerHTML;
	$.ajax({
			url: 'modules/ishop/ajax.php?act=save_new_tper',
			cache: false,
			type:'POST',
			data: frem,
			success: function (html){
				if (html.search('23000')!=-1){
					alert('Запись с таким кодом уже существует!');
				}
				if(parseInt(html) > 0)	el.children[0].children[0].children[1].innerHTML='Успешно!';
				ex=hs.getExpander();
				if(ex!=null){ex.close();}

			}
		});
	return false;

}

function deleteupak(id){
	$.ajax({
			url: 'modules/ishop/ajax.php?act=delete_upak&ID=' + id,
			cache: false,
			success: function(html){
				if(parseInt(html) > 0)
				{
					if($('#ru_' + id))  $('#ru_' + id).remove();
				}
			}
		});
}
function open_sub_cats(id, page,type,srt,find){
	if($('#mc3').length){
		if (srt==undefined){
			if (tsort>0){
				srt=tsort;
			}else{
				srt='0';
			}
		} else {
			tsort=srt;
		}
		if (type=='all' || type==undefined || type=='')	{type='all';}	
		surl='modules/ishop/ajax.php?act=get_cats_s' + '&id=' + id + '&page=' + page+ '&type=' + type+'&srt='+srt;
		if (find!=undefined) surl=surl+'&find='+find;
		$.ajax({url: surl,cache: false,success: function(html){
					$('#mc3').html(html);
					scroll_top();
					if (type='all'){
						$('#tder').attr("onClick","gotourl('include.php?place=ishop#open_treesub_cats("+id+")')");
						$('#tkat').attr("onClick","open_sub_cats("+id+","+page+")");
					}
				}
		});
/*		var str=$('#ftree .highslide-body').html();
		if (str.length == 0){
		$.ajax({
				url: 'modules/ishop/ajax.php?act=get_catstree' + '&v1=1',
				cache: false,
				success: function(html){
					$('#ftree .highslide-body').html(html);
					$('#tre0 #11').removeClass('TEc').addClass('TEo');
				}
			});
			
		}*/
	}
}
function open_treesub_cats(id, page){
	if($('#mc3').length)
	{
		$.ajax({
				url: 'modules/ishop/ajax.php?act=get_catstree' + '&id=' + id + '&page=' + page,
				cache: false,
				success: function(html){
					$('#mc3').html(html);
					if (id>0 && id!=11 ){
						$('#mtr #'+id).parents('li').removeClass('TEc').addClass('TEo');
						destination = $('#mtr #'+id).offset().top-500;
		  				$('#mtr #'+id).children('.Trc').addClass("hig");
		  				$('html,body').animate({ scrollTop: destination}, 500 ); 
					}
					$('#mtr #11').removeClass('TEc').addClass('TEo');
				}
			});
	}
}
function vlink_tree(){
	if($('#mc3').length)
	{
		$.ajax({
				url: 'modules/ishop/ajax.php?act=get_catstree&vlink=1',
				cache: false,
				success: function(html){
					$('#mc3').html(html);
				}
			});
	}
}
	
function open_sub_cats1(id){
	if($('#mc3').length)
	{
		$.ajax({
				url: 'modules/ishop/ajax.php?act=get_cats1' + '&id=' + id,
				cache: false,
				success: function(html){
					$('#mc3').html(html);
					scroll_to('prd_row_'+id);
				}
			});
	}
}
function open_sub_cats_json(id, page){
	if($('#mc3'))
	{
		$.getJSON('modules/ishop/ajax.php?act=get_cats' + '&id=' + id + '&page=' + page,
			function(html){
				$.each(html, function(i, item){
						alert(item.id + ' ' + item.sort + ' ' + item.title + ' ' + item.enabled);
					});
			}
		);
	}
}
function add_prd_html(id){
	if($('#mc3'))
	{
		$.ajax({
				url: 'modules/ishop/ajax.php?act=update_prd_html' + '&id=-1&cat=' + id,
				cache: false,
				success: function(html){
					$('#mc3').html(html);
					scroll_top();
				}
			});
	}
}
function add_this_page(id){
	var module_html = $('#mc3');
	if(module_html)
	{
		$.ajax({
				url: 'modules/ishop/ajax.php?act=update_cat_html' + '&id=-1&cat=' + id,
				cache: false,
				success: function(html){
					$('#mc3').html(html);
					scroll_top();
				}
			});
	}
}
function update_cat_mod_html(id){
	var module_html = $('#mc3');
	if(module_html)
	{
		$.ajax({
				url: 'modules/ishop/ajax.php?act=update_cat_mod_html' + '&id=' + id,
				cache: false,
				success: function(html){
					$('#mc3').html(html);
				}
			});
	}
}

function testsend(){
		$.ajax({
			url: 'ie.php',
			cache: false,
			type:'POST',
			data: $("#upl_csv").serialize(),
			success: function (html){
				$('#status').html(html);
			}
		});
	
}