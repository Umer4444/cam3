String.prototype.capitalize = function(){
	return this.replace(/\w+/g, function(a){
		return a.charAt(0).toUpperCase() + a.substr(1).toLowerCase();
	});
};
Array.prototype.in_array = function(val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == val) {
			return true;
		}
	}
	return false;
}
function isInt(value){
	if((parseFloat(value) == parseInt(value)) && !isNaN(value)) return true;
	else return false;
}
function humanize(obj){
	value=dojo.string.trim(obj.attr("value").toLowerCase().capitalize());
	obj.attr("value",value);
	return value;
};
function addAbsentaNota(){
	dojo.connect(dijit.byId("absenta0"),"onChange",handleAbsente);
	dojo.xhrPost({
		form: "addAbsentaNotaForm",
		timeout: 3000,
		load:function(data, ioArgs){
			cell=dojo.byId(dojo.byId("elev").value+dojo.byId("data").value+dojo.byId("materia").value);
			cell.innerHTML=data;
			dijit.byId('catalogDialog').reset();
			
			tooltip=dijit.byId("tooltip"+dojo.byId("elev").value+dojo.byId("data").value+dojo.byId("materia").value);
			if(tooltip) tooltip.destroy();
			
			dojo.parser.parse(cell);
		}
	});
	dijit.byId("catalogDialog").hide();
	return false;	
}
function addCell(elev,data,materia){
	
	toggleDisplay('notabox','');
	handleAbsente();
	
	dijit.byId("nota").attr("disabled",false);
	dijit.byId('catalogDialog').reset();
	dijit.byId("catalogDialog").show();
	toggleDisplay('delNota','none');
	dijit.byId("obs").reset();
	
	dojo.byId("action").value="addAbsentaNota";
	dojo.byId("elev").value=elev;
	dojo.byId("data").value=data;
	dojo.byId("materia").value=materia;
	
}
function editCell(elev,data,materia,val,tip,motivat){
	
	toggleDisplay('notabox','');
	
	dijit.byId('catalogDialog').reset();
	dijit.byId("catalogDialog").show();
	dijit.byId("obs").reset();
	dijit.byId("nota").attr("disabled",false);
	
	dojo.byId("elev").value=elev;
	dojo.byId("data").value=data;
	dojo.byId("materia").value=materia;
	dojo.byId("action").value="edit"+tip.capitalize();
	
	tooltip=dojo.byId("tooltip"+elev+data+materia);
	if(tooltip) dijit.byId("obs").attr("value",dojo.trim(tooltip.lastChild.textContent));
	
	if(tip=="nota"){
		dijit.byId("nota").attr("value",val);
		toggleDisplay('motivatbox','none');
		toggleDisplay('delNota','');
		toggleDisplay('hTeza','none');
	}
	else if(tip=="absenta"){
		dijit.byId("absenta"+val).attr("checked",true);
		toggleDisplay('delNota','none');        
		dojo.connect(dijit.byId("absenta0"),"onChange",function(){
			toggleDisplay('notabox','none');
		});
	}

	if(motivat!=undefined && dojo.byId('motivatbox')) dijit.byId('motivat'+motivat).attr("checked",true);
	
}
function toggleDisplay(id,display){
	obj=dojo.byId(id);
	
	if(obj && display!=undefined) obj.style.display=display;
	else if(obj) obj.style.display=(obj.style.display=="none")?"":"none";
}
function validateAddCellInfo(){
	if(dijit.byId('nota').state=="Error"){
		dijit.byId('submit').attr("disabled",true);
		return false;
	}
	else dijit.byId('submit').attr("disabled",false);    
}
function handleAbsente(){
	
	absent=dijit.byId("absenta0");
	toggleDisplay('notabox',absent.checked?'':'none');

	if(dojo.byId('motivatbox')) toggleDisplay('motivatbox',absent.checked?'none':'');
			
}
function handleBulk(tip,id){
	if(confirm("Sunteti sigur ca vreti sa stergeti aceasta "+tip+" ?")){
		dojo.xhrPost({
			url:"/process/",
			content:{
				'action':'stergereBulk',
				'tip':tip,
				'id':id,
			},
			load:function(data, ioArgs){
				location.href=location.href;                
			}
		});
	}            
}
function printChart(){
	FusionCharts.printManager.enabled(true);
	FusionCharts.printManager.managedPrint();
}
function getIndividualChartUrl(chart,ignoreElevi,ignoreFields){
	
	url = '/process/graficeIndividuale/elev/'+document.elev_selectat+'/materie/'+dijit.byId('materiigrf').value+'/tip/'+(dijit.byId('noter').getValue()?dijit.byId('noter').getValue():'absente')+'/comparativ/'+dijit.byId('comparativgrf').value+'/'+(dijit.byId('altaclasagrf').value>0?'alta_clasa/'+dijit.byId('altaclasagrf').value+'/':'')+(dijit.byId('altelevgrf') && dijit.byId('altelevgrf').value>0 ?'altelev/'+dijit.byId('altelevgrf').value+'/':'');
	
	if(document.lastChartUrl==url) return false;
	
	if(!ignoreFields){
		if(dijit.byId('materiigrf').value==0 && dijit.byId('comparativgrf').value==0 && dijit.byId('noter').getValue()=='note'){
			chart=chart.clone({swfUrl:"/scripts/charts/fusioncharts.com/Column2D.swf"});
		}
		else if(dijit.byId('absenter').attr("checked")) chart=chart.clone({swfUrl:"/scripts/charts/fusioncharts.com/MSBar2D.swf"});
		else chart=chart.clone({swfUrl:"/scripts/charts/fusioncharts.com/MSLine.swf"});
				
		if(typeof altelevgrfStore !="undefined" && !ignoreElevi){
			alta_clasa=false;
			dojo.forEach(url.split("/"),function(item){
				if(item=="alta_clasa") return alta_clasa=true;
				if(isInt(item) && item>0 && alta_clasa && item && item!=document.clasa_selectata){
					if(altelevgrfStore.url!="/process/getEleviListJson/clasa/"+item+"/"){
						document.clasa_selectata=item;
						altelevgrfStore.url="/process/getEleviListJson/clasa/"+item+"/";
						altelevgrfStore.close();
						dijit.byId("altelevgrf").set('value','0');
					}
				}
				 
			});
		}
		
	}
		
	dojo.xhrPost({
		timeout: 3000,
		url:url,
		load:function(data, ioArgs){
			chart.setDataXML(data);
			chart.render();
			document.lastChartUrl=url;
		}
	});
}    
function getGlobalAnStudiuChartUrl(chart){
	
	url = '/process/graficeGlobaleAniStudiu/materie/'+dijit.byId('materiigrf').value+'/tip/'+(dijit.byId('noter').getValue()?dijit.byId('noter').getValue():'absente')+'/an1/'+dijit.byId('an1').value+'/an2/'+dijit.byId('an2').value+'/';
	
	if(dijit.byId('absenter').attr("checked")) chart=chart.clone({swfUrl:"/scripts/charts/fusioncharts.com/MSBar2D.swf"});
	else chart=chart.clone({swfUrl:"/scripts/charts/fusioncharts.com/MSLine.swf"});

	dojo.xhrPost({
		timeout: 3000,
		url:url,
		load:function(data, ioArgs){
			chart.setDataXML(data);
			chart.render();
			document.lastChartUrl=url;
		}
	});
}
function getGlobalChartUrl(chart){
	
	url = '/process/graficeGlobale/clasa/'+document.clasa_selectata+'/materie/'+dijit.byId('materiigrf').value+'/tip/'+(dijit.byId('noter').getValue()?dijit.byId('noter').getValue():'absente')+'/comparativ/'+dijit.byId('comparativgrf').value+'/'+(dijit.byId('altaclasagrf').value>0?'alta_clasa/'+dijit.byId('altaclasagrf').value+'/':'');

	if(document.lastChartUrl==url) return false;
	
	if(dijit.byId('absenter').attr("checked")) chart=chart.clone({swfUrl:"/scripts/charts/fusioncharts.com/MSBar2D.swf"});
	else chart=chart.clone({swfUrl:"/scripts/charts/fusioncharts.com/MSLine.swf"});
			
	dojo.xhrPost({
		timeout: 3000,
		url:url,
		load:function(data, ioArgs){
			chart.setDataXML(data);
			chart.render();
			document.lastChartUrl=url;
		}
	});
}
function orarCheckAcceptance(){return true;}
function onDraggingOverOrar(){lastSource=this;}
function getIconClass(item,opened){
	if(item.type=='parent') return "treeMaterieIcon";
	if(item.type=='child') return "treeProfesorIcon";
	if(item.id_materie) return "treeProfesorIcon";
	return "treeMaterieIcon";
}

