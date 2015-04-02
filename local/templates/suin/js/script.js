var submitForm = $.noop,
    openedSections = [true, false, false];

$.fn.sectioner = function(){
	this.each(function(){
		var $section = $(this),
		    $sections = $section.closest('.b-calculation').find('.b-calculation__form__section'),
			$sectionTop = $section.find('.b-calculation__form__section__top'),
			$sectionBottom = $section.find('.b-calculation__form__section__bottom'),
			$arrow = $section.find('.b-calculation__form__section__top__arrow'),
			$ico = $section.find('.b-calculation__form__section__top__ico'),
			$choice = $section.find('.b-calculation__form__section__top__choice'),
			$choiceOption = $choice.find('.b-calculation__form__section__top__choice__options').text(),
			$edit = $section.find('.b-calculation__form__section__top__edit'),
			index = $sections.index($section);
		// open
		var open = function(duration){
			openedSections[index] = true;
			$sectionTop.animate({backgroundColor: '#fff'}, duration*1.5);
			$edit.toggleClass('b-calculation__form__section__top__edit__active');
			$arrow.animate({'left': 0}, duration);
			$ico.css('position', 'absolute').animate({'left': 20}, duration);
			$choice.delay(duration).fadeIn(duration).addClass('b-calculation__form__section__top__choice_visible');
			$sectionBottom.slideDown(duration).data('opened', 1);
			$edit.text('свернуть');
		};
		// close
		var close = function(duration){
			openedSections[index] = false;
			$sectionTop.animate({backgroundColor: '#390255'}, duration*1.5);
			$edit.toggleClass('b-calculation__form__section__top__edit__active');
			$arrow.animate({'left': -266}, duration);
			$ico.animate({'left': 380}, duration);
			$choice.fadeOut(duration/2).removeClass('b-calculation__form__section__top__choice_visible');
			$sectionBottom.slideUp(duration).data('opened', 0);
			$edit.text('изменить');
		};
		// toggle
		var toggle = function(){
			if ($sectionBottom.data('opened')==1){
				close(400);
			} else {
				open(400);
			};
			return false;
		};
		// init
		if (openedSections[index]) {
			open(0);
		}  else {
			$sectionBottom.hide();
			$choice.hide();
		}
		// {event} click on header
		$sectionTop.click(toggle);
	});
};


