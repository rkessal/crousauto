						menuState = getCookie("class");
						marginState = getCookie("margin");


						function getMaxChildWidth(sel) {
						    max = 0;
						    $(sel).children().each(function(){
						        c_width = parseInt($(this).width());
						        if (c_width > max) {
						            max = c_width;
						        }
						    });
						    return max;
						}
						var footerResize = function()
						{
							var footerRowWidth = $(".footer1").outerWidth() + $(".footer2").outerWidth() + $(".footer3").outerWidth();
	       					var footerWidth = $("#footer").width();

	       					if (footerRowWidth < footerWidth)
		       				{
		       					$("#footer").css("flex-direction", "row");
		       					//console.log("row");
		       				}
		       				else
		       				{
		       					$("#footer").css("flex-direction", "column");
		       					$("#footer").children().css("align-self", "flex-start");

		       					//console.log("column");
		       				}
		       				//console.log(footerRowWidth + " " + footerWidth);
		       				
						}

						$(window).resize(footerResize);
						$(document).ready(footerResize);



						if (menuState === "")
						{
							menuState = "cote-gauche-ferme scroll-width-thin";
							marginState = "80px";
						}
						document.getElementById("cote-gauche").style.display = "block";
						document.getElementById("cote-gauche").style.transition = "0.0s";
						document.getElementById("side-img").style.transition = "0.0s";
						if (menuState === "cote-gauche-ferme scroll-width-thin")
						{
							document.getElementById('menu-icon').src="ressources/responsive/menu-item.png";
							
							document.getElementById('side-img').src="ressources/responsive/besancon-wallpaper-side-responsive.png";
							document.getElementById('top-margin').style.marginLeft = marginState;
							margeGauche = document.getElementById('top-margin').style.marginLeft;
							//console.log(margeGauche);
							//console.log(marginState);
						}

						else
						{
							document.getElementById('menu-icon').src="ressources/responsive/cross-item.png";
							document.getElementById('side-img').src="ressources/menu-image.png";
							
						}

						document.getElementById('cote-gauche').className = menuState;
						document.getElementById('menu-container').className = menuState;

						function setCookie(cname, cvalue, exdays) {
							    var d = new Date();
							    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
							    var expires = "expires="+d.toUTCString();
							    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
							}


						function getCookie(cname) 
						{
						    var name = cname + "=";
						    var decodedCookie = decodeURIComponent(document.cookie);
						    var ca = decodedCookie.split(';');
						    for(var i = 0; i <ca.length; i++) {
						        var c = ca[i];
						        while (c.charAt(0) == ' ') {
						            c = c.substring(1);
						        }
						        if (c.indexOf(name) == 0) {
						            return c.substring(name.length, c.length);
						        }
						    }
						    return "";
						}

						function fonction1()
						{

							

							var $menu   = $("#cote-gauche"), 
					        $window    = $(window),
					        offset     = $menu.offset(),
					        topPadding = 0,
					        $sommaire = $(".sommaire"),
					        offsetSommaire = $sommaire.offset();
					        var offsetFixed = offset.top - $window.scrollTop();
					        if ($window.scrollTop() > offset.top)
						    	{
						    		offsetFixed = 0;
						    	}
					        //console.log(offsetFixed);

					          

							if(document.getElementById('menu-container').className == "cote-gauche-ouvert scroll-width-thin")
							{
								if ($(window).width() > 1007)
								{
									marginStateFooter = "340px";
									setCookie("marginFooter", marginStateFooter, 1);
								}

								else
								{
									marginStateFooter = "60px";
									setCookie("marginFooter", marginStateFooter, 1);
								}
								
								
							}
							else
							{
								marginStateFooter = "60px";
								setCookie("marginFooter", marginStateFooter, 1);
								
							}

							if(document.getElementById('cote-gauche').className == "cote-gauche-ouvert scroll-width-thin")
							{
								menuState = "cote-gauche-ferme scroll-width-thin";
								marginState = "60px";
								marginStateFooter = "60px";
								setCookie("class", menuState, 1);
								setCookie("margin", marginState, 1);
								setCookie("marginFooter", marginStateFooter, 1);
								a = getCookie("marginFooter");
								
								document.getElementById('cote-gauche').className = menuState;
								document.getElementById('menu-container').className = menuState;
								document.getElementById("top-margin").style.marginLeft = marginState;
								document.getElementById("footer").style.marginLeft = marginStateFooter;

								document.getElementById('cote-gauche').style.transition = 0;
								document.getElementById('menu-icon').src="ressources/responsive/menu-item.png";
								document.getElementById('side-img').src="ressources/responsive/besancon-wallpaper-side-responsive.png";
							}

							else
							{
								menuState = "cote-gauche-ouvert scroll-width-thin";
								if ($(window).width() > 1007)
								{
									
									marginState = "320px";
									marginStateFooter = "320px";
								}

								else
								{
									marginState = "60px";
									marginStateFooter = "60px";
								}
								
								setCookie("class", menuState, 1);
								setCookie("margin", marginState, 1);
								setCookie("marginFooter", marginStateFooter, 1);
								document.getElementById('cote-gauche').className = menuState;
								document.getElementById('menu-container').className = menuState;
								document.getElementById("top-margin").style.marginLeft = marginState;
								document.getElementById("footer").style.marginLeft = marginStateFooter;
								document.getElementById('menu-icon').src="ressources/responsive/cross-item.png";
								document.getElementById('side-img').src="ressources/menu-image.png";
							}
							footerResize();
						}

						function getJsVariable(vehicule)
						{
							var vehicule = vehicule;
							document.getElementById("vehicule-hidden").value = "vehicule";
						}



						$(document).ready(function(){


						  $(function() {
							$( "#datepicker, #datepicker2" ).datepicker({
							altField: "#datepicker",
							closeText: 'Fermer',
							prevText: 'Précédent',
							nextText: 'Suivant',
							currentText: 'Aujourd\'hui',
							monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
							monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
							dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
							dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
							dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
							weekHeader: 'Sem.',
							dateFormat: 'yy-mm-dd',
						    changeMonth: true,
						    changeYear: true,
						    gotoCurrent: true
						 });
							$( "#datepickerAvecDateMin" ).datepicker({
							altField: "#datepicker",
							closeText: 'Fermer',
							prevText: 'Précédent',
							nextText: 'Suivant',
							currentText: 'Aujourd\'hui',
							monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
							monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
							dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
							dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
							dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
							weekHeader: 'Sem.',
							dateFormat: 'yy-mm-dd',
						    changeMonth: true,
						    changeYear: true,
						    gotoCurrent: true,
					  		minDate: new Date()
							});
							
						});
						  $('#ui-datepicker-div').css('font-size', '15px');				

						$(".datepicker").keydown(function (event) {
		    				event.preventDefault();
						});
					   
						$("#datepicker").keydown(function (event) {
		    				event.preventDefault();
						});
						$("#datepicker2").keydown(function (event) {
		    				event.preventDefault();
						});
						$(".jscolor").keydown(function (event) {
		    				event.preventDefault();
						});

						if ($(".btn").is(":disabled"))
						{
							$(".btn").hide();
						}
						var url = window.location.href;
						var NomDuFichier = url.substring(url.lastIndexOf( "/" )+1 );

						if (NomDuFichier == 'gestion_statistiques.php')
							{
								//chart.reflow()
							}

						if (NomDuFichier == 'creation_vehicule.php')
						{
							var e = document.getElementById("typeVehicule");
							var type = e.options[e.selectedIndex].value;

							//console.log("Le type est : " + type);

							if (type != "default")
							{
								$("#caracteristiques-vehicule").removeClass("hide");

								if (type == "VL" || type == "Utilitaire" || type == "Bus" || type == "Camion" || type == "Vehicule frigorifique")
								{
									$("#voiture").removeClass("hide");
									$("#entretien").removeClass("hide");
								}

								if (type == "Velo")
								{
									$("#velo").removeClass("hide");
									$("#kilometrage").removeAttr("required");
								}
							}
							$('.location').change(function(){
								if( $('input[name=location]').is(':checked') ){
									$("#entretien").hide();
								}
								else {
									$("#entretien").show();
								}
							});	

						}

						menuState = getCookie("class");
						marginState = getCookie("margin");
						marginStateFooter = getCookie("marginFooter");


						menucontainerWidth = document.getElementById("menu-container").offsetWidth
						//console.log(menucontainerWidth);

						if (menuState === "")
						{
							menuState = "cote-gauche-ferme scroll-width-thin";
							marginState = "60px";
							marginStateFooter = "60px";
						}

						document.getElementById("cote-gauche").style.display = "block";

						if (menuState === "cote-gauche-ferme scroll-width-thin")
						{
							marginStateFooter = "60px";
							setCookie("marginFooter", marginStateFooter, 1);
							document.getElementById('menu-icon').src="ressources/responsive/menu-item.png";
							
							document.getElementById('side-img').src="ressources/responsive/besancon-wallpaper-side-responsive.png";
							
						}

						else
						{
							document.getElementById('menu-icon').src="ressources/responsive/cross-item.png";
							document.getElementById('side-img').src="ressources/menu-image.png";
							marginStateFooter = "320px";
							setCookie("marginFooter", marginStateFooter, 1);
						}

						document.getElementById('cote-gauche').className = menuState;
						document.getElementById('menu-container').className = menuState;
						document.getElementById('footer').style.marginLeft = marginStateFooter;

						function setCookie(cname, cvalue, exdays) {
							    var d = new Date();
							    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
							    var expires = "expires="+d.toUTCString();
							    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
						}

						function getCookie(cname) 
						{
						    var name = cname + "=";
						    var decodedCookie = decodeURIComponent(document.cookie);
						    var ca = decodedCookie.split(';');
						    for(var i = 0; i <ca.length; i++) {
						        var c = ca[i];
						        while (c.charAt(0) == ' ') {
						            c = c.substring(1);
						        }
						        if (c.indexOf(name) == 0) {
						            return c.substring(name.length, c.length);
						        }
						    }
						    return "";
						}


						$(document).ready(function(){
						 ResizeMenu();
						 if ($('#logo').width() + $('.informations-user').width() >= $(window).width())
							{
								$('#logo').css('display' , 'none');
								$('.informations-user').css('margin-left', '4px');
							}
						});

						$(window).resize(function(){

							if ($('#logo').width() + $('.informations-user').width() >= $(window).width())
							{
								$('#logo').css('display' , 'none');
								$('.informations-user').css('margin-left', '4px');
							}
							ResizeMenu();
						});

						function ResizeMenu() {
							currentHeight = $('#top-margin').innerHeight();
							footerHeight = $('#footer').innerHeight();
							currentHeight = currentHeight + 20;
							pageHeight = currentHeight + footerHeight;
							
							currentHeightMenu = $('#menu-container').outerHeight();
						}

						var lastId,
					    topMenu = $("#top-menu"),
					    topMenuHeight = topMenu.outerHeight()+15,
					    // All list items
					    menuItems = topMenu.find("a"),
					    // Anchors corresponding to menu items
					    scrollItems = menuItems.map(function(){
					      var item = $($(this).attr("href"));
					      if (item.length) { return item; }
					    });

					// Bind click handler to menu items
					// so we can get a fancy scroll animation
						menuItems.click(function(e){
						  var href = $(this).attr("href"),
						      offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
						  $('html, body').stop().animate({ 
						      scrollTop: offsetTop
						  }, 800);
						  e.preventDefault();
						});

						// Bind to scroll
						$(window).scroll(function(){
						   // Get container scroll position
						   var fromTop = $(this).scrollTop()+topMenuHeight;
						   
						   // Get id of current scroll item
						   var cur = scrollItems.map(function(){
						     if ($(this).offset().top < fromTop)
						       return this;
						   });
						   // Get the id of the current element
						   cur = cur[cur.length-1];
						   var id = cur && cur.length ? cur[0].id : "";
						   
						   if (lastId !== id) {
						       lastId = id;
						       // Set/remove active class
						       menuItems
						         .parent().removeClass("active")
						         .end().filter("[href='#"+id+"']").parent().addClass("active");
						   }                   
						});

						var $menu   = $("#cote-gauche"), 
				        $window    = $(window),
				        offset     = $menu.offset(),
				        topPadding = 0,
				        $sommaire = $(".sommaire"),
				        offsetSommaire = $sommaire.offset();



				        if ($(this).scrollTop()) {
						        $('.back-to-top').fadeIn();
						    } else {
						        $('.back-to-top').fadeOut();
						    }

				        $(".back-to-top").click(function() {
							$("html, body").animate({scrollTop: 0}, 800);
						});

				        if($('.back-to-top').offset().top + $('.back-to-top').height() >= $('#footer').offset().top - 10)
						        $('.back-to-top').css('position', 'absolute');
						    if($(document).scrollTop() + window.innerHeight < $('#footer').offset().top)
						        $('.back-to-top').css('position', 'fixed'); // restore when you scroll up
						    $('.back-to-top').text($(document).scrollTop() + window.innerHeight);



					    $window.scroll(function() {

					    	if ($(this).scrollTop()) {
						        $('.back-to-top').fadeIn();
						    } else {
						        $('.back-to-top').fadeOut();
						    }

					  		if($('.back-to-top').offset().top + $('.back-to-top').height() >= $('#footer').offset().top - 10)
						        $('.back-to-top').css('position', 'absolute');
						    if($(document).scrollTop() + window.innerHeight < $('#footer').offset().top)
						        $('.back-to-top').css('position', 'fixed'); // restore when you scroll up
						    $('.back-to-top').text($(document).scrollTop() + window.innerHeight);
						    	var offsetFixed = offset.top - $window.scrollTop();
						    	if ($window.scrollTop() > offset.top)
						    	{
						    		offsetFixed = 0;
						    	}
				        		//console.log(offsetFixed);
					        	if ($(window).width() > 1007)
					        	{
				        		 $('.cote-gauche-ouvert').css({
				        			'position' : 'relative',
				        			'top' : '0',
									'overflow-x': 'hidden'});
				        		
				        			$('.cote-gauche-ferme').css({
				        			'position' : 'relative',
				        			'top' : '0',
									'overflow-x': 'hidden'});
									
					        		if ($window.scrollTop() > offset.top) {
					        		$('.cote-gauche-ouvert').css('height', 'calc(100vh - ' + offsetFixed + 'px');
					        		$('.cote-gauche-ferme').css('height', 'calc(100vh - ' + offsetFixed + 'px');
					        		$('#menu-container').stop().animate({
					                marginTop: $window.scrollTop() - offset.top + topPadding
							            }, 100);

							    } 
								else {
									$('.cote-gauche-ouvert').css('height', 'calc(100vh - ' + offsetFixed + 'px');
									$('.cote-gauche-ferme').css('height', 'calc(100vh - ' + offsetFixed + 'px');
									
						            $('#menu-container').stop().animate({
						                marginTop: 0
						            }, 100);
							        }

							        
					        	}
					        	else
					        	{


					        		if ($window.scrollTop() > offset.top)

					        		{
					        			$('.cote-gauche-ouvert').css({
					        			'position' : 'fixed',
										'z-index': '3',
										'top' : '0',
										'margin-top' : '0',
										'left': '0',
										'height' : 'calc(100vh - ' + offsetFixed + 'px',
										'overflow-x': 'hidden'});
					        		
					        		$('.cote-gauche-ferme').css({
					        			'position' : 'fixed',
					        			'top' : '0',
					        			'margin-top' : '0',
										'z-index': '3',
										'left': '0',
										'height' : 'calc(100vh - ' + offsetFixed + 'px',
										'overflow-x': 'hidden'});
					        		}
					        		else
					        		{
					        			$('.cote-gauche-ouvert').css({
					        			'position' : 'fixed',
										'z-index': '3',
										'top' : offsetFixed,
										'margin-top' : '0',
										'left': '0',
										'height' : 'calc(100vh - ' + offsetFixed + 'px',
										'overflow-x': 'hidden'});
					        		
					        			$('.cote-gauche-ferme').css({
					        			'position' : 'fixed',
					        			'top' : offsetFixed,
										'overflox-y': 'scroll',
					        			'margin-top' : '0',
										'z-index': '3',
										'left': '0',
										'height' : 'calc(100vh - ' + offsetFixed + 'px',
										'overflow-x': 'hidden'});
					        		}
					        		
					        	}
					           
					        	var url = window.location.href;
								var NomDuFichier = url.substring(url.lastIndexOf( "/" )+1 );
								//console.log('$window.scrollTop()' + $window.scrollTop());
								//console.log('$offset.top' + offset.top);
								//console.log(NomDuFichier);
								if (NomDuFichier == 'conditions.php')
								{
									var width = $(window).width();
							   		if (width > 1690) {
							        if ($window.scrollTop() > offsetSommaire.top) {

							            $sommaire.stop().animate({
							                marginTop: $window.scrollTop() - offsetSommaire.top + topPadding
							            }, 'fast');
							        } else {
							            $sommaire.stop().animate({
							                marginTop: 0
							            }, 'fast');
							        }
							    }
									
					       			$(window).resize(function() {

							  		var width = $(window).width();
							    	if (width > 1690) {
							        if ($window.scrollTop() > offsetSommaire.top) {

							            $sommaire.stop().animate({
							                marginTop: $window.scrollTop() - offsetSommaire.top + topPadding
							            }, 'fast');
							        } else {
							            $sommaire.stop().animate({
							                marginTop: 0
							            }, 'fast');
							        }
							    }
								});
								}
					        	

					        
					    });
						});


						
						
						