// 122 = alt + z
dojo.connect(document,'onkeypress',function(evt){
	document.eventKeyCode = evt.charCode;
});
dojo.connect(document,'onkeyup',function(evt){
	document.eventKeyCode = 0;
});

function doDropOnOrar(source, nodes, copy){
	
    //dojo.cookie("sortMaterii"+document.id_clasa, '', {expires:-1, path:'/'});
    
    dojo.xhrPost({
        url:"/process/",
        content:{
            'action':'deleteSortMaterii',
            'id_clasa':document.id_clasa
        }
    });
    
    
	checkEmptyCell=lastSource.getAllNodes()[0];
	
	if(source.node.id!="profesoriTree"){
		if(this != source){
			
			this.before=true;
			this.onDropExternal(source, nodes, copy);

			dojo.map(nodes, function(node){ document.movableObj=node; });
			dojo.map(lastSource.getAllNodes(), function(node){
												
				// copiere sau mai multe materii in celula
				if(document.movableObj.id!=node.id){
					
					// copiere ora
					if(copy){
						
						dojo.byId(lastSource.node.id).appendChild(node);
						lastSource.before=true;
						lastSource.insertNodes(false, [node], true, null);
											
						post={
							action:"editOrar",
							id_clasa:document.id_clasa,
							id_interval:dojo.attr(lastSource.node,"id_interval"),
							zi:dojo.attr(lastSource.node,"zi"),
							id_materie:dojo.attr(node,"id_materie"),
							id_profesor:dojo.attr(node,"id_profesor")
						}
						lastSource.selectNone();
						dojo.removeClass(lastSource.getAllNodes()[0],"dojoDndItemAfter");
					}
					
					// interschimbare ora
					else{
												
						if(document.eventKeyCode == 122){ // pentru mai multe ore in acelasi interval
							
							post={
								action:"editOrar",
								id_clasa:document.id_clasa,
								id_interval:dojo.attr(lastSource.node,"id_interval"),
								zi:dojo.attr(lastSource.node,"zi"),
								id_materie:dojo.attr(node,"id_materie"),
								old_id_interval:dojo.attr(source.node,"id_interval"),
								old_zi:dojo.attr(source.node,"zi")
							}
							dojo.xhrPost({url:"/process/",content:post});
 
							return true;
							
						}
												
						if(lastSource.getAllNodes().length == 1){ // aici testam daca sunt mai multe materii in celula
							lastSource.delItem(node.id); // sterge celula in care se face drop
							dojo.byId(source.node.id).appendChild(node);
						}
						source.before=true;
						source.insertNodes(false, [node], true, null); // si aici se introduce materia peste care s-a facut drop (la switch)
						
						//post.multiple = 1;
						//lastSource.getAllNodes()
															
						post={
							action:"editOrar",
							id_clasa:document.id_clasa,
							id_interval:dojo.attr(source.node,"id_interval"),
							zi:dojo.attr(source.node,"zi"),
							id_materie:dojo.attr(node,"id_materie"),
							id_profesor:dojo.attr(node,"id_profesor")
						}
												
						source.selectNone();
						dojo.removeClass(source.getAllNodes()[0],"dojoDndItemAfter");
												
					}
					
				}
				
				// muta materia in celula goala
				else{
										
					post={
						action:"editOrar",
						id_clasa:document.id_clasa,
						id_interval:dojo.attr(lastSource.node,"id_interval"),
						zi:dojo.attr(lastSource.node,"zi"),
						id_materie:dojo.attr(node,"id_materie"),
						old_id_interval:dojo.attr(source.node,"id_interval"),
						old_zi:dojo.attr(source.node,"zi"),
						id_profesor:dojo.attr(node,"id_profesor"),
						old_id_materie:''
					}

					if(lastSource.getAllNodes().length>1){
						post.old_id_interval=null;
						post.old_zi=null;
						post.old_id_materie=null;
					}
				}
				
				dojo.xhrPost({url:"/process/",content:post});
								
			});
			
		}else this.onDropInternal(nodes, copy);
		
		return true;
	}
	
	// when dragging from the tree
	var dataItems = dojo.map(nodes, function(node){
		
		var item = source.getItem(node.id);
		
		if(item.data._iconClass=="treeProfesorIcon"){
			
			cell=dojo.byId(lastSource.node.id);
			rnd=Math.floor(Math.random()*1001);                        
			html="<div class='dojoDndItem' id='"+rnd+"' id_materie='"+item.data.item.id_materie+"' id_profesor='"+item.data.item.id+"'>"+item.data.item.nume_materie+"</div><br class='offset'>";
			
			post={
				action:"editOrar",
				id_clasa:document.id_clasa,
				id_interval:dojo.attr(lastSource.node,"id_interval"),
				zi:dojo.attr(lastSource.node,"zi"),
				id_materie:item.data.item.id_materie,
				id_profesor:item.data.item.id
			}
						
			if(checkEmptyCell && document.eventKeyCode != 122){ // cand se trage peste o materie deja existenta
				post.old_id_interval=dojo.attr(lastSource.node,"id_interval");
				post.old_id_materie=dojo.attr(checkEmptyCell,"id_materie");
				post.old_zi=dojo.attr(lastSource.node,"zi");
			}
			
			if(document.eventKeyCode == 122) post.multiple = 1;  
			
			dojo.xhrPost({url:"/process/",content:post});
			
			var tmpTooltip = dijit.byId("orar-tooltip-"+item.data.item.id_materie+"-"+item.data.item.id);
			if(!tmpTooltip){
				cell.innerHTML=(document.eventKeyCode==122?cell.innerHTML:'')+html+"<div style='display:none' dojoType='dijit.Tooltip' showDelay='50' id='orar-tooltip-"+item.data.item.id_materie+"-"+item.data.item.id+/**/rnd/**/+"' connectId='"+rnd+"' position='after'>"+item.data.item.label+"</div>";
				dojo.parser.parse(cell);
			}
			else{
				cell.innerHTML=(document.eventKeyCode==122?cell.innerHTML:'')+html;
				tmpTooltip.addTarget(dojo.query("#"+rnd)[0]);
			}
			
			lastSource.insertNodes(false, [dojo.query("#"+rnd)[0]], true, null);
			
			/*
			corelat cu filtrareProfesoriOrar()
			dojo.map(item.data.getParent().getDescendants(), function(row){
					if(row.domNode.id!=item.data.domNode.id) row.domNode.style.display="none";
				}
			);*/
			
		}
	});
}
function filtrareProfesoriOrar(){
	dojo.forEach(profesoriTree.rootNode.getDescendants(), function(_materii){
			if(typeof _materii.item.children == "object" && materii.in_array(_materii.item.id)){
				dojo.forEach(_materii.getChildren(), function(_profesori){
						if(!profesori.in_array(_profesori.item.id)) _profesori.domNode.style.display="none";
					}
				)
			}
		}
	);
}
function doRemoveOrar(source, nodes, copy){
	this.onDropExternal(source, nodes, copy);
	this.clearItems();
	this.node.innerHTML='';
	dojo.map(nodes, function(node){ id_materie=dojo.attr(node,"id_materie"); });
	dojo.xhrPost({
		url:"/process/",
		content:{
			action:"removeOra",
			id_materie:id_materie,
			id_clasa:document.id_clasa,
			id_interval:dojo.attr(source.node,"id_interval"),
			zi:dojo.attr(source.node,"zi")
		},
		load:function(data, ioArgs){
			eval(data);	
		}
	});
	box=dojo.byId("notice");
	box.innerHTML="Sters cu success";
	coords=dojo.coords(dojo.byId("orarTrash"));
	box.style.top=coords.y+30+"px";
	box.style.left=coords.x-23+"px";
	box.style.opacity=1;
	dojo.fx.combine([dojo.fx.slideTo({node:box,top:coords.y-100,left:coords.x-23,duration:1500}),dojo.fadeOut({node:box,duration:1500,onEnd: function(){box.style.top="-9999px";}})]).play();
	box.style.top="-9999px";
}
function enableClaseEdit(id){
	if(!id) return false;
	toggleDisplay(dojo.byId('tr'+id));
	toggleDisplay(dojo.byId('trn'+id));
	validateClaseEdit(id);
} 
function enableMaterieEdit(id){
	if(!id) return false;
	toggleDisplay(dojo.byId('tr'+id));
	toggleDisplay(dojo.byId('trn'+id));
}
function enableAlocareSmsEdit(id){
	if(!id) return false;
	toggleDisplay(dojo.byId('tr'+id));
	toggleDisplay(dojo.byId('trn'+id));
} 
function enableProfilEdit(id){
	if(!id) return false;
	toggleDisplay(dojo.byId('tr'+id));
	toggleDisplay(dojo.byId('trn'+id));
}
function validateClaseEdit(id){
	 if(dijit.byId('profile'+id).isValid() && dijit.byId('profile'+id).attr("value") && dijit.byId('toti_profesorii'+id).isValid() && dijit.byId('toti_profesorii'+id).attr("value") && dijit.byId('acr'+id).isValid() && dijit.byId('nr'+id).isValid()) dijit.byId('save'+id).attr('disabled',false);
	 else dijit.byId('save'+id).attr('disabled',true);
}
function validateMaterieEdit(id){
	 if(dijit.byId('nume'+id).isValid()) dijit.byId('save'+id).attr('disabled',false);
	 else dijit.byId('save'+id).attr('disabled',true);
}
function validateAlocareSmsEdit(id){
	 if(dijit.byId('sms_alocat'+id).isValid()) dijit.byId('save'+id).attr('disabled',false);
	 else dijit.byId('save'+id).attr('disabled',true);
}
function validateProfilEdit(id){
	 if(dijit.byId('nume'+id).isValid()) dijit.byId('save'+id).attr('disabled',false);
	 else dijit.byId('save'+id).attr('disabled',true);
}
function validateClase(){
	if(dijit.byId('addacr').isValid() && dijit.byId('addnr').isValid() && dijit.byId('addtoti_profesorii').isValid() && dijit.byId('addtoti_profesorii').attr("value") && dijit.byId('addprofile').isValid() && dijit.byId('addprofile').attr("value")) dijit.byId('add').attr('disabled',false);
	 else dijit.byId('add').attr('disabled',true);
}
function validateElevi(){
	if(dojo.byId('id_clasa').value && dijit.byId('nr_matricol').isValid() && dijit.byId('nume').isValid() && dijit.byId('prenume').isValid()) dijit.byId('add').attr('disabled',false);
	 else dijit.byId('add').attr('disabled',true);
}
function validateProfesori(){
	if(dojo.byId('id_unitate').value && dojo.byId('id_materii').value && dijit.byId('nume').isValid() && dijit.byId('prenume').isValid()) dijit.byId('add').attr('disabled',false);
	else dijit.byId('add').attr('disabled',true);
}
function validatePlata(){
		
	if(dijit.byId('plataForm').isValid()){
		 dojo.xhrPost({
			form: "plataForm",
			load:function(data, ioArgs){dijit.byId('plataHolder').hide();location.reload();}});
	}
}
function validateAnunt(){
	if(dojo.byId('id_unitate').value && dijit.byId('data').isValid() && dijit.byId('text').getDisplayedValue()) dijit.byId('add').attr('disabled',false);
	else dijit.byId('add').attr('disabled',true);
}
function processClaseEdit(id){
	
	dijit.byId('save'+id).attr("label","Se salveaza");
	dijit.byId('cancel'+id).attr("disabled",true);
	
	var content = {
		action:"editareClasa",
		nume:dijit.byId('nr'+id)+' '+dijit.byId('acr'+id).attr('value'),
		id_profil:dijit.byId('profile'+id),
		id_diriginte:dijit.byId('toti_profesorii'+id),
		id:id
	}
	
	dojo.byId('diriginte'+id).innerHTML=content.id_diriginte._getDisplayedValueAttr();
	dojo.byId('profil'+id).innerHTML=content.id_profil._getDisplayedValueAttr();
	dojo.byId('nume'+id).innerHTML=content.nume;
		
	dojo.xhrPost({
		url: '/process/',
		content:content,
		load:function(data, ioArgs){
			dijit.byId('save'+id).attr("label","Salveaza");
			dijit.byId('cancel'+id).attr("disabled",false);
			enableClaseEdit(id);
		}
	});
	
}
function processMaterieEdit(id){
	dijit.byId('save'+id).attr("label","Se salveaza");
	dijit.byId('cancel'+id).attr("disabled",true);
	
	var content = {
		action:"editareMaterie",
		nume:dijit.byId('nume'+id).attr('value'),
		id:id
	}
	
	dojo.xhrPost({
		url: '/process/',
		content:content,
		load:function(data, ioArgs){
			dijit.byId('save'+id).attr("label","Salveaza");
			dijit.byId('cancel'+id).attr("disabled",false);
			enableMaterieEdit(id);
			dojo.byId('nume'+id).innerHTML=content.nume;
			dijit.byId('nume'+id).attr("value",content.nume);
		}
	});
	
}
function processAlocareSmsEdit(id){
	dijit.byId('save'+id).attr("label","Se salveaza");
	dijit.byId('cancel'+id).attr("disabled",true);
		
	dojo.xhrPost({
		url: '/process/',
		content:{
			action:"editareAlocareSms",
			sms_alocat:dijit.byId('sms_alocat'+id).attr('value'),
			sms_disponibil:dojo.byId('sms_disponibil'+id).value,
			id:id
		},
		load:function(data, ioArgs){
			location.reload()
		}
	});    
}
function processAlocareSmsManagerEdit(id){
	dijit.byId('save'+id).attr("label","Se salveaza");
	dijit.byId('cancel'+id).attr("disabled",true);
		
	dojo.xhrPost({
		url: '/process/',
		content:{
			action:"editareAlocareSmsManager",
			sms_alocat:dijit.byId('sms_alocat'+id).attr('value'),
			sms_disponibil:dojo.byId('sms_disponibil'+id).value,
			id:id
		},
		load:function(data, ioArgs){
			location.reload()
		}
	});    
}
function processProfilEdit(id){
	dijit.byId('save'+id).attr("label","Se salveaza");
	dijit.byId('cancel'+id).attr("disabled",true);
	
	var content = {
		action:"editareProfil",
		nume:dijit.byId('nume'+id).attr('value'),
		id:id
	}
	
	dojo.xhrPost({
		url: '/process/',
		content:content,
		load:function(data, ioArgs){
			dijit.byId('save'+id).attr("label","Salveaza");
			dijit.byId('cancel'+id).attr("disabled",false);
			enableProfilEdit(id);
			dojo.byId('nume'+id).innerHTML=content.nume;
		}
	});
	
}
function addClasa(id_unitate){
	if(dijit.byId('addacr').isValid() && dijit.byId('addnr').isValid() && dijit.byId('addtoti_profesorii').isValid() && dijit.byId('addprofile').isValid()){
		dijit.byId('add').attr("label","Salveaza");
		dijit.byId('add').attr("disabled",true);
		dojo.xhrPost({
			url: '/process/',content:{
				action:"adaugareClasa",
				nume:dijit.byId('addnr')+' '+dijit.byId('addacr').attr('value'),
				id_profil:dijit.byId('addprofile'),
				id_unitate:id_unitate,
				id_diriginte:dijit.byId('addtoti_profesorii')
			},
			load:function(data, ioArgs){
				location.reload();
			}
		});
	} 
	else return false;
}
function addMaterie(id_unitate){
	if(dijit.byId('nume').isValid()){
		dijit.byId('add').attr("label","Salveaza");
		dijit.byId('add').attr("disabled",true);
		dojo.xhrPost({
			url: '/process/',
			content:{
				action:"adaugareMaterie",
				nume:dijit.byId('nume').attr('value'),
				id_unitate:id_unitate
			},
			load:function(data, ioArgs){
				location.reload();
			}
		});
	} 
	else return false;
}
function addProfil(id_unitate){
	if(dijit.byId('nume').isValid()){
		dijit.byId('add').attr("label","Salveaza");
		dijit.byId('add').attr("disabled",true);
		dojo.xhrPost({
			url: '/process/',
			content:{
				action:"adaugareProfil",
				nume:dijit.byId('nume').attr('value'),
				id_unitate:id_unitate
			},
			load:function(data, ioArgs){
				location.reload();
			}
		});
	} 
	else return false;
}
function validateMaterie(){
	if(dijit.byId('nume').isValid()) dijit.byId('add').attr('disabled',false);
	 else dijit.byId('add').attr('disabled',true);
}
function validateProfil(){
	if(dijit.byId('nume').isValid()) dijit.byId('add').attr('disabled',false);
	 else dijit.byId('add').attr('disabled',true);
}
function addElev(){
	if(dojo.byId('id_clasa').value && dijit.byId('nume').isValid() && dijit.byId('prenume').isValid() && dijit.byId('nr_matricol').isValid()){
		dijit.byId('add').attr("label","Salveaza");
		dijit.byId('add').attr("disabled",true);
		dojo.xhrPost({
			url: '/process/',
			content:{
				action:"adaugareElev",
				nume:dijit.byId('nume').attr("value"),
				prenume:dijit.byId('prenume').attr("value"),
				nr_matricol:dijit.byId('nr_matricol').attr("value"),
				id_clasa:dojo.byId('id_clasa').value,
				id_unitate:dojo.byId('id_unitate').value
			},
			load:function(data, ioArgs){location.reload();}});
	} 
	else return false;
}
function addAnunt(){
	if(dojo.byId('id_unitate').value && dijit.byId('data').isValid() && dijit.byId('text').value){
		dijit.byId('add').attr("label","Salveaza");
		dijit.byId('add').attr("disabled",true);
		dojo.xhrPost({
			url: '/process/',
			content:{
				action:"adaugareAnunt",
				data:dijit.byId('data').getDisplayedValue(),
				text:dijit.byId('text').attr("value"),
				id_unitate:dojo.byId('id_unitate').value
			},
			load:function(data, ioArgs){location.reload();}});
	} 
	else return false;
}
function addProfesor(){
	if(dojo.byId('id_unitate').value && dojo.byId('id_materii').value && dijit.byId('nume').isValid() && dijit.byId('prenume').isValid()){
		dijit.byId('add').attr("label","Salveaza");
		dijit.byId('add').attr("disabled",true);
		dojo.xhrPost({
			url: '/process/',
			content:{
				action:"adaugareProfesor",
				nume:dijit.byId('nume').attr("value"),
				prenume:dijit.byId('prenume').attr("value"),
				id_materii:dojo.byId('id_materii').value,
				id_unitate:dojo.byId('id_unitate').value
			},
			load:function(data, ioArgs){location.reload();}});
	} 
	else return false;
}
function deleteClasa(id){
	if(confirm("Sunteti sigur ca vreti sa stergeti aceasta clasa ?")){
		if(confirm("ATENTIE !!! \nClasa urmeaza sa fie stearsa impreuna cu toti elevi aferenti ! \nSunteti foarte sigur ca vreti sa continuati ?")){
			dojo.xhrPost({
				url: '/process/',
				content:{
					action:"stergereClasa",
					id:id
				},
				load:function(data, ioArgs){
					location.reload();
				}
			});
		}
	}
}
function deleteMaterie(id){
	if(confirm("Sunteti sigur ca vreti sa stergeti aceasta materie ?")){
		if(confirm("ATENTIE !!! \nMateria urmeaza sa fie stearsa, putand cauza inconsistenta a datelor! \nSunteti foarte sigur ca vreti sa continuati ?")){
			dojo.xhrPost({
				url: '/process/',
				content:{
					action:"stergereMaterie",
					id:id
				},
				load:function(data, ioArgs){
					location.reload();
				}
			});
		}
	}
}
function deleteProfil(id){
	if(confirm("Sunteti sigur ca vreti sa stergeti aceasta specializare ?")){
		if(confirm("ATENTIE !!! \nSpecializarea urmeaza sa fie stears ! \nSunteti foarte sigur ca vreti sa continuati ?")){
			dojo.xhrPost({
				url: '/process/',
				content:{
					action:"stergereProfil",
					id:id
				},
				load:function(data, ioArgs){
					location.reload();
				}
			});
		}
	}
}
function deleteElev(id,id_elev){
	if(confirm("Sunteti sigur ca vreti sa stergeti aceast elev ?")){
		if(confirm("ATENTIE !!! \nElevul urmeaza sa fie stearsa impreuna cu toate notele si absentele aferente ! \nSunteti foarte sigur ca vreti sa continuati ?")){
			dojo.xhrPost({url: '/process/',content:{action:"stergereElev",id:id,id_elev:id_elev},load:function(data, ioArgs){location.reload();}});
		}
	}
}
function deleteProfesor(id){
	if(confirm("Sunteti sigur ca vreti sa stergeti aceast Profesor ?")){
		if(confirm("ATENTIE !!! \nProfesorul urmeaza sa fie stearsa impreuna cu toate datele aferente ! \nSunteti foarte sigur ca vreti sa continuati ?")){
			dojo.xhrPost({url: '/process/',content:{action:"stergereProfesor",id:id},load:function(data, ioArgs){location.reload();}});
		}
	}
}
function deleteManager(id){
	if(confirm("Sunteti sigur ca vreti sa stergeti aceast Data Admin ?")){
		if(confirm("ATENTIE !!! \nData Admin urmeaza sa fie stearsa impreuna cu toate datele aferente ! \nSunteti foarte sigur ca vreti sa continuati ?")){
			dojo.xhrPost({url: '/process/',content:{action:"stergereManager",id:id},load:function(data, ioArgs){location.reload();}});
		}
	}
}
function deleteParinte(id){
	if(confirm("Sunteti sigur ca vreti sa stergeti aceast Parinte ?")){
		if(confirm("ATENTIE !!! \nParintele urmeaza sa fie stearsa impreuna cu toate datele aferente ! \nSunteti foarte sigur ca vreti sa continuati ?")){
			dojo.xhrPost({url: '/process/',content:{action:"stergereParinte",id:id},load:function(data, ioArgs){location.href=location.href;}});
		}
	}
}
function sortMaterii(id_clasa){
    var nodes = sortable.getAllNodes()
    var order = [];
    dojo.map(nodes, function(node){
        order.push(node.id.substring(4));
    });
    
    //dojo.cookie("sortMaterii"+id_clasa, order.join(","), {expires:365, path:'/'});

    dojo.xhrPost({
        url:"/process/",
        content:{
            'action':'processSortMaterii',
            'sort_text':order.join(","),
            'id_clasa':id_clasa
        }
    });
                

}
function deleteAnunt(id){
	if(confirm("Sunteti sigur ca vreti sa stergeti aceast Anunt ?")) dojo.xhrPost({url: '/process/',content:{action:"stergereAnunt",id:id},load:function(data, ioArgs){location.reload();}});       
}
function trimiteSMS(id_elev,data,obj){
	dijit.byId(obj.id).attr('disabled',true).attr('label','Se trimite ...');
	dojo.xhrPost({
		url: '/process/',
		content:{
			action:"trimiteSMS",
			id_elev:id_elev,
			data:data
		},
		load:function(data, ioArgs){
			//alert(data);
			dijit.byId(obj.id).attr('disabled',false).attr('label','Trimite SMS');
		}
	});
}
function populateMaterii(){
	ids = new Array();
	dojo.forEach(dijit.byId('materiiTreeHolder').getChildren(),function(item){
		if(item.attr("checked")) ids[ids.length]=item.id;
	});
	dojo.byId('id_materii').value=ids.join(",");
}
function editarePermisiune(obj){
	dojo.xhrPost({
		url: '/process/',
		content:{
			action:"editarePermisiune",
			permisiune:obj.value,
			add:obj.checked
		} 
	})
}
function selectAllCheckbox(){
	nodes = dojo.query('[type="checkbox"]');
	dojo.forEach(nodes,
		function(node){
			if(node.disabled) return;
			selectElevCheckbox(node,true);
		}
	);
		
}
function selectElevCheckbox(_obj,forceCheck){
	
	i = parseInt(dojo.byId("nr_selectate").value);
	obj = dijit.byId(_obj.id);
	var smsToSend = 0;
	
	if(typeof obj == "undefined"){
		obj = dojo.byId(_obj.id+"");
		if(forceCheck && !obj.checked) obj.checked=true;
		if(!forceCheck){
			if(obj.checked) i++;
			else i--;
		}	
	}
	else{			
		if(forceCheck && !obj.attr("checked")) obj.attr("checked",true);
		if(!forceCheck){
			if(obj.attr("checked")) i++;
			else i--;
		}
	}
	
	if ( i > parseInt(dojo.byId("nr_sms_disponibil").value) ){
		smsToSend = '<b class="red">'+i+'</span>'; 
		dijit.byId('trmiteSmsBtn').attr('disabled',true);   
	}else smsToSend = i;     
	
	dojo.byId('smsToSend').innerHTML = smsToSend;
	dojo.byId("nr_selectate").value = i;
	
	if( i < 1 ) dijit.byId('trmiteSmsBtn').attr('disabled',true);    
	
	countTextChars();
}
function countTextChars(){
	max_length = 160;
	current_nr_str = '0';
	current_nr = dojo.byId('smsText').value.length;
	i = parseInt(dojo.byId("nr_selectate").value);
	
	if( current_nr > max_length ) {
		current_nr_str = '<b class="red">'+current_nr+'</span>';
		dijit.byId('trmiteSmsBtn').attr('disabled',true);
	}
	else{
		dijit.byId('trmiteSmsBtn').attr('disabled',false);;
	}
	
	if( current_nr < 10 || i == 0 || i > parseInt(dojo.byId("nr_sms_disponibil").value)  ) dijit.byId('trmiteSmsBtn').attr('disabled',true);
	
	dojo.byId('countChars').innerHTML = current_nr;
}
function toggleShowing(val){
	if(val == 2){
		dojo.style("comp_by_clase", "display", '');
		dojo.style("comp_by_clase_and_elev", "display", '');
	}else{
		dojo.style("comp_by_clase", "display", "none");
		dojo.style("comp_by_clase_and_elev", "display", "none");
	}    
}
function toggleShowing_2(val){
	if(val == 2){
		dojo.style("comp_by_clase", "display", '');
	}else{
		dojo.style("comp_by_clase", "display", "none");
	}    
}
function getEleviList(id_clasa){
	
}
function selectPermisCheckbox(obj,elem_id,status){

	obj = dijit.byId(obj.id);
	elem_id = dijit.byId(elem_id);
	
	try{
		if(status == 1){
			if(obj.attr("checked")){
				elem_id.attr("checked",true);
			}
		}else{
			if(!obj.attr("checked")){
				elem_id.attr("checked",false);
			}  
		}	
	}catch(e){}
		
}
function addNewInterval(){
	i++;
	text = '<td align="center"><input style="width:30px" type="text" name="interval['+i+'][0]" id="interval_'+i+'_0" value="" regExp="[0-9]+" dojoType="dijit.form.ValidationTextBox" maxLength="2" trim="true"> : <input style="width:30px" type="text" name="interval['+i+'][1]" id="interval_'+i+'_1" value="" regExp="[0-9]+" dojoType="dijit.form.ValidationTextBox" maxLength="2" trim="true"></td><td>&nbsp;&nbsp; - &nbsp;&nbsp;</td><td align="center"><input style="width:30px" type="text" name="interval['+i+'][2]" id="interval_'+i+'_2" value="" regExp="[0-9]+" dojoType="dijit.form.ValidationTextBox" maxLength="2" trim="true"> : <input style="width:30px" type="text" name="interval['+i+'][3]" id="interval_'+i+'_3" value="" regExp="[0-9]+" dojoType="dijit.form.ValidationTextBox" maxLength="2" trim="true"></td>';
	
	dojo.place('<tr>'+text+'</tr>', 'theLast', 'before');
	dojo.parser.parse();
}
function verificarePlataSMS(){
	verificarePlata.show();
	dojo.style(verificarePlata.closeButtonNode,"display","none");
	var interval = 2000; // 2 sec
	var i = 0;
	setInterval(function(){
		i += interval;
		dojo.xhrPost({
			url: '/process/',
			content:{
				action:"verificarePlataSMS",
			},
			load:function(data, ioArgs){
				if(data==404){
					verificarePlata.set("title","Verificare esuata !");
					verificarePlata.set("content","<center>Va rugam efectuati pasii de mai sus apoi dati click pe butonul '<b>Am finalizare plata !</b>'<br><br> <input type='button' dojoType='dijit.form.Button' onclick='location.reload()' label='Ok'></center>");
					return false; 
				}
				if(data==1 || i>=64000) location.href='/admin/plata/pas/3/';
			}
		});
	},interval);
}