var initModal = function(){
	$('.b-wrap_popup').suin();
	$('.b-more.b-more_first .b-more_first__left').height($('.b-more.b-more_first').outerHeight());
	$('.b-wrap_popup .f-button').each(function(){
		var $block = $(this);
		
		$block.append('<a class="f-button__hide" href="'+$block.find('a').attr('href')+'" />')
			.append('<div class="f-button__right" />')
			.append('<div class="f-button__bottom" />')
			.append('<div class="f-button__right-bottom" />')
			.append('<div class="f-button__right-top" />')
			.append('<div class="f-button__bottom-left" />')
			.append('<div class="f-button__shadow" />')
			.append('<div class="f-button__shadow_top-left" />')
			.append('<div class="f-button__shadow_bottom-right" />');
		
		if ($block.find('button.f-button__text').length || $block.find('input.f-button__text').length){
			var hideH = $('.f-button__text').outerHeight() - 40;
		} else {
			var hideH = $('.f-button__text').outerHeight();
		}
		
		$block.find('.f-button__hide').hover(function(){
			$block.toggleClass('f-button_hover');
		});
		$block.find('.f-button__hide').click(function(){
			$block.find('a,input,button').click();
		});
	});
	
	if($('.b-about').length){
		$('.b-about__col__slider').slides({
    		paginationClass: 'b-pagination',
    		effect: 'fade'
    	});
    	
    	$('.b-about__col__links').each(function(){
			var $window = $('.arcticmodal-container'),
			    isFixed = false;
			var checkPosition = function(){
				var min = $('.b-about__col__text-block').first().offset().top,
					position = 445+min*(-1),
					left = $('.b-about__left'),
				    rightH = $('.b-about__right').height(),
				    max = $('.b-about__col__links').height() + $('.b-about__col__percent').height() - min +80;
				
				if(left.height() != rightH){
					left.css('height', rightH);
				}  
				
				if (min < 0 && max < rightH) {
					$('.b-about__col__links').addClass('b-about__col__links_fix').css({'left':'50%', 'top': 20, 'position':'fixed'});
				} else if (max>rightH){
					$('.b-about__col__links').removeClass('b-about__col__links_fix').css({'top': rightH-$('.b-about__col__links').height()-20, 'left': 0, 'position': 'absolute'});
				} else if (min>=0){
					$('.b-about__col__links').removeClass('b-about__col__links_fix').css({'top': 445, 'left': 0, 'position': 'absolute'});
				}
			};
			$window.scroll(checkPosition);
			checkPosition();
		});
		
		$('.b-about__col__links__item').click(function(){
			var blockID = $(this).attr('href');
			$('.arcticmodal-container').scrollTo($(blockID), 350);
			return false;
		});
	}
 	
 	if($('.b-company').length){	
     	$('.b-company__slider').slides({
    		paginationClass: 'b-pagination',
    		effect: 'fade'
    	});
    	
    	var elem = 1;
    	$('.b-company__list__item').each(function(){
    		if(elem % 2 == 0){
    			$(this).addClass('b-company__list__item__inline-last');
    		}
    		elem++
    	});
    }
    
    if($('.b-contacts').length){
    	
    	$('.b-contacts').find('.js-map').each(function(index){
			var $map = $(this),
				mapID = 'js-map'+index,
			    x = parseFloat($map.attr('data-x')),
			    y = parseFloat($map.attr('data-y')),
			    zoom = parseFloat($map.attr('data-zoom')) || 14,
			    image = $map.attr('data-image');
			    
			$map.attr('id', mapID);
			
			ymaps.ready(function(){
				var map = new ymaps.Map(mapID, {
					center: [x, y],
					zoom: zoom
				});
				
				map.controls.add(
				   new ymaps.control.ZoomControl()
				);
				
				var placemark = new ymaps.Placemark([x, y], {}, {
					preset: 'twirl#blueDotIcon',
					cursor: 'move',
					iconImageHref: image,
		        	iconImageSize: [66, 81],
		        	iconImageOffset: [-33, -40]
				});
				
				map.geoObjects.add(placemark);
			});
		});

    	$('.b-contacts__sub__right__form').ketchup().ajaxForm({
    		beforeSubmit: function(){
    			$('.b-contacts__sub__right__form__ajax').html('<div class="b-notice b-notice_warning">Отправка...</div>')
    		},
    		success: function(data){
	    		var $data = $(data).find('.b-contacts__sub__right__form__ajax');
	    		$('.b-contacts__sub__right__form__ajax').replaceWith($data);
	    		if ($data.find('.b-notice_success').length) $('.b-contacts__sub__right__form').clearForm();
	    	}
    	});
   	}
   
   	if($('.b-business').length){
   		$('.b-business__top__slider').slides({
    		paginationClass: 'b-pagination_purple',
    		effect: 'slide',
    		play: 3000
    	});
   	}
	
	if($('.b-question').length){	

	 	$('.b-question__section__slider').slides({
    		paginationClass: 'b-pagination_center',
    		effect: 'fade'
    	});
    	
    	$('.b-question__section').first().show();
    	
    	$('.b-question__section__question__link').each(function(){
    		var $link = $(this),
    			$answer = $link.find('.b-question__section__question__link__answer');
    			
    		$link.click(function(){
    			$answer.slideToggle();
    			
    			return false;
    		})
    	});
    	
    	$('.b-question__sections__item').each(function(){
    		var $link = $(this),
    			section = $link.data('section'),
    			$sections = $('.b-question__section');
    			
    		$link.click(function(){
    			if(!($link.hasClass('b-question__sections__item_active'))){
    				$('.b-question__sections__item').removeClass('b-question__sections__item_active');
    				$sections.hide();
    				$sections.filter('[data-section="'+section+'"]').show();
    				$link.addClass('b-question__sections__item_active')
    			}
    			
    			return false;
    		})
    	});
   }
};


var modal = function(page, isEnd){
	$.arcticmodal({
		type: 'ajax',
		url: page,
		beforeClose: function(){
			if (isEnd) {
				document.location.href = '/';
				return false;
			};
		},
		afterLoadingOnShow: initModal
	});
};


