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
			responseStatus: ''
		}
	},
	methods: 
	{
		login: function(event)
		{
			event.preventDefault();
			
			// FormData objects are used to capture HTML form and submit it using fetch or another network method.
			let formData = new FormData(this.$refs.formHTML);

			console.log(formData);
		}
	},
	mounted()
	{
		console.log(this.responseMessage);
	}
}).mount('#app2');

// AuthVue3.mount('#app2');