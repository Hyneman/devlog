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

devlog.ApplicationController = Ember.ObjectController.extend({
	name: 'devlog',
	version: '0.1',
	copyright: 'Copyright © 2015 by Silent Byte',

	_loading: 0,
	disabled : true,

	startLoading: function() {
		var counter = this.get('_loading');
		if(counter === 0)
			NProgress.start();

		this.set('_loading', counter + 1);
	},

	stopLoading: function() {
		var counter = Math.max(0, this.get('_loading'));
		if(counter === 1)
			NProgress.done();

		this.set('_loading', counter - 1);
	}

});
