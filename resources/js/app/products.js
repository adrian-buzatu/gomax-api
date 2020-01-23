import tableView from './lib/tableView.js';
import { baseUrl, isValidJSON, controller, controllerUrl } from './lib/master_helper.js';
let prodMediaIdsUploaded = [];
export default class Products {
	constructor() {

		this.id = $('.js-product-id').val() || 0;
		this.tableColumns = [
			{ data: "dt_id" },
      { data: "name" },
      { data: "code" },
      { data: "price" },
    ];
    $('.js-delete').on('click', this.deleteProdImgHandler.bind(this));
    this.listAll();
    this.dzUpload();
	}

	listAll() {
		const tViewProducts = new tableView({
			columns: this.tableColumns
		});
		tViewProducts.generate();
	}

	dzUpload() {
		$(".js-product-file-upload-add").dropzone({
			autoDiscover: false,
			uploadMultiple: true,
			paramName: 'files',
			init: function() {
				this.on('success', (file, responseText) => {
					let responseToParse = isValidJSON(responseText) ? responseText : responseText.replace(/^.+?(\{)/, '{');
					const xhrResponse = JSON.parse(responseToParse);
					const mediaIds = xhrResponse.media_ids;
					mediaIds.forEach(id => {
						if (prodMediaIdsUploaded.indexOf(id) === -1) {
							prodMediaIdsUploaded.push(id);
							$('<input>', {
								name: 'media_ids[]',
								type: 'hidden',
								value: id
							}).prependTo($('.js-product-form'));
						}
					});
				})
			},
			url: controllerUrl() + "/upload_media/"
		});
	}

	deleteProdImgHandler(e) {
		const productId = this.id;
		const currentEl = $(e.target);
		const mediaId = currentEl.data('media-id');
		const parentEl = currentEl.closest('.media-item');
		$.ajax({
			url: controllerUrl() + '/delete_media/',
			method: 'GET',
			dataType: "json"
		}).then((response) => {
			if (!!response.success === true) {
				$(`.js_media_input_${productId}`).remove();
				parentEl.fadeOut('slow', event => $(event.target).remove());
			}
		});
	}
}