$.fn.suin = function(){

	var $root = this,
	    $window = $(window);
	    
	$root.find('.b-calculation__steps__line').each(function(){
		var $line = $(this),
			$step = $line.find('.b-calculation__steps__line__step'),
			stepNow = $step.data('step');
			
		$step.text(stepNow);
	});
	
	$root.find('.b-calculation__form__section__bottom__option__marks').splitter({
		columns: 6,
		itemsSelector: '.f-radio',
		containerClass: 'b-calculation__form__section__bottom__option__mark-container',
		columnClass: 'b-calculation__form__section__bottom__option__mark-container__col'
	});
	
	$root.find('.f-radio__input').uniform();
	
	$root.find('.f-select').select2({
		minimumResultsForSearch: 10
	});
	
	$root.find('input, textarea').placeholder();
	
	$root.find('.b-calculation__form__section__bottom__option').each(function(){
		$(this).find('.f-radio').each(function(){
			var $block = $(this),
				$allBlock = $block.closest('.b-calculation__form__section__bottom__option').find('.f-radio'),
				$active = $block.find('span'),
				$radio = $block.find('input'),
				radioName = $radio.attr('name'),
				value = $block.find('label').text(),
				$section = $block.closest('.b-calculation__form__section'),
				$choice = $section.find('.b-calculation__form__section__top__choice'),
				$choiceOption = $choice.find('.b-calculation__form__section__top__choice__options'),
				$choiceOptionText = $choice.find('.b-calculation__form__section__top__choice__options').text();
			
			if ($active.hasClass('checked')){
				$block.addClass('f-radio__active');
			}
			
			if ($active.hasClass('checked')){
				$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+radioName+'">'+value+',</span>');
			}
			
			$radio.click(function(){
				var $choiceOptionItem = $choiceOption.find('.b-calculation__form__section__top__choice__options__option'),
					choiceOptionItemName = $choiceOptionItem.data('name');
				
				$allBlock.removeClass('f-radio__active');
				$radio.closest('.f-radio').addClass('f-radio__active');
				
				if ($choice.hasClass('b-calculation__form__section__top__choice_visible')){
					if(choiceOptionItemName == radioName || $choiceOptionItem.filter('[data-name="'+radioName+'"]').data('name') == radioName){
						$choiceOptionItem.filter('[data-name="'+radioName+'"]').text(value+',');
					} else {
						$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+radioName+'">'+value+',</span>');
					}
				} else {
					$choice.fadeIn().addClass('b-calculation__form__section__top__choice_visible');
					if(choiceOptionItemName == radioName || $choiceOptionItem.filter('[data-name="'+radioName+'"]').data('name') == radioName){
						$choiceOptionItem.filter('[data-name="'+radioName+'"]').text(value+',');
					} else {
						$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+radioName+'">'+value+',</span>');
					}
				}
			});
		});
		
		$(this).find('.f-input').each(function(){
			var $input = $(this),
				inputName = $input.attr('name'),
				$section = $input.closest('.b-calculation__form__section'),
				$choice = $section.find('.b-calculation__form__section__top__choice'),
				$choiceOption = $choice.find('.b-calculation__form__section__top__choice__options'),
				$choiceOptionText = $choice.find('.b-calculation__form__section__top__choice__options').text();
				
			if ($input.val().length > 0){
				$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+inputName+'">'+$input.val()+',</span>');
			}
				
			$input.bind('textchange', function(){
				var value = $input.val(),
					$choiceOptionItem = $choiceOption.find('.b-calculation__form__section__top__choice__options__option'),
					choiceOptionItemName = $choiceOptionItem.data('name');
	
				if ($choice.hasClass('b-calculation__form__section__top__choice_visible')){
					if(choiceOptionItemName == inputName || $choiceOptionItem.filter('[data-name="'+inputName+'"]').data('name') == inputName){
						$choiceOptionItem.filter('[data-name="'+inputName+'"]').text(value+',');
					} else {
						$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+inputName+'">'+value+',</span>');
					}
				} else {
					$choice.fadeIn().addClass('b-calculation__form__section__top__choice_visible');
					if(choiceOptionItemName == inputName || $choiceOptionItem.filter('[data-name="'+inputName+'"]').data('name') == inputName){
						$choiceOptionItem.filter('[data-name="'+inputName+'"]').text(value+',');
					} else {
						$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+inputName+'">'+value+',</span>');
					}
				}
				
			});
		});
		
		$(this).find('select.f-select').each(function(){
			var $container = $(this).parent(),
				selectName = $container.find('select').attr('name'),
				text = $container.find('select option:selected').text(),
				value = $container.find('select option:selected').attr('value'),
				$select = $(this),
				$section = $container.closest('.b-calculation__form__section'),
				$choice = $section.find('.b-calculation__form__section__top__choice'),
				$choiceOption = $choice.find('.b-calculation__form__section__top__choice__options'),
				$choiceOptionText = $choice.find('.b-calculation__form__section__top__choice__options').text();
			if (text.length > 0){
				$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+selectName+'">'+text+',</span>');
			}	
			
			$select.change(function(){
				
				var selected = $(this).find('option:selected').text(),
					value = $container.find('select option:selected').attr('value'),
					$choiceOptionItem = $choiceOption.find('.b-calculation__form__section__top__choice__options__option'),
					choiceOptionItemName = $choiceOptionItem.data('name');
					
				if(value != 0){
					if ($choice.hasClass('b-calculation__form__section__top__choice_visible')){
						if(choiceOptionItemName == selectName || $choiceOptionItem.filter('[data-name="'+selectName+'"]').data('name') == selectName){
							$choiceOptionItem.filter('[data-name="'+selectName+'"]').text(selected+',');
						} else {
							$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+selectName+'">'+selected+',</span>');
						}
					} else {
						$choice.fadeIn().addClass('b-calculation__form__section__top__choice_visible');
						if(choiceOptionItemName == selectName || $choiceOptionItem.filter('[data-name="'+selectName+'"]').data('name') == selectName){
							$choiceOptionItem.filter('[data-name="'+selectName+'"]').text(selected+',');
						} else {
							$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+selectName+'">'+selected+',</span>');
						}
					}
				} else {
					$choiceOptionItem.filter('[data-name="'+selectName+'"]').remove();
				}

			});
		});
		
		$(this).find('.b-calculation__form__section__bottom__option__date').each(function(){
			$(this).datepicker({
				maxDate: 0,
				minDate: '-8y',
				changeMonth: true,
				changeYear: true,
				onSelect: function(selectedDate) {
					var $container = $(this),
						dateName = $container.attr('name'),
						value = selectedDate,
						$section = $container.closest('.b-calculation__form__section'),
						$choice = $section.find('.b-calculation__form__section__top__choice'),
						$choiceOption = $choice.find('.b-calculation__form__section__top__choice__options'),
						$choiceOptionText = $choice.find('.b-calculation__form__section__top__choice__options').text(),
						$choiceOptionItem = $choiceOption.find('.b-calculation__form__section__top__choice__options__option'),
						choiceOptionItemName = $choiceOptionItem.data('name');		
			
					if ($choice.hasClass('b-calculation__form__section__top__choice_visible')){
						if(choiceOptionItemName == dateName || $choiceOptionItem.filter('[data-name="'+dateName+'"]').data('name') == dateName){
							$choiceOptionItem.filter('[data-name="'+dateName+'"]').text(value+',');
						} else {
							$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+dateName+'">'+value+',</span>');
						}
					} else {
						$choice.fadeIn().addClass('b-calculation__form__section__top__choice_visible');
						if(choiceOptionItemName == dateName || $choiceOptionItem.filter('[data-name="'+dateName+'"]').data('name') == dateName){
							$choiceOptionItem.filter('[data-name="'+dateName+'"]').text(value+',');
						} else {
							$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+dateName+'">'+value+',</span>');
						}
					}
				}
			});
		});
	});
	
	$root.find('.b-calculation').each(function(){
		var $result = $root.find('.b-result'),
		    $errors = $root.find('.b-calculation__form__section__bottom__option_error'),
		    $visibleErrors = $errors.filter(':visible'),
		    $scroller = $([]);
		if ($visibleErrors.length) {
			$scroller = $visibleErrors.first();
		} else if ($errors.length) {
			$scroller = $root.find('.b-calculation');
		} else {
			$scroller = $result;
		}
		if ($scroller.length) $('html').scrollTo($scroller, 350);
		if ($result.length || $errors.length){
			openedSections = [true, true, true];
		};
	});
	
	$root.find('.b-calculation__form__section').each(function(){
		$(this).sectioner();
		$(this).find('.f-button:last').prev('.b-calculation__form__section__bottom__option').addClass('b-calculation__form__section__bottom__option_no-bottom')
	});
	
	$root.find('.b-footer__slider').slides({
		generatePagination: false
	});


	/* ---- Agent form ---- */
	var active_form = false;
	$root.find('.js-agent-form').each(function(i){
		var $frm = $(this),
			$allfrm = $root.find('.js-agent-form'),
		    $btn = $frm.find('.f-button__text'),
		    $mdl = $frm.find('.b-modal'),
		    page = $mdl.attr('data-page');
		$frm.ketchup();
 		$frm.click(function(){
			if(active_form !== i)
			{
				active_form = i;
				$allfrm.each(function(a){
					if(i!=a){
						$(this).find('.ketchup-error').hide();
						$(this).find('.b-ketchup').addClass('b-ketchup_hide');
					}else{
						$(this).find('.ketchup-error').show();
						$(this).find('.b-ketchup.b-ketchup_hide').removeClass('b-ketchup_hide');
					}
				});			
			}
		}) 		
		$frm.submit(function(){
			return false;
		}).bind('formIsValid', function(event,form) {
			modal(page + (page.indexOf('?') >= 0 ? '&' : '?') + $frm.serialize(), true);
		});
	});
	/* ---- Button ---- */
	$root.find('.f-button').each(function(){
		var $block = $(this);
		
		$block.append('<div class="f-button__hide" />')
			.append('<div class="f-button__right" />')
			.append('<div class="f-button__bottom" />')
			.append('<div class="f-button__right-bottom" />')
			.append('<div class="f-button__right-top" />')
			.append('<div class="f-button__bottom-left" />')
			.append('<div class="f-button__shadow" />')
			.append('<div class="f-button__shadow_top-left" />')
			.append('<div class="f-button__shadow_bottom-right" />');
		
		
		if ($block.find('button.f-button__text').length || $block.find('input.f-button__text').length){
			var hideH = $('.f-button__text').outerHeight() - 40;
		} else {
			var hideH = $('.f-button__text').outerHeight();
		}
		$block.find('.f-button__hide').click(function(){
			$block.find('a,input,button').click();
		});

		
		if(!($block.parent().hasClass('b-result__list__col__item__block'))){
			$block.find('.f-button__hide').hover(function(){
				$block.toggleClass('f-button_hover');
			});
		}
	});
	
	$root.find('.b-calculation__step-3__col__form__cell').each(function(){
		var $cell = $(this),
			$add = $cell.find('.b-calculation__step-3__col__form__cell__add'),
			$driverFirst = $('.b-calculation__step-3__col__form__cell').filter('[data-cell="driver"]').first(),
			$clone = $driverFirst.clone();
			
		$add.click(function(){
			var $cloneMore = $clone.clone();
			
			$cloneMore.appendTo('.b-calculation__step-3__col__form__cell__more-driver');
			
			var $driver = $('.b-calculation__step-3__col__form__cell').filter('[data-cell="driver"]'),
				driverValue = $driver.length;
			
			$driver.last().find('.b-calculation__step-3__col__form__cell__title__driver-num').text(driverValue);
			return false;
		});
	});
	
	$root.find('.b-modal').click(function(){
		var page = $(this).data('page'),
		    isEnd = $(this).hasClass('b-modal_end');
		modal(page, isEnd);
	    return false;
	});
	
	$root.find('.b-result__list__col').each(function(){
		var button = $(this).find('.b-result__list__col__item__button'),
			realButton = $(this).find('.f-button');
			
		realButton.hide();
		
		button.hover(function(){
			button.hide()
			realButton.show();
		},function(){});
		
		realButton.hover(function(){},function(){
			button.show()
			realButton.hide();
		});
	});
	
	$.datepicker.setDefaults($.extend(
		$.datepicker.regional["ru"])
	);
	
	$root.find('.b-calculation__form__section__bottom__option__date').datepicker({
		onSelect: function(selectedDate) {
			var $container = $(this),
				dateName = $container.attr('name'),
				value = selectedDate,
				$section = $container.closest('.b-calculation__form__section'),
				$choice = $section.find('.b-calculation__form__section__top__choice'),
				$choiceOption = $choice.find('.b-calculation__form__section__top__choice__options'),
				$choiceOptionText = $choice.find('.b-calculation__form__section__top__choice__options').text(),
				$choiceOptionItem = $choiceOption.find('.b-calculation__form__section__top__choice__options__option'),
				choiceOptionItemName = $choiceOptionItem.data('name');
				
			if (value.length > 0){
				$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+dateName+'">'+value+',</span>');
			}	

	
			if ($choice.hasClass('b-calculation__form__section__top__choice_visible')){
				if(choiceOptionItemName == dateName || $choiceOptionItem.filter('[data-name="'+dateName+'"]').data('name') == dateName){
					$choiceOptionItem.filter('[data-name="'+dateName+'"]').text(value+',');
				} else {
					$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+dateName+'">'+value+',</span>');
				}
			} else {
				$choice.fadeIn().addClass('b-calculation__form__section__top__choice_visible');
				if(choiceOptionItemName == dateName || $choiceOptionItem.filter('[data-name="'+dateName+'"]').data('name') == dateName){
					$choiceOptionItem.filter('[data-name="'+dateName+'"]').text(value+',');
				} else {
					$choiceOption.append('<span class="b-calculation__form__section__top__choice__options__option" data-name="'+dateName+'">'+value+',</span>');
				}
			}
		}
	});
	
	$root.find('.b-header__promo').each(function(){
		var $block = $(this),
		    $arrow = $block.find('.b-header__promo__arrow'),
			image = $block.find('.b-header__promo__image__img'),
			slide = $block.find('.b-header__promo__navigation__item'),
			activeSlide = slide.filter('[class="b-header__promo__navigation__item b-header__promo__navigation__item_active"]'),
			curSlide = slide.filter('[class="b-header__promo__navigation__item b-header__promo__navigation__item_active"]').data('slide'),
			toCalc = $('.b-header__promo-text .f-button__hide'),
			arrowHidden = false,
			$window = $(window),
			carecar = $block.find('.b-header__promo-decor11');
			
		carecar.each(function(){
			var car = $(this),
				cadrLenght = 14,
				step = car.width();
				var i = 0,
					j = cadrLenght;
			setInterval(function(){
				if(i<cadrLenght){
					car.css({'background-position': '-'+step*i+'px 0'})
					i++
				}else{
					if(j>0){
						car.css({'background-position': '-'+step*j+'px 0'})
						j--
					}else{
						i = 0;
						j = cadrLenght;
					}
				}
			},25);		
		})
		carecar.delay(1000).animate({'top':'470px','left':'1730px'},5000);
		
		$block.attr('data-slide', curSlide);

		$arrow.each(function(){
			var $icon = $arrow.find('.b-header__promo__arrow__icon'),
			    duration = 350,
			    href = $arrow.attr('href');
			var up = function(){
				if (!arrowHidden) $icon.animate({ marginBottom:15 }, duration, down);
			}
			var down = function(){
				if (!arrowHidden) $icon.animate({ marginBottom:0 }, duration, up);
			}
			up();
			var check = function(){
				if (!arrowHidden && $window.scrollTop()>100) {
					arrowHidden = true;
					$arrow.fadeOut(350);
				}
			}
			$arrow.click(function(){
				$('html').scrollTo($(href), 350);
				return false;
			});
			$window.scroll(check);
			check();
		});
		$('.b-header__promo-text').fadeOut()
		$('.b-header__promo-text').eq(0).fadeIn()
		var moveTo = function(index){
			var clickSlide = index+1;
			
			$block.attr('data-slide', clickSlide);
			slide.removeClass('b-header__promo__navigation__item_active');
			slide.filter('[data-slide="'+clickSlide+'"]').addClass('b-header__promo__navigation__item_active');
	
			
			if (clickSlide == 1){
				carecar.stop().css({'top':'470px','left':'1000px'})
				image.animate({'left': -430, 'right': -430, 'top': 0},650,function(){
					carecar.animate({'top':'470px','left':'1730px'},5000)
				});
				$('.b-header__promo-text').fadeOut()
				$('.b-header__promo-text').eq(0).fadeIn()
			}
			
			if (clickSlide == 2){
				image.animate({'left': -1630, 'right': -1630, 'top': 0},650,function(){
					carecar.stop().css({'top':'470px','left':'1730px'})
					carecar.animate({'top':'470px','left':'2300px'},5000)
				});
				$('.b-header__promo-text').fadeOut()
				$('.b-header__promo-text').eq(1).fadeIn()
			}
			
			if (clickSlide == 3){
				image.animate({'left': -1625, 'right': -1625, 'top': -670},650,function(){
					carecar.stop().css({'top':'1194px','left':'2000px'})
					carecar.animate({'top':'1194px','left':'2800px'},5000)
				});
				$('.b-header__promo-text').fadeOut()
				$('.b-header__promo-text').eq(2).fadeIn()
			}
			
			if ($(window).width() >= 1600){
				if (clickSlide == 4){
					image.animate({'left': -2515, 'right': -2515, 'top': -675},650,function(){
						carecar.stop().css({'top':'1194px','left':'2800px'})
						carecar.animate({'top':'1194px','left':'3600px'},5000)
					});
					$('.b-header__promo-text').fadeOut()
					$('.b-header__promo-text').eq(3).fadeIn()
				}
			} else if ($(window).width() < 1600){
				if (clickSlide == 4){
					image.animate({'left': -2815, 'right': -2815, 'top': -675},650,function(){
						carecar.stop().css({'top':'1194px','left':'2800px'})
						carecar.animate({'top':'1194px','left':'3600px'},5000)	
					});
					$('.b-header__promo-text').fadeOut()
					$('.b-header__promo-text').eq(3).fadeIn()	
				}
 			} 
		}
		
		slide.click(function(){
			var clickSlide = $(this).data('slide') -1;
			moveTo(clickSlide);
			clearInterval(slider);
			return false;
		});
		
		toCalc.click(function(){
			$('html').scrollTo($('.b-calculation'), 350);
			
			return false;
		});
		
		var slider = setInterval(function(){
			var activeSlide = slide.filter('[class="b-header__promo__navigation__item b-header__promo__navigation__item_active"]'),
				next = activeSlide.data('slide');
			
			if(activeSlide.next().length){
				moveTo(next);
			} else {
				moveTo(0);
			}

		}, 8000);
		
	});

	$root.filter('.b-calculation__form').add($root.find('.b-calculation__form')).each(function(){
		var $form = $(this),
		    $parent = $form.closest('.b-calculation'),
		    $loading = $parent.find('.b-calculation__loading'),
		    $overlay = $parent.find('.b-calculation__overlay'),
		    $ajaxInputs = $form.find('.js-ajax, .js-ajax input, .js-ajax select'),
		    timer,
		    request;
		// loading
		var showLoading = function(){
			$loading.add($overlay).show();
		};
		var hideLoading = function(){
			$loading.add($overlay).hide();
		};
		// ajax submit form
		submitForm = function(){
			clearTimeout(timer);
			timer = setTimeout(function(){
				if (request!=undefined) request.abort();
				$form.ajaxSubmit({ 
					data: { 'is_ajax': 'Y' },
					success: function(data) {
						$form.html(data).suin();
						hideLoading();
						return false;
					}, beforeSend: function(xhr,settings){
						showLoading();
						request = xhr;
					}, error: function(data){
						hideLoading();
						return false;
					}
				});
			}, 500);
			return true;
		};
		$ajaxInputs.change(submitForm);
		$form.submit(function(){
			showLoading();
		});
	});


	/* ---- Number input ---- */
	$root.find('.js-number-input').each(function(){
		var $input = $(this),
		    min = parseFloat($input.attr('data-min')) || 0,
		    max = parseFloat($input.attr('data-max')) || 100,
		    noRange = $input.attr('data-no-range') || false;
		$input.keypress(function(e){
			if (e.which!=8 && e.which!=0 && e.which!=46 && e.which!=44 && (e.which<48 || e.which>57)) return false;
		});
		$input.bind('change', function(){
			var value = $input.val().replace(',','.');
			if (noRange!='true'){
				value = Math.max(value, min);
				value = Math.min(value, max);
			};
			$input.val(value);
			$input.trigger('textchange');
		});
	});


	/* ---- Number input ---- */
	$root.find('.b-calculation__form__section_second').each(function(){
		var $section = $(this),
		    $age = $section.find('.js-driver-age'),
		    $exp = $section.find('.js-driver-exp'),
		    $note = $section.find('.js-driver-note');
			var check = function(){
				var error = false;
				$age.each(function(){
					var value = $(this).val();
					if (!error && !isNaN(parseInt(value)) && parseInt(value,10)<21) error = true;
				});
				$exp.each(function(){
					var value = $(this).val();
					if (!error && !isNaN(parseInt(value)) && parseInt(value,10)<2) error = true;
				});
				if (error) $note.show(); else $note.hide();
			};
			check();
			$age.add($exp).change(check);
	});

}


$(function() {
	
	$('.b-page:first').suin();
	
});