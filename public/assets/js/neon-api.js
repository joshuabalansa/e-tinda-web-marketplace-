/*
	Resuable Functions with Neon Theme

	------

	Theme by: Laborator - www.laborator.co

	Developed by: Arlind Nushi
	Designed by: Art Ramadani
*/

var public_vars = public_vars || {};


// ! Sidebar Menu Options
jQuery.extend(public_vars, {
	sidebarCollapseClass: 'sidebar-collapsed',
	sidebarOnTransitionClass: 'sidebar-is-busy',
	sidebarOnHideTransitionClass: 'sidebar-is-collapsing',
	sidebarOnShowTransitionClass: 'sidebar-is-showing',
	sidebarTransitionTime: 700, // ms
	isRightSidebar: false
});

function show_sidebar_menu(with_animation)
{
	if(isxs())
		return;

	// Always use non-animated version
	public_vars.$pageContainer.removeClass(public_vars.sidebarCollapseClass);
	public_vars.$mainMenu.find('.has-sub > ul').attr('style', '');
}

function hide_sidebar_menu(with_animation)
{
	if(isxs())
		return;

	// Always use non-animated version
	public_vars.$pageContainer.addClass(public_vars.sidebarCollapseClass);
	public_vars.$mainMenu.find('.has-sub > ul').attr('style', '');
}

function toggle_sidebar_menu(with_animation)
{
	var open = public_vars.$pageContainer.hasClass(public_vars.sidebarCollapseClass);

	if(open)
	{
		show_sidebar_menu(with_animation);
		ps_init();
	}
	else
	{
		hide_sidebar_menu(with_animation);
		ps_destroy();
	}
}


// Added on v1.5
function rtl() // checks whether the content is in RTL mode
{
	if(typeof window.isRTL == 'boolean')
		return window.isRTL;

	window.isRTL = jQuery("html").get(0).dir == 'rtl' ? true : false;

	return window.isRTL;
}

// Right to left Coeficient
function rtlc()
{
	return rtl() ? -1 : 1;
}


// Perfect scroll bar functions by Arlind Nushi
function ps_update(destroy_init)
{
	if(isxs())
		return;

	if(jQuery.isFunction(jQuery.fn.perfectScrollbar))
	{
		if(public_vars.$sidebarMenu.hasClass('collapsed'))
		{
			return;
		}

		public_vars.$sidebarMenu.find('.sidebar-menu-inner').perfectScrollbar('update');

		if(destroy_init)
		{
			ps_destroy();
			ps_init();
		}
	}
}


function ps_init()
{
	if(isxs())
		return;

	if(jQuery.isFunction(jQuery.fn.perfectScrollbar))
	{
		if(public_vars.$pageContainer.hasClass(public_vars.sidebarCollapseClass) || ! public_vars.$sidebarMenu.hasClass('fixed'))
		{
			return;
		}

		public_vars.$sidebarMenu.find('.sidebar-menu-inner').perfectScrollbar({
			wheelSpeed: 1,
			wheelPropagation: public_vars.wheelPropagation
		});
	}
}

function ps_destroy()
{
	if(jQuery.isFunction(jQuery.fn.perfectScrollbar))
	{
		public_vars.$sidebarMenu.find('.sidebar-menu-inner').perfectScrollbar('destroy');
	}
}