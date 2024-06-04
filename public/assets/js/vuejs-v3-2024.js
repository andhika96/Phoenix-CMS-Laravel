const { createApp, ref, reactive, defineModel } = Vue

createApp(
	{
		setup() {
			const message = ref('Hello vue!')

			return {
				message
			}
		}
	}).mount('#app');

const AuthVue3 = createApp(
	{
		data() {
			// const Data = reactive(
			// {
			// 	reponseData: '',
			// 	responseMessage: '',
			// 	responseStatus: ''
			// });

			return {
				reponseData: '',
				responseMessage: '',
				responseMessageAfterSubmit: '',
				responseStatus: '',
				isArrayMessageAfterSubmit: 0
			}
		},
		methods:
		{
			login: function (event) {
				event.preventDefault();

				// Get id form submit
				let getIdFormSubmit = document.getElementById("ph-form-login-submit");

				// Get value of attribute in HTML.
				let formActionURL = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("action");
				let formMethod = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("method");

				// FormData objects are used to capture HTML form and submit it using fetch or another network method.
				let formData = new FormData(this.$refs.formHTML);

				// Disabled for experimental
				// for (const item of this.$refs.formHTML)
				// {
				// 	console.log(item);
				// }

				// Get class button name to change the button to button loading state .
				document.getElementsByClassName("btn-submit-login")[0].insertAdjacentHTML("beforebegin", "<a class=\"btn btn-secondary btn-submit-loading rounded-pill p-2 w-100\">Submitting <div class=\"spinner-border spinner-border-sm text-light ml-1\" role=\"status\"><span class=\"sr-only\">Loading...</span></div></a>");
				document.getElementsByClassName("btn-submit-login")[0].remove();

				axios(
				{
					url: formActionURL,
					method: formMethod,
					data: formData,
					headers: { "Content-Type": "multipart/form-data", 'X-Requested-With': 'XMLHttpRequest' }
				})
				.then(response => {
					if (response.data.status == 'success') 
					{
						window.setTimeout(function() 
						{
							window.location.href = response.data.redirect_url;
						}, 500);

						// We use toast from Bootstrap 5
						let toastBox = document.getElementsByClassName("ph-notice-toast")[0];

						let toast = new bootstrap.Toast(toastBox);
						toast.hide();

						document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<a class=\"btn btn-success btn-logged rounded-pill p-2 w-100\">Success <i class=\"far fa-check-circle fa-fw mr-1\"></i></div></a>");
						document.getElementsByClassName("btn-submit-loading")[0].remove();

						// console.log(response.data);
					}
					else if (response.data.status == 'failed') 
					{
						if (response.data.message instanceof Object == true ||
							response.data.message instanceof Array == true) 
						{
							this.isArrayMessageAfterSubmit = 1;
						}
						else 
						{
							this.isArrayMessageAfterSubmit = 0;
						}

						this.responseMessageAfterSubmit = response.data.message;

						// We use toast from Bootstrap 5
						let toastBox = document.getElementsByClassName("ph-notice-toast")[0];

						let toast = new bootstrap.Toast(toastBox);
						toast.show();

						document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<input type=\"submit\" class=\"btn btn-primary btn-submit-login rounded-pill p-2 w-100\" value=\"Login\">");
						document.getElementsByClassName("btn-submit-loading")[0].remove();

						// console.log(response.data.message instanceof Object);
					}
				})
				.catch(error => 
				{
					if (error.response.data.message instanceof Object == true ||
						error.response.data.message instanceof Array == true) 
					{
						this.isArrayMessageAfterSubmit = 1;
					}
					else {
						this.isArrayMessageAfterSubmit = 0;
					}

					if (error.response !== undefined) 
					{
						this.responseMessageAfterSubmit = error.response.data.message;
					}
					else 
					{
						this.responseMessageAfterSubmit = error.message;
					}

					// We use toast from Bootstrap 5
					let toastBox = document.getElementsByClassName("ph-notice-toast")[0];

					let toast = new bootstrap.Toast(toastBox);
					toast.show();

					document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<input type=\"submit\" class=\"btn btn-primary btn-submit-login rounded-pill p-2 w-100\" value=\"Login\">");
					document.getElementsByClassName("btn-submit-loading")[0].remove();
				});
			}
		}
	}).mount('#ph-app-auth');

