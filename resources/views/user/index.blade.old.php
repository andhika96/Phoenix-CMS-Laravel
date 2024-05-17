@extends('themes.default.layout')

@section('content')
    <div class="arv6-box bg-white p-3 p-md-4 rounded shadow-sm" id="userIndex">
        <div class="bg-white arv3-pc-content p-3 p-md-4 rounded shadow-sm" id="ar-app-listdata">
			<div class="h5 pb-3 pb-md-4 mb-3 border-bottom d-flex justify-content-between align-items-center">
            <div><i class="fad fa-calendar-star fa-fw me-2"></i> Accounts/Users</div> 
            <div>
                <div class="float-md-right font-size-normal">
                   <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fad fa-user-plus fa-fw me-2"></i> Create User
                    </button>
                </div>
            </div>
        </div>
			
        <div class="toast ar-notice-toast position-relative bg-transparent align-items-center border-0" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-autohide="false"></div>

        <div v-if="loading" class="text-center p-5">
            <div class="spinner-border text-primary mb-2" role="status">
                <span class="sr-only"></span>
            </div>

            <div class="h6">Loading ...</div>
        </div>

        <div v-else-if="responseStatus && !responseStatus.toString().startsWith('2')"
            class="ar-data-status text-center text-danger h5 p-5" style="display: none">
            @{{ responseMessage }}
        </div>


        <div v-else class="ar-data-load" style="display: none">
            <div class="ar-fetch-listdata" data-url="{{ route('api.v1.user.index') }}">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%">ID</th>
                            <th scope="col">User</th>
                            <th scope="col">Email Address</th>
                        </tr>
                    </thead>

                    <tbody is="transition-group" name="custom-classes-transition" enter-active-class="animate__animated animate__fadeIn animate__faster">
                        <tr class="text-nowrap" v-for="(info, index) in responseData" v-bind:key="info.id">
                            <td class="align-middle">#@{{ info.id }}</td>
                            <td class="align-middle">@{{ info.fullname }}</td>
                            <td class="align-middle">@{{ info.email }}</td>
                        </tr>
                    </tbody>
                </table>
                <paginate :page-count="pageCount" :page-range="pageRange" :click-handler="clickPaginate"
                    :prev-text="'Prev'" :next-text="'Next'" :container-class="'pagination'" v-model="getCurrentPage">
                </paginate>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="createData">
                            <div class="mb-3">
                                <label for="userFullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="userFullName" required>
                            </div>
                            <div class="mb-3">
                                <label for="userEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="userEmail" required>
                            </div>
                            <button type="submit" class="btn btn-primary" :disabled="loading">
                                <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span v-if="!loading">Create User</span>
                                <span v-else>Loading...</span>
                            </button>
                            <button type="button" class="btn btn-default" :disabled="loading"
                                data-bs-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@pushonce('js')
    <script>
        createApp({
            data() {
                return {
                    responseData: [],
                    responseMessage: '',
                    responseStatus: '',
                    getCurrentPage: '',
                    pageUrl: '',
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
                listData: function() {
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
                            .catch(function(error) {
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

                clickPaginate: async function(page) {
                    if (document.querySelector(".ar-fetch-listdata") !== null &&
                        document.querySelector(".ar-fetch-listdata").getAttribute("data-url") !== null) {
                        const url = document.querySelector(".ar-fetch-listdata").getAttribute("data-url");

                        this.pageUrl = '?page=' + page;

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

                                document.querySelector("#userIndex").scrollIntoView(true);
                            })
                            .catch(function(error) {
                                this.responseMessage = error;
                                console.log(error);
                            })
                            .finally(() => {
                                this.loading = false;
                                this.loadingnextpage = false;
                            });
                    }
                },

                loadComplete: function() {
                    this.loading = false;
                    this.loadingnextpage = false;

                    if (document.querySelector(".ar-data-status") !== null) {
                        if (getComputedStyle(document.querySelector('.ar-data-status'), null).display ==
                            'none') {
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

                createData: async function() {
                    this.loading = true;  // Set loading state to true

                    await axios.post('{{ route("api.v1.user.store") }}', this.newUser)
                        .then(response => {
                            this.responseMessage = 'User created successfully!';
                            new bootstrap.Modal(document.getElementById('createUserModal')).hide();  // Hide modal
                            this.newUser = { fullname: '', email: '' };  // Reset form fields
                        })
                        .catch(error => {
                            console.log(error.response.data);
                        })
                        .finally(() => {
                            this.loading = false;  // Reset loading state
                        });
                    
                    this.listData();
                },
            },

            mounted() {
                this.listData();
            }

        }).mount('#userIndex');
    </script>
@endpushonce