// for Admin only

function enableUnitatiEdit(id){
	if(!id) return false;
	toggleDisplay(dojo.byId('tr'+id));
	toggleDisplay(dojo.byId('trn'+id));
}

function validateUnitatiEdit(id){
	if(dijit.byId('nume'+id).isValid() && dijit.byId('nume_scurt'+id).isValid() && dijit.byId('oras'+id).attr("value") && dijit.byId('tip'+id).isValid() && dijit.byId('tip'+id).attr("value") && dijit.byId('sms_alocat'+id).isValid() ) dijit.byId('save'+id).attr('disabled',false);
	else dijit.byId('save'+id).attr('disabled',true);
}

function validateUnitati(id){
	if(dijit.byId('nume').isValid() && dijit.byId('nume_scurt').isValid() && dijit.byId('oras').attr("value") && dijit.byId('tip').attr("value") && dijit.byId('tip').isValid() && dijit.byId('sms_alocat').isValid() ) dijit.byId('add').attr('disabled',false);
	else dijit.byId('add').attr('disabled',true);
}

//adaugare unitate
function addUnitate(){
	if( dijit.byId('nume').isValid() && dijit.byId('nume_scurt').isValid() && dijit.byId('oras').attr("value") && dijit.byId('tip').isValid() && dijit.byId('sms_alocat').isValid() ){
		dijit.byId('add').attr("label","Salveaza");
		dijit.byId('add').attr("disabled",true);
		dojo.xhrPost({
			url: '/process/',
			content:{
				action:"adaugareUnitate",
				nume:dijit.byId('nume').attr('value'),
				nume_scurt:dijit.byId('nume_scurt').attr('value'),
				oras:dijit.byId('oras').attr('value'),
                tip:dijit.byId('tip').attr('value'),
				notificare:dijit.byId('notificare').attr('checked'),
				sms_alocat:dijit.byId('sms_alocat').attr('value')
				
			},
			load:function(data, ioArgs){
				location.reload();
			}
		});
	} 
	else return false;
}