const AuthVue3Demo = createApp(
	{
		data() {
			return {
				responseData: {},
				responseMessage: 'Hallo',
				responseStatus: ''
			}
		},
		components:
		{
			paginate: VuejsPaginateNext,
		},
		methods:
		{
			listData: function () {
				if (document.querySelector(".ph-fetch-listdata") !== null &&
					document.querySelector(".ph-fetch-listdata").getAttribute("data-url") !== null) {
					const url = document.querySelector(".ph-fetch-listdata").getAttribute("data-url");

					axios.get(url)
						.then(response => {
							this.responseData = response.data.data;

							console.log(response.data);
						})
						.catch(function (error) {
							console.log(error.response);
						});
				}
			},
			paginate: function () {

			},
			dataTables: function () {
				// $(document).ready( function ()
				// {
				const table = new DataTable('.myTable2', {
					data: [{ name: 'Andhika', position: 'Leader', salary: '20M' }, { name: 'Andhika 2', position: 'Leader', salary: '20M' }, { name: 'Andhika', position: 'Leader', salary: '20M' }],
					columns: [
						{ data: 'name' },
						{ data: 'position' },
						{ data: 'salary' }
					]
				});

				// console.log(table);
				// });
			}
		},
		mounted() {
			this.listData();

			this.dataTables();
		}
	}).mount('#ph-app-demo');

