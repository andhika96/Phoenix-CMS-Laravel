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
			responseMessage: '<strong>Hello World!</strong>',
			responseStatus: ''
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
				window.setTimeout(function() 
				{
					window.location.href = response.data.redirect_url;
				}, 500);

				document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<a class=\"btn btn-success btn-logged rounded-pill p-2 w-100\">Success <i class=\"far fa-check-circle fa-fw mr-1\"></i></div></a>");
				document.getElementsByClassName("btn-submit-loading")[0].remove();

				console.log(response.data);
			})
			.catch(error =>
			{
				document.getElementsByClassName("btn-submit-loading")[0].insertAdjacentHTML("beforebegin", "<input type=\"submit\" class=\"btn btn-primary btn-submit-login rounded-pill p-2 w-100\" value=\"Login\">");
				document.getElementsByClassName("btn-submit-loading")[0].remove();

				console.log(error.response.data);
			});
		},
		testingClick: function(value)
		{
			console.log(value);
		}
	},
	created()
	{
		this.responseMessage;
	}
}).mount('#ph-app-auth');