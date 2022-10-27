/**!
 * Google Drive File Picker Example
 * By Daniel Lo Nigro (http://dan.cx/)
 */
(function() {
	/**
	 * Initialise a Google Driver file picker
	 */
	var GoogleDrivePicker = window.GoogleDrivePicker = function(options) {
		// Config
		this.apiKey = options.apiKey;
		this.clientId = options.clientId;
		
		// Elements
		this.buttonEl = options.buttonEl;
		
		// Events
		this.onSelect = options.onSelect;
		this.buttonEl.addEventListener('click', this.open.bind(this));		
	
		// Disable the button until the API loads, as it won't work properly until then.
		this.buttonEl.disabled = true;

		// Load the drive API
		gapi.client.setApiKey(this.apiKey);
		gapi.client.load('drive', 'v3', this._driveApiLoaded.bind(this));
		google.load('picker', '1', { callback: this._pickerApiLoaded.bind(this) });
	}

	GoogleDrivePicker.prototype = {
		/**
		 * Open the file picker.
		 */
		open: function() {		
			// Check if the user has already authenticated
			var token = gapi.auth.getToken();
			if (token) {
				this._showPicker();
			} else {
				// The user has not yet authenticated with Google
				// We need to do the authentication before displaying the Drive picker.
				this._doAuth(false, function() { this._showPicker(); }.bind(this));
			}
		},
		
		/**
		 * Show the file picker once authentication has been done.
		 * @private
		 */
		_showPicker: function() {
			var accessToken = gapi.auth.getToken().access_token;
			var view = new google.picker.View(google.picker.ViewId.DOCS_IMAGES_AND_VIDEOS);
		        view.setMimeTypes("image/png,image/jpeg,image/jpg,video/mp4");

			this.picker = new google.picker.PickerBuilder().
				hideTitleBar().
				addView(view).
				setAppId(this.clientId).
				setOAuthToken(accessToken).
				setCallback(this._pickerCallback.bind(this)).
				build().
				setVisible(true);
		},
		
		/**
		 * Called when a file has been selected in the Google Drive file picker.
		 * @private
		 */
		_pickerCallback: function(data) {
			if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
				var file = data[google.picker.Response.DOCUMENTS][0],
					id = file[google.picker.Document.ID],
					request = gapi.client.drive.files.get({
						fileId: id,
						fields: "id,mimeType,size,fileExtension"
					});
					
				request.execute(this._fileGetCallback.bind(this));
			}
		},
		/**
		 * Called when file details have been retrieved from Google Drive.
		 * @private
		 */
		_fileGetCallback: function(file) {
			if (this.onSelect) {
				this.onSelect(file);
			}
		},
		
		/**
		 * Called when the Google Drive file picker API has finished loading.
		 * @private
		 */
		_pickerApiLoaded: function() {
			this.buttonEl.disabled = false;
		},
		
		/**
		 * Called when the Google Drive API has finished loading.
		 * @private
		 */
		_driveApiLoaded: function() {
			this._doAuth(true);
		},
		
		/**
		 * Authenticate with Google Drive via the Google JavaScript API.
		 * @private
		 */
		_doAuth: function(immediate, callback) {	
			gapi.auth.authorize({
				client_id: this.clientId + '.apps.googleusercontent.com',
				scope: 'https://www.googleapis.com/auth/drive',
				immediate: immediate
			}, callback);
		}
	};
}());