// START: Basic Model Function
const BasicFunctionalityVue3 = createApp({
	data() {
		return {
			responseData: [],
			responseDetailData: [],
			responseMessage: '',
			responseStatus: '',
			getCurrentPage: '',
			pageUrl: '',
			getData: '',
			getTotalData: '',
			loading: '',
			loadingnextpage: '',
			pageCount: '',
			pageRange: '',
			showModal: false,
		}
	},
	components: {
		paginate: VuejsPaginateNext,
	},
	methods: {
		listData: function () {
			if (
				document.querySelector(".ph-fetch-listdata") !== null &&
				document.querySelector(".ph-fetch-listdata").getAttribute("data-url") !== null
			) {
				const url = document.querySelector(".ph-fetch-listdata").getAttribute("data-url");
				axios.get(url)
					.then(response => {
						this.responseData = response.data.data;
						this.getTotalData = response.data.total;
						this.pageCount = response.data.total_page;
						this.pageRange = response.data.limit;
						this.responseStatus = response.data.status;
						this.responseMessage = response.data.message;

						// console.log(this.responseData);
					})
					.catch(function (error) {
						this.responseStatus = response.data.status;
						this.responseMessage = response.data.message;

						console.log(error.response);
					})
					.finally(() => {
						this.loadComplete();
						console.log(this.responseStatus);
						console.log(this.responseMessage);
					});
			}
		},
		searchData: _.debounce(function () {
			const getData = this.getData.trim();

			if (
				document.querySelector(".ph-fetch-listdata") !== null &&
				document.querySelector(".ph-fetch-listdata").getAttribute("data-url") !== null
			) {
				this.loadingnextpage = true;

				const url = document.querySelector(".ph-fetch-listdata").getAttribute("data-url");

				axios.get(url + '?fullname=' + getData)
					.then(response => {
						this.responseData = response.data.data;
						this.getTotalData = response.data.total;
						this.pageCount = response.data.total_page;
						this.pageRange = response.data.limit;
						this.responseStatus = response.data.status;
						this.responseMessage = response.data.message;

						console.log(this.responseStatus);
					})
					.catch(function (error) {
						this.responseStatus = error.data.status;
						this.responseMessage = error.data.message;
					})
					.finally(() => {
						this.loadComplete();
					});
			}
		}, 500),
		// To use this function, you can use our custom directive to activate
		// Our custom directive is v-debounce:1s="YOUR_FUNCTION"
		searchDataWithVueDebounce: function () {

			const getData = this.getData.trim();
			
			if (
				document.querySelector(".ph-fetch-listdata") !== null &&
				document.querySelector(".ph-fetch-listdata").getAttribute("data-url") !== null
			) {
				this.loadingnextpage = true;

				const url = document.querySelector(".ph-fetch-listdata").getAttribute("data-url");

				axios.get(`${url}?fullname=${getData}&email=${getData}`)
					.then(response => {
						this.responseData = response.data.data;
						this.getTotalData = response.data.total;
						this.pageCount = response.data.total_page;
						this.pageRange = response.data.limit;
						this.responseStatus = response.data.status;
						this.responseMessage = response.data.message;

						console.log(this.responseData);
					})
					.catch(function (error) {
						this.responseStatus = response.data.status;
						this.responseMessage = response.data.message;

						console.log(error.response);
					})
					.finally(() => {
						this.loadComplete();
					});
			}
		},
		clickPaginate: async function (page) {
			if (document.querySelector(".ph-fetch-listdata") !== null &&
				document.querySelector(".ph-fetch-listdata").getAttribute("data-url") !== null) {
				const url = document.querySelector(".ph-fetch-listdata").getAttribute("data-url");

				this.pageUrl = '?page=' + page;
				if (this.getData !== null && this.getData !== "") {
					const keyword = this.getData.trim();
					this.pageUrl += '&fullname=' + keyword + '&email=' + keyword;
				}

				this.loadingnextpage = true;

				await axios.get(url + this.pageUrl)
					.then(response => {
						// const getval = response.data.data.slice(-1)[0];

						if (this.getCurrentPage >= this.pageCount) {
							this.getCurrentPage = '';
						}

						this.responseData = response.data.data;
						this.getTotalData = response.data.total;
						this.pageCount = response.data.total_page;
						this.pageRange = response.data.limit;

						document.querySelector("#dataIndex").scrollIntoView(true);
					})
					.catch(function (error) {
						this.responseMessage = error;
						console.log(error);
					})
					.finally(() => {
						this.loading = false;
						this.loadingnextpage = false;
					});
			}
		},
		loadComplete: function () {
			this.loading = false;
			this.loadingnextpage = false;

			if (document.querySelector(".ph-data-status") !== null) {
				if (getComputedStyle(document.querySelector('.ph-data-status'), null).display == 'none') {
					document.querySelector(".ph-data-status").style.display = 'block';
				}
			}


			if (document.querySelector(".ph-data-load") !== null) {
				if (getComputedStyle(document.querySelector('.ph-data-load'), null).display == 'none') {
					document.querySelector(".ph-data-load").style.display = 'block';
				}
			}

			if (document.querySelector(".ph-total-data-load") !== null) {
				if (getComputedStyle(document.querySelector('.ph-total-data-load'), null).display ==
					'none') {
					document.querySelector(".ph-total-data-load").style.display = 'block';
				}
			}
		},
		submitData: async function (event) {
			event.preventDefault();

			// Get id form submit
			let getIdFormSubmit = document.getElementById("ph-form-submit");

			// Get value of attribute in HTML.
			let formActionURL = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("action");
			let formMethod = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("method");


			// FormData objects are used to capture HTML form and submit it using fetch or another network method.
			let formData = new FormData(this.$refs.formHTML);

			// Get class button name to change the button to button loading state .
			document.getElementsByClassName("btn-submit")[0].insertAdjacentHTML(
				"beforebegin",
				"<a class=\"btn btn-secondary btn-submit-loading p-2\">Submitting <div class=\"spinner-border spinner-border-sm text-light ml-1\" role=\"status\"><span class=\"sr-only\">Loading...</span></div></a>"
			);
			document.getElementsByClassName("btn-submit")[0].remove();

			axios({
				url: formActionURL,
				method: formMethod,
				data: formData,
				headers: {
					"Content-Type": "multipart/form-data",
					'X-Requested-With': 'XMLHttpRequest'
				}
			})
				.then(response => {
					const formSubmitModal = bootstrap.Modal.getOrCreateInstance("#formSubmitModal");

					if (formSubmitModal) {
						awaiformSubmitModal.hide();
					}
					
					document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML(
						"beforebegin",
						"<a class=\"btn btn-success btn-logged p-2\">Success <i class=\"far fa-check-circle fa-fw mr-1\"></i></div></a>"
					);
					document.getElementsByClassName("btn-submit-loading")[0].remove();

					console.log(response.data);
				})
				.catch(error => {
					document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML(
						"beforebegin",
						'<button type="submit" class="btn btn-success btn-submit"><i class="fad fa-save me-2"></i>Save</button>'
					);
					document.getElementsByClassName("btn-submit-loading")[0].remove();

					console.log(error.response.data);
				});
		},
		getDetailData: function()
		{
			if (document.querySelector(".ph-fetch-list-detaildata") !== null &&
				document.querySelector(".ph-fetch-list-detaildata").getAttribute("data-url") !== null) 
			{
				const url = document.querySelector(".ph-fetch-list-detaildata").getAttribute("data-url");

				axios.get(url)
					.then(response => 
					{
						this.responseDetailData = response.data;
						this.responseStatus = response.data.status;
						this.responseMessage = response.data.message;

						console.log(this.responseDetailData);
					})
					.catch(function (error) 
					{
						this.responseStatus = response.data.status;
						this.responseMessage = response.data.message;

						console.log(error.response);
					})
					.finally(() => 
					{
						// Empty Code
					});
			}
		}
	},
	directives: {
		debounce: vueDebounce.vueDebounce({
			lock: true
		})
	},
	mounted() {
		this.listData();

		this.getDetailData();
	}
}).mount('#ph-app');
// END: Basic Model Function

