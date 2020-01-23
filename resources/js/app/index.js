import { controller } from './lib/master_helper.js';
import Products  from './products.js';
import Pages from './pages.js';
import Messages from './messages.js';
import Categories from './categories.js';
import Banners from './banners.js';

const controllerMap = {
	'categories': 'Categories',
	'pages': 'Pages',
	'products': 'Products',
	'messages': 'Messages',
	'banners': 'Banners'
}

$(document).ready(function() {
	switch (controller()) {
		case 'products': {
			new Products(); break;
		}
		case 'pages': {
			new Pages(); break;
		}
		case 'messages': {
			new Messages(); break;
		}
		case 'categories': {
			new Categories(); break;
		}
		case 'banners': {
			new Banners(); break;
		}
	}
});