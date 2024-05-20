const { createApp, ref, reactive, defineModel } = Vue

createApp(
{
	setup() 
	{
		const message = ref('Hello vue!')

		return {
			message
		}
	}
}).mount('#app');

const AuthVue3 = createApp(
{
	data()
	{
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
		login: function(event)
		{
			event.preventDefault();

			// Get id form submit
			let getIdFormSubmit = document.getElementById("ph-form-login-submit");

			// Get value of attribute in HTML.
			let formActionURL 	= getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("action"); 
			let formMethod 		= getIdFormSubmit.getElementsByTagName("form")[0].getAttribute("method");
			
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
				headers: {"Content-Type": "multipart/form-data", 'X-Requested-With': 'XMLHttpRequest'}
			})
			.then(response => 
			{
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
				else
				{
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

const AuthVueDemo = createApp(
{
	data()
	{
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
		listData: function()
		{
			if (document.querySelector(".ar-fetch-listdata") !== null && 
				document.querySelector(".ar-fetch-listdata").getAttribute("data-url") !== null)
			{
				const url = document.querySelector(".ar-fetch-listdata").getAttribute("data-url");

				axios.get(url)
				.then(response => 
				{
					this.responseData = response.data.data;

					console.log(response.data);
				})
				.catch(function(error) 
				{
					console.log(error.response);
				});
			}
		},
		paginate: function()
		{

		},
		dataTables: function()
		{
			// $(document).ready( function ()
			// {
				const table = new DataTable('.myTable2', {
				    data: [ {name: 'Andhika', position: 'Leader', salary: '20M'}, {name: 'Andhika 2', position: 'Leader', salary: '20M'}, {name: 'Andhika', position: 'Leader', salary: '20M'} ],
				    columns: [
				        { data: 'name' },
				        { data: 'position' },
				        { data: 'salary' }
				    ]
				} );

				// console.log(table);
			// });
		}
	},
	mounted()
	{
		this.listData();

		this.dataTables();
	}
}).mount('#ph-app-demo');