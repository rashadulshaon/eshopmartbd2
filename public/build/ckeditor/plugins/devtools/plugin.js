CKEDITOR.plugins.add("devtools",{lang:"ar,az,bg,ca,cs,cy,da,de,de-ch,el,en,en-au,en-gb,eo,es,es-mx,et,eu,fa,fi,fr,fr-ca,gl,gu,he,hr,hu,id,it,ja,km,ko,ku,lt,lv,nb,nl,no,oc,pl,pt,pt-br,ro,ru,si,sk,sl,sq,sr,sr-latn,sv,tr,tt,ug,uk,vi,zh,zh-cn",init:function(t){t._.showDialogDefinitionTooltips=1},onLoad:function(){CKEDITOR.document.appendStyleText(CKEDITOR.config.devtools_styles||"#cke_tooltip { padding: 5px; border: 2px solid #333; background: #ffffff }#cke_tooltip h2 { font-size: 1.1em; border-bottom: 1px solid; margin: 0; padding: 1px; }#cke_tooltip ul { padding: 0pt; list-style-type: none; }")}}),function(){function t(t,o,e,n){t=t.lang.devtools;var i='<a href="https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_dialog_definition_'+(e?"text"==e.type?"textInput":e.type:"content")+'.html" target="_blank" rel="noopener noreferrer">'+(e?e.type:"content")+"</a>";return o="<h2>"+t.title+"</h2><ul><li><strong>"+t.dialogName+"</strong> : "+o.getName()+"</li><li><strong>"+t.tabName+"</strong> : "+n+"</li>",e&&(o+="<li><strong>"+t.elementId+"</strong> : "+e.id+"</li>"),(o+="<li><strong>"+t.elementType+"</strong> : "+i+"</li>")+"</ul>"}function o(t,o,n,i,l,r){var d=o.getDocumentPosition(),s={"z-index":CKEDITOR.dialog._.currentZIndex+10,top:d.y+o.getSize("height")+"px"};e.setHtml(t(n,i,l,r)),e.show(),"rtl"==n.lang.dir?(t=CKEDITOR.document.getWindow().getViewPaneSize(),s.right=t.width-d.x-o.getSize("width")+"px"):s.left=d.x+"px",e.setStyles(s)}var e;CKEDITOR.on("reset",(function(){e&&e.remove(),e=null})),CKEDITOR.on("dialogDefinition",(function(n){var i=n.editor;if(i._.showDialogDefinitionTooltips){e||((e=CKEDITOR.dom.element.createFromHtml('<div id="cke_tooltip" tabindex="-1" style="position: absolute"></div>',CKEDITOR.document)).hide(),e.on("mouseover",(function(){this.show()})),e.on("mouseout",(function(){this.hide()})),e.appendTo(CKEDITOR.document.getBody()));var l=n.data.definition.dialog,r=i.config.devtools_textCallback||t;l.on("load",(function(){for(var t,n=l.parts.tabs.getChildren(),d=0,s=n.count();d<s;d++)(t=n.getItem(d)).on("mouseover",(function(){var t=this.$.id;o(r,this,i,l,null,t.substring(4,t.lastIndexOf("_")))})),t.on("mouseout",(function(){e.hide()}));l.foreach((function(t){if(!(t.type in{hbox:1,vbox:1})){var n=t.getElement();n&&(n.on("mouseover",(function(){o(r,this,i,l,t,l._.currentTabId)})),n.on("mouseout",(function(){e.hide()})))}}))}))}}))}();