function selectAllMultiselect(obj){
    try{
        var childs = dijit.byId(obj).domNode.childNodes;
        for(i=0;i<childs.length;i++){
            if(childs[i].tagName == "OPTION") childs[i].selected = true;
        }
    }catch(e){}    
}

function processUnitatiEdit(id){
	
	dijit.byId('save'+id).attr("label","Se salveaza");
	dijit.byId('cancel'+id).attr("disabled",true);
	
	var content={
		action:"editareUnitate",
		id:id,
		nume:dijit.byId('nume'+id).attr('value'),
		nume_scurt:dijit.byId('nume_scurt'+id).attr('value'),
		oras:dijit.byId('oras'+id).attr('value'),
        tip:dijit.byId('tip'+id).attr('value'),
		notificare:dijit.byId('notificare'+id).attr('checked'),
		sms_alocat:dijit.byId('sms_alocat'+id).attr('value')
	}
	
	dojo.xhrPost({
		url: '/process/',
		content:content,
		load:function(data, ioArgs){
			dijit.byId('save'+id).attr("label","Salveaza");
			dijit.byId('cancel'+id).attr("disabled",false);
			enableUnitatiEdit(id);
			
			dojo.byId('nume'+id).innerHTML=content.nume;
			dijit.byId('nume'+id).attr("value",content.nume);
			
			dojo.byId('nume_scurt'+id).innerHTML=content.nume_scurt;
			dijit.byId('nume_scurt'+id).attr("value",content.nume_scurt);
			
			dojo.byId('oras'+id).innerHTML=content.oras;
			dijit.byId('oras'+id).attr("value",content.oras);
            
            dojo.byId('notificare'+id).innerHTML=content.notificare?"da":"nu";
            dijit.byId('notificare'+id).attr("checked",content.notificare);
			
			dojo.byId('sms_alocat'+id).innerHTML=content.sms_alocat;
			dijit.byId('sms_alocat'+id).attr("value",content.sms_alocat);
			
			dojo.byId('tip'+id).innerHTML=content.tip;
			dojo.byId('tip'+id).innerHTML=content.tip._getDisplayedValueAttr();
			
 
		}
	});    
	
	
}

