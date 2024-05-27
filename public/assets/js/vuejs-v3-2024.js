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
				if (document.querySelector(".ar-fetch-listdata") !== null &&
					document.querySelector(".ar-fetch-listdata").getAttribute("data-url") !== null) {
					const url = document.querySelector(".ar-fetch-listdata").getAttribute("data-url");

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

// START: Fetch Data
const ListDataVue3 = createApp({
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
			newUser: {
				fullname: '',
				email: ''
			},
			showModal: false,
		}
	},
	components: {
		paginate: VuejsPaginateNext,
	},
	methods: {
		listData: function () {
			if (
				document.querySelector(".ar-fetch-listdata") !== null &&
				document.querySelector(".ar-fetch-listdata").getAttribute("data-url") !== null
			) {
				const url = document.querySelector(".ar-fetch-listdata").getAttribute("data-url");
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
				document.querySelector(".ar-fetch-listdata") !== null &&
				document.querySelector(".ar-fetch-listdata").getAttribute("data-url") !== null
			) {
				this.loadingnextpage = true;

				const url = document.querySelector(".ar-fetch-listdata").getAttribute("data-url");

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
				document.querySelector(".ar-fetch-listdata") !== null &&
				document.querySelector(".ar-fetch-listdata").getAttribute("data-url") !== null
			) {
				this.loadingnextpage = true;

				const url = document.querySelector(".ar-fetch-listdata").getAttribute("data-url");

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
			if (document.querySelector(".ar-fetch-listdata") !== null &&
				document.querySelector(".ar-fetch-listdata").getAttribute("data-url") !== null) {
				const url = document.querySelector(".ar-fetch-listdata").getAttribute("data-url");

				this.pageUrl = '?page=' + page;
				if (this.getData !== null && this.getData !== "") {
					const keyword = this.getData.trim();
					this.pageUrl += `&fullname=${keyword}&email=${keyword}`;
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

			if (document.querySelector(".ar-data-status") !== null) {
				if (getComputedStyle(document.querySelector('.ar-data-status'), null).display == 'none') {
					document.querySelector(".ar-data-status").style.display = 'block';
				}
			}


			if (document.querySelector(".ar-data-load") !== null) {
				if (getComputedStyle(document.querySelector('.ar-data-load'), null).display == 'none') {
					document.querySelector(".ar-data-load").style.display = 'block';
				}
			}

			if (document.querySelector(".ar-total-data-load") !== null) {
				if (getComputedStyle(document.querySelector('.ar-total-data-load'), null).display ==
					'none') {
					document.querySelector(".ar-total-data-load").style.display = 'block';
				}
			}
		},
		createData: async function () {
			this.loading = true; // Set loading state to true

			await axios.post('{{ route("api.v1.user.store") }}', this.newUser)
				.then(response => {
					this.responseMessage = 'User created successfully!';
					new bootstrap.Modal(document.getElementById('createUserModal'))
						.hide(); // Hide modal
					this.newUser = {
						fullname: '',
						email: ''
					}; // Reset form fields
				})
				.catch(error => {
					console.log(error.response.data);
				})
				.finally(() => {
					this.loading = false; // Reset loading state
				});

			this.listData();
		},
		createDataRevs: async function (event) {
			event.preventDefault();

			// Get id form submit
			let getIdFormSubmit = document.getElementById("ph-form-submit");

			// Get value of attribute in HTML.
			let formActionURL = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("action");
			let formMethod = getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("method");

			// FormData objects are used to capture HTML form and submit it using fetch or another network method.
			let formData = new FormData(this.$refs.formHTML);

			// Get class button name to change the button to button loading state .
			document.getElementsByClassName("btn-submit")[0].insertAdjacentHTML("beforebegin",
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
					const modalToggleNewUser = bootstrap.Modal.getOrCreateInstance(
						"#createUserModalRevs");

					modalToggleNewUser.hide();

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
						"<input type=\"submit\" class=\"btn btn-primary btn-submit p-2\" value=\"Login\">"
					);
					document.getElementsByClassName("btn-submit-loading")[0].remove();

					console.log(error.response.data);
				});
		},
		getDetailData: function()
		{
			if (document.querySelector(".ar-fetch-list-detaildata") !== null &&
				document.querySelector(".ar-fetch-list-detaildata").getAttribute("data-url") !== null) 
			{
				const url = document.querySelector(".ar-fetch-list-detaildata").getAttribute("data-url");

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
}).mount('#ph-list-data');
// END: Fetch Data

const ListDataSimpleVue3 = createApp(
{
	data() 
	{
		return {
			responseData: [],
			responseDetailData: [],
			responseMessage: '',
			responseStatus: '',
			responseMessageAfterSubmit: '',
			responseStatusAfterSubmit: ref(false),
			successClass: 'text-bg-success',
			dangerClass: 'text-bg-danger'
		}
	},
	methods:
	{
		submitData: function(event)
		{
			event.preventDefault();

			// Get id form submit
			let getIdFormSubmit = document.getElementById("ph-submit-data");

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

					this.listDataSimple();

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
		listDataSimple: function()
		{
			if (
				document.querySelector(".ar-fetch-listdata-simple") !== null &&
				document.querySelector(".ar-fetch-listdata-simple").getAttribute("data-url") !== null) 
			{
				const url = document.querySelector(".ar-fetch-listdata-simple").getAttribute("data-url");

				axios.get(url)
				.then(response => 
				{
					this.responseData = response.data.data;
					this.responseStatus = response.data.status;
					this.responseMessage = response.data.message;

					// console.log(this.responseData);
				})
				.catch(function (error) 
				{
					this.responseStatus = response.data.status;
					this.responseMessage = response.data.message;

					console.log(error.response);
				})
				.finally(() => 
				{
					console.log(this.responseStatus);
					console.log(this.responseMessage);
				});
			}
		}
	},
	mounted: function()
	{
		this.listDataSimple();
	}
}).mount('#ph-list-data-simple');