const ListDataRolePermissionVue3 = createApp(
{
	data() 
	{
		return {
			responseData: [],
			responseDataAllPermissionForRole: [],
			responseDetailData: [],
			responseDetailDataRole: 
			{
				viewRoleModal: [],
				editRoleModal: [],
				deleteRoleModal: []
			},
			responseDetailDataPermission: 
			{
				addRolePermissionModal: [],
				viewRolePermissionModal: [],
				editRolePermissionModal: []
			},
			responseMessage: {},
			responseStatus: {},
			responseMessageAfterSubmit: '',
			responseStatusAfterSubmit: ref(false),
			successClass: 'text-bg-success',
			dangerClass: 'text-bg-danger',
			loadingData: true
		}
	},
	components: 
	{
		vSelect: window["vue-select"]
	},
	methods:
	{
		submitData: function(event)
		{
			event.preventDefault();

			// Get id form submit
			let getIdFormSubmit = document.getElementById("ph-submit-data-rp");

			// Get value of attribute in HTML.
			let formActionURL = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("action");
			let formMethod = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("method");

			// FormData objects are used to capture HTML form and submit it using fetch or another network method.
			let formData = new FormData(this.$refs.formHTML);

			// Get class button name to change the button to button loading state .
			document.getElementsByClassName("btn-submit-data")[0].insertAdjacentHTML("beforebegin", "<a class=\"btn btn-secondary btn-submit-loading font-size-inherit\">Submitting <div class=\"spinner-border spinner-border-sm text-light ml-1\" role=\"status\"><span class=\"sr-only\">Loading...</span></div></a>");
			document.getElementsByClassName("btn-submit-data")[0].remove();

			axios(
			{
				url: formActionURL,
				method: formMethod,
				data: formData,
				headers: { "Content-Type": "multipart/form-data", 'X-Requested-With': 'XMLHttpRequest' }
			})
			.then(response => 
			{
				if (response.data.status == 'success') 
				{
					if (response.data.message instanceof Object == true ||
						response.data.message instanceof Array == true) 
					{
						this.isArrayMessageAfterSubmit = 1;
					}
					else 
					{
						this.isArrayMessageAfterSubmit = 0;
					}

					this.listData();

					this.responseStatusAfterSubmit = true;
					this.responseMessageAfterSubmit = response.data.message;

					// We use toast from Bootstrap 5
					let toastBox = getIdFormSubmit.getElementsByClassName("ph-notice-toast")[0];

					let toast = new bootstrap.Toast(toastBox);

					window.setTimeout(function() 
					{
						toast.show();
					}, 100);

					getIdFormSubmit.getElementsByTagName("form")[0].reset();

					document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<input type=\"submit\" class=\"btn btn-primary btn-submit-data font-size-inherit\" value=\"Submit\">");
					document.getElementsByClassName("btn-submit-loading")[0].remove();

					// console.log(response.data);
				}
				else if (response.data.status == 'failed') 
				{
					if (response.data.message instanceof Object == true ||
						response.data.message instanceof Array == true) 
					{
						this.isArrayMessageAfterSubmit = 1;
					}
					else 
					{
						this.isArrayMessageAfterSubmit = 0;
					}

					this.responseStatusAfterSubmit = false;
					this.responseMessageAfterSubmit = response.data.message;

					// We use toast from Bootstrap 5
					let toastBox = getIdFormSubmit.getElementsByClassName("ph-notice-toast")[0];

					let toast = new bootstrap.Toast(toastBox);
					
					window.setTimeout(function() 
					{
						toast.show();
					}, 100);

					document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<input type=\"submit\" class=\"btn btn-primary btn-submit-data font-size-inherit\" value=\"Submit\">");
					document.getElementsByClassName("btn-submit-loading")[0].remove();

					// console.log(response.data.message instanceof Object);
				}
			})
			.catch(error => 
			{
				this.responseStatusAfterSubmit = ref(false);

				if (error.response.data.message instanceof Object == true ||
					error.response.data.message instanceof Array == true) 
				{
					this.isArrayMessageAfterSubmit = 1;
				}
				else 
				{
					this.isArrayMessageAfterSubmit = 0;
				}

				if (error.response !== undefined) 
				{
					this.responseStatusAfterSubmit = false;
					this.responseMessageAfterSubmit = error.response.data.message;
				}
				else 
				{
					this.responseStatusAfterSubmit = false;
					this.responseMessageAfterSubmit = error.message;
				}

				// We use toast from Bootstrap 5
				let toastBox = getIdFormSubmit.getElementsByClassName("ph-notice-toast")[0];

				let toast = new bootstrap.Toast(toastBox);
				
				window.setTimeout(function() 
				{
					toast.show();
				}, 100);

				document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<input type=\"submit\" class=\"btn btn-primary btn-submit-data font-size-inherit\" value=\"Submit\">");
				document.getElementsByClassName("btn-submit-loading")[0].remove();
			});
		},
		submitMultipleData: function(event, idSubmit)
		{
			event.preventDefault();

			// Get id form submit
			let getIdFormSubmit = document.getElementById("ph-submit-multipledata-"+idSubmit);

			// Get value of attribute in HTML.
			let formActionURL = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("action");
			let formMethod = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("method");

			// FormData objects are used to capture HTML form and submit it using fetch or another network method.
			let formData = new FormData(this.$refs['formHTML-'+idSubmit]);

			// Get class button name to change the button to button loading state .
			getIdFormSubmit.getElementsByClassName("btn-submit-multipledata")[0].insertAdjacentHTML("beforebegin", "<a class=\"btn btn-secondary btn-submit-loading font-size-inherit\">Submitting <div class=\"spinner-border spinner-border-sm text-light ml-1\" role=\"status\"><span class=\"sr-only\">Loading...</span></div></a>");
			getIdFormSubmit.getElementsByClassName("btn-submit-multipledata")[0].remove();

			axios(
			{
				url: formActionURL,
				method: formMethod,
				data: formData,
				headers: { "Content-Type": "multipart/form-data", 'X-Requested-With': 'XMLHttpRequest' }
			})
			.then(response => 
			{
				if (response.data.status == 'success') 
				{
					if (response.data.message instanceof Object == true ||
						response.data.message instanceof Array == true) 
					{
						this.isArrayMessageAfterSubmit = 1;
					}
					else 
					{
						this.isArrayMessageAfterSubmit = 0;
					}

					this.listData();

					this.responseStatusAfterSubmit = true;
					this.responseMessageAfterSubmit = response.data.message;

					// We use toast from Bootstrap 5
					let toastBox = getIdFormSubmit.getElementsByClassName("ph-notice-toast")[0];

					let toast = new bootstrap.Toast(toastBox);

					window.setTimeout(function() 
					{
						toast.show();
					}, 100);

					window.setTimeout(function() 
					{
						const modalMultipleFormData = bootstrap.Modal.getOrCreateInstance(getIdFormSubmit);

						modalMultipleFormData.hide();
					}, 1200);

					getIdFormSubmit.getElementsByTagName("form")[0].reset();

					getIdFormSubmit.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<input type=\"submit\" class=\"btn btn-primary btn-submit-multipledata font-size-inherit\" value=\"Submit\">");
					getIdFormSubmit.getElementsByClassName("btn-submit-loading")[0].remove();

					// console.log(response.data);
				}
				else if (response.data.status == 'failed') 
				{
					if (response.data.message instanceof Object == true ||
						response.data.message instanceof Array == true) 
					{
						this.isArrayMessageAfterSubmit = 1;
					}
					else 
					{
						this.isArrayMessageAfterSubmit = 0;
					}

					this.responseStatusAfterSubmit = false;
					this.responseMessageAfterSubmit = response.data.message;

					// We use toast from Bootstrap 5
					let toastBox = getIdFormSubmit.getElementsByClassName("ph-notice-toast")[0];

					let toast = new bootstrap.Toast(toastBox);
					
					window.setTimeout(function() 
					{
						toast.show();
					}, 100);

					getIdFormSubmit.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<input type=\"submit\" class=\"btn btn-primary btn-submit-multipledata font-size-inherit\" value=\"Submit\">");
					getIdFormSubmit.getElementsByClassName("btn-submit-loading")[0].remove();

					// console.log(response.data.message instanceof Object);
				}
			})
			.catch(error => 
			{
				this.responseStatusAfterSubmit = ref(false);

				if (error.response.data.message instanceof Object == true ||
					error.response.data.message instanceof Array == true) 
				{
					this.isArrayMessageAfterSubmit = 1;
				}
				else 
				{
					this.isArrayMessageAfterSubmit = 0;
				}

				if (error.response !== undefined) 
				{
					this.responseStatusAfterSubmit = false;
					this.responseMessageAfterSubmit = error.response.data.message;
				}
				else 
				{
					this.responseStatusAfterSubmit = false;
					this.responseMessageAfterSubmit = error.message;
				}

				// We use toast from Bootstrap 5
				let toastBox = getIdFormSubmit.getElementsByClassName("ph-notice-toast")[0];

				let toast = new bootstrap.Toast(toastBox);
				
				window.setTimeout(function() 
				{
					toast.show();
				}, 100);

				getIdFormSubmit.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<input type=\"submit\" class=\"btn btn-primary btn-submit-multipledata font-size-inherit\" value=\"Submit\">");
				getIdFormSubmit.getElementsByClassName("btn-submit-loading")[0].remove();
			});
		},
		closeModalMultipleData: function(idSubmit)
		{
			// Get id form submit
			let getIdFormSubmit = document.getElementById("ph-submit-multipledata-"+idSubmit);

			if (getIdFormSubmit !== null)
			{
				// We use toast from Bootstrap 5
				let toastBox = getIdFormSubmit.getElementsByClassName("ph-notice-toast")[0];
				
				let toast = bootstrap.Toast.getOrCreateInstance(toastBox);

				toast.hide();
			}

			console.log('closed');
		},
		listData: function()
		{
			if (
				document.querySelector(".ar-fetch-listdata-rp") !== null &&
				document.querySelector(".ar-fetch-listdata-rp").getAttribute("data-url") !== null) 
			{
				const url = document.querySelector(".ar-fetch-listdata-rp").getAttribute("data-url");

				axios.get(url)
				.then(response => 
				{
					this.responseData = response.data.data;
					this.responseStatus.role = response.data.status;
					this.responseMessage.role = response.data.message;

					console.log(this.responseStatus.role);
				})
				.catch(function (error) 
				{
					this.responseStatus.role = response.data.status;
					this.responseMessage.role = response.data.message;
				})
				.finally(() => 
				{	
					this.loadingData = false;

					if (this.loadingData == false)
					{
						window.setTimeout(function() 
						{
							if (document.querySelector(".ph-data-load-status") !== null) 
							{
								if (getComputedStyle(document.querySelector('.ph-data-load-status'), null).display == 'none') 
								{
									document.querySelector(".ph-data-load-status").style.display = 'block';

									
								}
							}

							// if (document.querySelector(".ph-data-load-content") !== null) 
							// {
							// 	if (getComputedStyle(document.querySelector('.ph-data-load-content'), null).display == 'none') 
							// 	{
							// 		document.querySelector(".ph-data-load-content").style.display = 'block';
							// 	}
							// }		
						}, 100);
					}
				});
			}
		},
		listDataAllPermissionForRole: function()
		{
			if (
				document.querySelector(".ar-fetch-listdata-permissionrole") !== null &&
				document.querySelector(".ar-fetch-listdata-permissionrole").getAttribute("data-url") !== null) 
			{
				const url = document.querySelector(".ar-fetch-listdata-permissionrole").getAttribute("data-url");

				axios.get(url)
				.then(response => 
				{
					this.responseDataAllPermissionForRole = response.data.data;
					this.responseStatus.permissionrole = response.data.status;
					this.responseMessage.permissionrole = response.data.message;
				})
				.catch(function (error) 
				{
					this.responseStatus.permissionrole = response.data.status;
					this.responseMessage.permissionrole = response.data.message;

					//console.log(error.response);
				})
				.finally(() => 
				{
					//console.log(this.responseStatus);
					//console.log(this.responseMessage);
				});
			}
		},
		detailDataRoles: function(KeyId, KeyValue)
		{
			if (
				document.querySelector(".ar-fetch-detail-multipledata-simple-"+KeyId+"-"+KeyValue) !== null &&
				document.querySelector(".ar-fetch-detail-multipledata-simple-"+KeyId+"-"+KeyValue).getAttribute("data-url") !== null) 
			{
				const url = document.querySelector(".ar-fetch-detail-multipledata-simple-"+KeyId+"-"+KeyValue).getAttribute("data-url");
				// const id = document.querySelector(".ar-fetch-detaildata-simple-"+KeyId+"-"+KeyValue).getAttribute("data-id");

				axios.get(url+'/'+KeyValue)
				.then(response => 
				{
					// this.responseData = response.data.data;
					
					if (KeyId == 'viewRoleModal')
					{
						this.responseDetailDataRole.viewRoleModal = response.data.data;

						// console.log(this.responseDetailDataRole.viewRoleModal);
					}
					else if (KeyId == 'editRoleModal')
					{
						this.responseDetailDataRole.editRoleModal = response.data.data;

						// console.log(this.responseDetailDataRole.editRoleModal);
					}
					else if (KeyId == 'deleteRoleModal')
					{
						this.responseDetailDataRole.deleteRoleModal = response.data.data;

						// console.log(this.responseDetailDataRole.deleteRoleModal);
					}

					this.responseStatus = response.data.status;
					this.responseMessage = response.data.message;

					// console.log(this.responseData);
				})
				.catch(function (error) 
				{
					this.responseStatus = error.response.data.status;
					this.responseMessage = error.response.data.message;

					console.log(error.response);
				})
				.finally(() => 
				{
					console.log(this.responseStatus);
					console.log(this.responseMessage);
				});
			}
		},
		detailDataPermissionRole: function(KeyId, KeyValue)
		{
			if (
				document.querySelector(".ar-fetch-detail-multipledata-simple-"+KeyId+"-"+KeyValue) !== null &&
				document.querySelector(".ar-fetch-detail-multipledata-simple-"+KeyId+"-"+KeyValue).getAttribute("data-url") !== null) 
			{
				const url = document.querySelector(".ar-fetch-detail-multipledata-simple-"+KeyId+"-"+KeyValue).getAttribute("data-url-2");

				axios.get(url+'/'+KeyValue)
				.then(response => 
				{
					if (KeyId == 'viewRoleModal')
					{
						this.responseDetailDataPermission.viewRolePermissionModal = response.data.data;

						// console.log(this.responseDetailDataMultiple2.viewRolePermissionModal);
					}
					else if (KeyId == 'editRoleModal')
					{
						this.responseDetailDataPermission.editRolePermissionModal = response.data.data;

						// console.log(this.responseDetailDataMultiple2.editRolePermissionModal);
					}

					this.responseStatus = response.data.status;
					this.responseMessage = response.data.message;

					// console.log(this.responseData);
				})
				.catch(function (error) 
				{
					this.responseStatus = error.response.data.status;
					this.responseMessage = error.response.data.message;

					//console.log(error.response);
				})
				.finally(() => 
				{
					//console.log(this.responseStatus);
					//console.log(this.responseMessage);
				});
			}
		},
		showModal: function(ModalId, DataId)
		{
			if (ModalId == 'viewRoleModal')
			{
				this.detailDataRoles(ModalId, DataId);
				this.detailDataPermissionRole(ModalId, DataId);

				// console.log(ModalId);
			}
			else if (ModalId == 'editRoleModal')
			{
				this.detailDataRoles(ModalId, DataId);
				this.detailDataPermissionRole(ModalId, DataId);

				// console.log(ModalId);
			}
			else if (ModalId == 'deleteRoleModal')
			{
				this.detailDataRoles(ModalId, DataId);

				// console.log(ModalId);
			}
		}
	},
	mounted: function()
	{
		this.listData();

		this.listDataAllPermissionForRole();
	}
}).mount('#ph-list-data-simple');