function startDemoByUnitate(id){
	if(confirm("Sunteti sigur ca vreti sa activati demo(trial) pentru aceasta unitate ?")){
		dojo.xhrPost({
			url: '/process/',
			content:{
				action:"startDemo",
				id:id
			},
			load:function(data, ioArgs){
				location.reload();
			}
		});
	}
}

function seteazaIntervalePlata(luna1,luna2,luna3,sfSem,sfAn){
	dijit.byId("1_luna").attr("value",luna1);
	dijit.byId("2_luni").attr("value",luna2);
	dijit.byId("3_luni").attr("value",luna3);
	dijit.byId("semestrul_in_curs").attr("value",sfSem);
	dijit.byId("anul_in_curs").attr("value",sfAn);    
}


// common
function calculeazaTotalPlata(obj){
   
	var val = obj.attr('value');
	
	if(typeof val != "boolean"){
		var parts = val.split(".");
		val = parts[0];
		var end = parts[1];
		dojo.byId("pretTotal").innerHTML = Math.floor(val*dojo.byId("pret_zi").value);
		dojo.byId("interval").value = obj.id;
		dojo.byId("numar_zile").innerHTML = val;
		dojo.byId("sfarsit").innerHTML = end.substring(6,8)+"."+end.substring(4,6)+"."+end.substring(0,4);
	}
}


//need to send referer in IE
function navWithRef(url){
	if(typeof(dojo.isIE) == 'undefined') location.href = url;  // sends referrer in FF, not in IE
	else{
		var fakeLink = document.createElement("a");
		fakeLink.href = url;
		document.body.appendChild(fakeLink);
		fakeLink.click();   // click() method defined in IE only
	}
}