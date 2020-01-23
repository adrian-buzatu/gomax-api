import tableView from './lib/tableView.js';
import { baseUrl, isValidJSON, controller, controllerUrl } from './lib/master_helper.js';
export default class Banners {
	constructor() {
		this.id = $('.js-banner-id').val() || 0;
    $('.js-delete').on('click', this.deleteBannerImgHandler.bind(this));
    this.listAll();
    this.dzUpload();
	}

	listAll() {
		const tViewBanners = new tableView();
		tViewBanners.generate();
	}

	dzUpload() {
		$(".js-banner-file-upload-add").dropzone({
			autoDiscover: false,
			uploadMultiple: false,
			maxFiles: 1,
			paramName: 'files',
			init: function() {
				this.on('success', (file, responseText) => {
					let responseToParse = isValidJSON(responseText) ? responseText : responseText.replace(/^.+?(\{)/, '{');
					const xhrResponse = JSON.parse(responseText);
					const mediaIds = xhrResponse.media_ids;
					mediaIds.forEach(id => {
						$('<input>', {
							name: 'media_ids[]',
							type: 'hidden',
							value: id
						}).prependTo($('.js-banner-form'));
					});
				})
				this.on("maxfilesexceeded", function(file) {
					this.removeAllFiles();
					this.addFile(file);
				});
			},
			url: controllerUrl() + "/upload_media/"
		});
	}

	deleteBannerImgHandler(e) {
		const bannerId = this.id;
		const currentEl = $(e.target);
		const mediaId = currentEl.data('media-id');
		const parentEl = currentEl.closest('.media-item');
		$.ajax({
			url: controllerUrl() + '/delete_media/',
			method: 'GET',
			dataType: "json"
		}).then((response) => {
			if (!!response.success === true) {
				$(`.js_media_input_${bannerId}`).remove();
				parentEl.fadeOut('slow', event => $(event.target).remove());
			}
		});
	}
}
