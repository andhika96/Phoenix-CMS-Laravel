@extends('themes.default.admin.admin_layout')

@section('content')
    <div class="arv6-box p-4" id="ph-list-data">
        <div id="ar-app-listdata" class="ar-fetch-listdata" data-url="{{ route('api.v1.user.index') }}">
            <div class="mb-3">
                <div class="d-md-flex justify-content-between align-items-center border-bottom pb-3">
                    <div class="h5"><i class="fad fa-users fa-fw me-2"></i> Manage User Account</div>
                    <div class="row g-2">
                        <div class="col-md-auto text-md-end">
                            <input type="text" name="search_user" class="form-control font-size-inherit"
                                placeholder="Search.." v-model="getData" @keyup="searchData">
                        </div>
                        <div class="col-md-auto text-md=,kend">
                            <button type="button" class="btn btn-success font-size-inherit" data-bs-toggle="modal"
                                data-bs-target="#createUserModalRevs">
                                <i class="fad fa-user-plus fa-fw me-2"></i> Create User
                            </button>
                        </div>
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

            <div v-else-if="responseStatus === 'failed'" class="ar-data-status text-center text-danger h5 p-5"
                style="display: none;">
                @{{ responseMessage }}
            </div>

            <div v-else class="ar-data-load" style="display: none">
                <div v-if="loadingnextpage" class="text-center p-5">
                    <div class="spinner-border text-primary mb-2" role="status">
                        <span class="sr-only"></span>
                    </div>

                    <div class="h6">Loading ...</div>
                </div>

                <div v-else>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%">ID</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Email Address</th>
                                </tr>
                            </thead>

                            <tbody is="transition-group" name="custom-classes-transition"
                                enter-active-class="animate__animated animate__fadeIn animate__faster">
                                <tr class="text-nowrap" v-for="(info, index) in responseData" v-bind:key="info.id">
                                    <td class="align-middle">#@{{ info.id }}</td>
                                    <td class="align-middle">@{{ info.fullname }}</td>
                                    <td class="align-middle">@{{ info.email }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <paginate :page-count="pageCount" :page-range="pageRange" :click-handler="clickPaginate"
                    :prev-text="'Prev'" :next-text="'Next'" :container-class="'pagination'" v-model="getCurrentPage">
                </paginate>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
                aria-hidden="true">
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
                                    <span v-if="loading" class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
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

            <!-- Modal -->
            <div class="modal fade" id="createUserModalRevs" tabindex="-1" aria-labelledby="createUserModalRevsLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createUserModalRevsLabel">Create New User Revs</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="ph-form-submit">
                            <form action="{{ route('api.v1.user.store') }}" method="post" ref="formHTML"
                                @submit.prevent="createDataRevs">
                                <div class="mb-3">
                                    <label for="userFullName" class="form-label">Full Name</label>
                                    <input type="text" name="fullname" class="form-control" id="userFullName"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="userEmail" class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" id="userEmail" required>
                                </div>

                                <input type="submit" class="btn btn-primary btn-submit" value="Create User">
                                <button type="button" class="btn btn-default" :disabled="loading"
                                    data-bs-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="ar-fetch-list-detaildata" data-url="{{ route('api.v1.user.show', ['idOrSlug' => 1]) }}">
            <div v-for="(item, index) in responseDetailData.data">
                @{{ index }}

                <input type="text" :name="index" class="form-control" v-model="item">
            </div>
        </div>
    </div>
@endsection
