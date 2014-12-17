/* JCE Editor - 2.4.5 | 09 December 2014 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2014 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
(function($){$.widget("ui.extensionmapper",{options:{labels:{'type_new':'Add new type...','group_new':'Add new group...'},defaults:''},_init:function(){var self=this,el=this.element,v=$(el).val()||'',name=$(el).attr('name').replace(/\[\]/,'');this.defaultMap={};var dv=this.options.defaults||$(el).data('default');if(dv){$.each(dv.split(';'),function(i,s){var parts=s.split('=');self.defaultMap[parts[0]]=parts[1].split(',');});}
v=$.type(v)=='array'?v.join(';'):v;var $input=$('<input type="hidden" name="'+name+'" id="'+$(el).attr('id')+'" />').addClass(function(){return $(el).hasClass('create')?'create':'';}).insertBefore(el).hide().val(v);$(el).remove();this.element=$input;$('<input type="text" disabled="disabled" role="presentation" aria-disabled="true" />').val(v).insertBefore(this.element);$('<span class="extension_edit"></span>').click(function(){var $edit=this;if(!this.mapper){$(this).addClass('loader');this.mapper=self._buildMapper();$(this.mapper).hide().insertAfter(this).slideDown(450,function(){$($edit).removeClass('loader');$('div.extension_group_container ul.extension_list',this).each(function(){if(this.firstChild.offsetHeight*this.childNodes.length>this.parentNode.offsetHeight){$(this).parent('div.extension_list_container').next('div.extension_list_scroll_bottom').css('visibility','visible');}});});}else{$(this.mapper).slideToggle(450);}}).insertAfter(this.element);},_buildMapper:function(){var self=this,v=$(this.element).val();var $container=$('<div class="extension_mapper" id="'+$(this.element).attr('id')+'_mapper" role="presentation"></div>');$.each(v.split(';'),function(i,s){$container.append(self._createGroup(s.split('=')));});if($(this.element).hasClass('create')){$('<div class="extension_group_add"><span>'+this.options.labels.group_new+'</span></div>').click(function(){var group=self._createGroup();$(group).hide().insertBefore(this).fadeIn('fast');self._createSortable($('ul.extension_list',group));}).appendTo($container);}
this._createSortable($('ul.extension_list',$container));$container.sortable({tolerance:'intersect',placeholder:'sortable-highlight',handle:'span.extension_group_handle',update:function(event,ui){self._setValues();},start:function(event,ui){$(ui.placeholder).width($(ui.item).width()).height($(ui.item).height());}});return $container;},_createSortable:function(list){var self=this;$(list).sortable({connectWith:'ul.extension_list',placeholder:'sortable-highlight',update:function(event,ui){if(!ui.sender)
return;self._showScroll($(ui.item).parent(),['bottom']);self._showScroll($(ui.sender),['top','bottom']);self._setValues();}});},_createGroup:function(values){var self=this;values=values||['custom','custom'];var $tmpl=$('<div class="extension_group_container" role="group">'+' <div class="extension_group_titlebar">'+'  <span class="extension_group_handle icon-move"></span>'+'  <span class="extension_group_title"></span>'+' </div>'+' <div class="extension_list_add"><span role="button">'+this.options.labels.type_new+'</span></div>'+' <div class="extension_list_scroll_top" role="button"><span class="extension_list_scroll_top_icon"></span></div>'+' <div class="extension_list_container">'+'  <ul class="extension_list"></ul>'+' </div>'+' <div class="extension_list_scroll_bottom" role="button"><span class="extension_list_scroll_bottom_icon"></span></div>'+'</div>');var name=values[0],list=values[1]||'';if(name=='custom'){$('<input type="text" size="8" value="" pattern="[a-zA-Z0-9_-]+" />').change(function(){if(this.value=='')
return;var v=this.value.toLowerCase();$('span.extension_group_title',$tmpl).addClass(v).attr('title',v);}).appendTo($('span.extension_group_title',$tmpl)).focus().pattern();var $remove=$('<span class="extension_group_remove" role="button"></span>').click(function(){$($tmpl).fadeOut('fast',function(){$tmpl.remove();self._setValues();});});$('div.extension_group_titlebar',$tmpl).append($remove);}else{var key=name.replace(/[\W]/g,'');if(this.defaultMap[key]){var $check=$('<span class="checkbox" role="checkbox"></span>').addClass(function(){return name.charAt(0)=='-'?'':'checked';}).attr('aria-checked',!(name.charAt(0)=='-'));$check.click(function(){var s=name;if(s.charAt(0)==='-'){s=s.substr(1);}
if($(this).is('.checked')){$(this).removeClass('checked').attr('aria-checked',false).prev('span.extension_group_title').attr('title','-'+s);}else{$(this).addClass('checked').attr('aria-checked',true).prev('span.extension_group_title').attr('title',s);}
self._setValues();});$('div.extension_group_titlebar',$tmpl).append($check);}else{var $remove=$('<span class="extension_group_remove" role="button"></span>').click(function(){$($tmpl).fadeOut('fast',function(){$tmpl.remove();self._setValues();});});$('div.extension_group_titlebar',$tmpl).append($remove);}
var title=this.options.labels[key]||(key.charAt(0).toUpperCase()+key.substr(1));$('span.extension_group_title',$tmpl).html(title);}
$('span.extension_group_title',$tmpl).attr('title',name).addClass(name);$('div.extension_list_add span',$tmpl).click(function(){self._createItem('custom').hide().prependTo($('ul.extension_list',$tmpl)).fadeIn('fast',function(){var parent=this.parentNode;if(parent.firstChild.offsetHeight*parent.childNodes.length>parent.parentNode.offsetHeight){$(parent).parent('div.extension_list_container').next('div.extension_list_scroll_bottom').css('visibility','visible');}
$(this).focus();});});$('div.extension_list_scroll_top',$tmpl).click(function(){self._scrollTo('top',$('ul.extension_list',$tmpl));});$('div.extension_list_scroll_bottom',$tmpl).click(function(){self._scrollTo('bottom',$('ul.extension_list',$tmpl));});list=list.replace(/^[;,]/,'').replace(/[;,]$/,'');$.each(list.split(','),function(){$('ul.extension_list',$tmpl).append(self._createItem(this,key));});return $tmpl;},_createItem:function(value,group){var self=this,v=value.replace(/[^a-z0-9]/gi,''),$item;if(value=='custom'){$item=$('<li class="file custom">'+' <span class="extension_title"><input type="text" value="" size="6" pattern="[a-zA-Z0-9_-]+" /></span>'+' <span class="extension_list_remove" role="button"></span>'+'</li>');$('input',$item).change(function(){if(this.value==''){$(this).removeClass('duplicate');$($item).removeClass(function(){return this.className.replace(/(file|custom)/,'');});return;}
if(new RegExp(new RegExp('[=,]'+this.value+'[,;]')).test($(self.element).val())){$(this).addClass('duplicate');$item.addClass('duplicate');}else{$(this).removeClass('duplicate');$item.removeClass(function(){return this.className.replace(/(file|custom)/,'');}).addClass(this.value);if(this.value!=''){self._setValues();}}}).focus().pattern();}else{$item=$('<li class="file '+v+'">'+' <span class="extension_title" title="'+value+'">'+value.replace(/[\W]+/,'')+'</span>'+' <span class="checkbox" role="checkbox" aria=checked="false"></span>'+'</li>');var map=this.defaultMap[group];if($.inArray(v,map)==-1){$('span.checkbox',$item).removeClass('checkbox').addClass('extension_list_remove').attr('role','button')}else{$('span.checkbox',$item).addClass(function(){return value.charAt(0)=='-'?'':'checked';}).attr('aria-checked',!(value.charAt(0)=='-')).click(function(){if($(this).is('.checked')){$(this).removeClass('checked').attr('aria-checked',false).prev('span.extension_title').attr('title','-'+v);}else{$(this).addClass('checked').attr('aria-checked',true).prev('span.extension_title').attr('title',v);}
self._setValues();});}}
$('span.extension_list_remove',$item).click(function(){$item.fadeOut('fast',function(){var parent=this.parentNode;if(parent.firstChild.offsetHeight*parent.childNodes.length<parent.parentNode.offsetHeight){$(parent).parent('div.extension_list_container').next('div.extension_list_scroll_bottom').css('visibility','hidden');}
if($(parent).children().length==0){$(parent).parents('div.extension_group_container').fadeOut('fast',function(){$(this).remove();});}
$(this).remove();if($('input',$item).val()==''){return;}
self._setValues();});});return $item;},_showScroll:function(el,dir){var p=$(el).parent(),m=parseFloat($(el).css('margin-top'));function check(el,p,dir){if(dir=='top'){return parseFloat(m)==0;}else{if(m==0){var c=$(el).children();return $(c).first().outerHeight()*c.length<$(p).outerHeight();}else{return(m+$(el).outerHeight())<$(p).outerHeight();}}}
var scroll=(dir=='top')?p.prev():p.next();$.each(dir,function(n,s){if(check(el,p,s)){scroll.css('visibility','hidden');}else{scroll.css('visibility','visible');}});},_scrollTo:function(dir,ul){var self=this,p=$(ul).parent(),mt=parseFloat($(ul).css('margin-top')),x=$(ul).get(0).firstChild.offsetHeight,v=mt-x,inv;if(dir=='top'){v=mt+x;v=v+1;if(mt==0||v>0)
return;}else{v=v-1;}
inv=(dir=='top')?p.next():p.prev();$(ul).animate({'marginTop':v},500,function(){$(inv).css('visibility','visible');self._showScroll(ul,[dir]);});},_setValues:function(){var id=$(this.element).attr('id'),groups=[],title='';$('div.extension_group_container','#'+id+'_mapper').each(function(){var n=$('span.extension_group_title:first',this);if($(n).is('.custom')){title=$('input',n).val();}else{title=$(n).attr('title');}
if(title){var list=[],v,title=title.toLowerCase();$('li span',this).each(function(){v=$('input',this).val()||$(this).attr('title');if(v){list.push(v);}});groups.push(title+'='+list.join(','));}});var data=groups.join(';').replace(/([a-z]+)=;/g,'').replace(/^[;,]/,'').replace(/[;,]$/,'');$(this.element).val(data).change();},destroy:function(){$.Widget.prototype.destroy.apply(this,arguments);}});})(jQuery);