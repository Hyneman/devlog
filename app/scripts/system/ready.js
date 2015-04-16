//
//// DEV/LOG: ISSUE TRACKING SYSTEM FOR DEVELOPERS.
//// COPYRIGHT (C) 2015 BY RICO BETI <rico.beti@silentbyte.com>.
////
//// THIS FILE IS PART OF DEV/LOG.
////
//// DEV/LOG IS FREE SOFTWARE: YOU CAN REDISTRIBUTE IT AND/OR MODIFY IT
//// UNDER THE TERMS OF THE GNU GENERAL PUBLIC LICENSE AS PUBLISHED BY
//// THE FREE SOFTWARE FOUNDATION, EITHER VERSION 3 OF THE LICENSE, OR
//// (AT YOUR OPTION) ANY LATER VERSION.
////
//// DEV/LOG IS DISTRIBUTED IN THE HOPE THAT IT WILL BE USEFUL,
//// BUT WITHOUT ANY WARRANTY; WITHOUT EVEN THE IMPLIED WARRANTY OF
//// MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE. SEE THE
//// GNU GENERAL PUBLIC LICENSE FOR MORE DETAILS.
////
//// YOU SHOULD HAVE RECEIVED A COPY OF THE GNU GENERAL PUBLIC LICENSE
//// ALONG WITH DEV/LOG. IF NOT, SEE <http://www.gnu.org/licenses/>.
//

// -----------------------------------------------------------------------------
// Style Updates.
// -----------------------------------------------------------------------------
$(function() {
	$(window).bind("load resize", function() {
		var width = (this.window.innerWidth > 0)
			? this.window.innerWidth : this.screen.width;

		if(width < 768) {
			$('div.navbar-collapse').addClass('collapse');

			$('#navigation').removeClass('navbar-fixed-top');
			$('#navigation').addClass('navbar-static-top');
		}
		else {
			$('div.navbar-collapse').removeClass('collapse');

			$('#navigation').removeClass('navbar-static-top');
			$('#navigation').addClass('navbar-fixed-top');
		}
	});
});


// -----------------------------------------------------------------------------
// Metis Menu.
// -----------------------------------------------------------------------------
$(function() {
	$('#sidebar').metisMenu();
});


// -----------------------------------------------------------------------------
// Perfect Scrollbar.
// -----------------------------------------------------------------------------
$(function() {
	$('#sidebar').perfectScrollbar({
		wheelSpeed:1,
		wheelPropagation: false,
		swipePropagation: true,
		useKeyboard: true
	});

	$(window).resize(function() {
		$('#sidebar').perfectScrollbar('update');
	});
});


// -----------------------------------------------------------------------------
// NProgress.
// -----------------------------------------------------------------------------
$(function() {
	NProgress.configure({
		showSpinner: false
	});
});