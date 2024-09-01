(function ($) {
	"use strict";

	function setStructure() {
        // mark parent-categories
        $('.widget_custom_product_categories ul.children').closest('li').addClass('cat-parent');
        
        // append a plus icon to each parent category
        $(".widget_custom_product_categories ul > li.cat-parent").append('<span class="subDropdown plus"></span>');
        
        // mark the current categories based on checked input
        $('.widget_custom_product_categories input[checked]').closest('li').addClass('current-cat');
	}

	
	function toggleHandler(event) { // applies only for parent-category items
		// this function will be triggered when clicking on the icon or anywhere else on the div (except the label)

		event.stopPropagation(); // prevent the event from bubbling up to child elements

		var icon = $(this).find('> .subDropdown');
		var isCollapsing = icon.hasClass('minus');
		var children = $(this).find(isCollapsing?
			'ul' : // select all nested lists (collapse them as well)
			'> ul'); // use '>' to select only immediate children

		// change the icon (+ / -) for the chosen element
		icon.toggleClass('plus minus');

		// change the icon (+ / -) for nested children that are being collapsed as well
		children.find('.subDropdown.minus').toggleClass('plus minus');

		// toggle children display
		children.css('display', isCollapsing ? 'none' : 'block');		
	}

	function showChosenCategories() { // expand items that contains chosen categories
		var root = $('.widget_custom_product_categories .site-checkbox-lists .site-scroll > ul');
		recursiveShowChosenCategories(root);

		function recursiveShowChosenCategories(list) {
			// look for parent categories
			list.find('li.cat-parent').each(function() {
				// for each parent, look for a nested chosen-category
				if($(this).find('.current-cat').length > 0) { // found
					// reveal children
					$(this).children('.children').css('display', 'block');
					// NOTE: do not use "toogleClass" to avoid conflicts with "expandLoneParent()"
					$(this).children('.subDropdown').removeClass('plus');
					$(this).children('.subDropdown').addClass('minus');
					
					// go deeper
					recursiveShowChosenCategories($(this).children('.children'))
				}	
			});	
		}
	}

	function expandLoneParent() { // if there is only one parent, it should be expanded by default
		var root = $('.widget_custom_product_categories .site-checkbox-lists .site-scroll > ul');
		var directChildren = root.children('li.cat-parent');
		if(directChildren.length === 1) {
			directChildren.children('.children').css('display', 'block');
			// NOTE: do not use "toogleClass" to avoid conflicts with "showChosenCategories()"
			directChildren.children('.subDropdown').removeClass('plus');
			directChildren.children('.subDropdown').addClass('minus');

		}
	}

	function minimizeLinkArea(item) { // applies only for parent-category items
		// this function will be triggered when clicking on the parent of "item" (and will run only once for each item)

		if(item.find('> a').css('flex-grow') !== '1') // different then the default value (already set before)
			return; // avoid unnecessary work

		var link = item.find('> a');
		var label = item.find('> a > label');

		label.css('display', 'inline'); // remove any margin and stretch label's text in order to get the exact width

		// calculate the percentage of label relative to the link-element
		// NOTE: the method .css('width') can make some inaccuracies (sometimes it returns the value as an integer instead of a float)  
		var percentage = parseFloat(label[0].getBoundingClientRect().width) / parseFloat(link[0].getBoundingClientRect().width);
		
		label.css('display', 'flex'); // revert attribute
		
		// minimize the link area to the size of the label (text and checkbox)
		link.css('flex-grow', percentage);
	}


    $(document).ready(function() {

		// initialize categories tree
		setStructure();

		// expand items that contains chosen categories
		showChosenCategories();

		// if there is only one parent, it should be expanded by default
		expandLoneParent();

		// for each parent-category item:
		$('.widget_custom_product_categories li.cat-parent').on('click', function(event) {

			if(!$(event.target).is('label')) { // the click is not on the label itself
				// define onClick listener to handle expanding / collapsing of lists
				toggleHandler.call(this, event); // use call to maintain the context of 'this'
			}

			// for each direct child that is also a parent-category item - redefine clickable area			
			$(this).each(function() {
				var subParentCategories = $(this).find('.children li.cat-parent');
				subParentCategories.each(function() {
					// NOTE: this function most run when the menu is visible, otherwise the width of links and labels will be 0
					minimizeLinkArea($(this));
				});
			});	
		});

		// define onClick listener // applies for both "categories button" and "burger button" 
		$('.filter-toggle').on('click', function() {
			// for each parent-category (that is a direct child of root)- redefine clickable area
			$('.widget_custom_product_categories li.cat-parent').each(function() {
				// NOTE: this function most run when the menu is visible, otherwise the width of links and labels will be 0
				minimizeLinkArea($(this));
			});
		})
    });

})(jQuery);