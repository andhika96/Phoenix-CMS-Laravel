@extends('themes.default.admin.admin_layout')

@section('content')
	<style>
	.vs__selected
	{
		padding: .35em .5rem;
		border: var(--vs-selected-border-width) var(--vs-selected-border-style) var(--vs-selected-bg);
	}

	.vs__deselect
	{
		margin-left: .5rem;
	}

	.vs__actions 
	{
		padding: 6.5px 10px 0 3px;
	}

	.vs__dropdown-toggle 
	{
		border: var(--bs-border-width) solid var(--bs-border-color);
		border-radius: var(--bs-border-radius);
	}

	.vs__search
	{
		padding: .2rem .75rem;
	}
	</style>

	{{ Breadcrumbs::render('awesome_admin.role') }}

	<div class="arv6-box p-4" id="ph-list-data-simple">
		<div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-2 border-bottom">
			<div class="h5 mb-0">
				<i class="fad fa-project-diagram fa-fw me-1"></i> Manage Role
			</div>
		</div>

		<div class="row gx-5 gy-3 gy-lg-3 gx-lg-4 mt-0 ar-fetch-listdata-permissionrole" data-url="{{ url('awesome_admin/role/listdatapermission') }}">
			<div class="col-12 col-lg-6 ar-fetch-listdata-rp" id="ar-fetch-listdata-rp" data-url="{{ url('awesome_admin/role/listdata') }}">
				<div class="h6 mb-3"><i class="fad fa-list-ul fa-fw me-1"></i> List of Data</div>

				<div v-if="loadingData.listData" class="text-center p-5">
					<div class="spinner-border text-primary mb-2" role="status">
						<span class="sr-only"></span>
					</div>

					<div class="h6">Loading ...</div>
				</div>

				<div v-else-if="responseStatus.listData == 'failed'" class="ph-data-load-status" style="display: none">
					@{{ responseMessage.listData }}
				</div>

				<div v-else class="ph-data-load-content" style="display: none">
					<ul class="list-group list-group-flush">
						<li class="list-group-item" v-for="(item, index) in responseData">
							@{{ item.name }} 

							<span class="float-end">
								<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ph-submit-multipledata-viewRoleModal" :class="'ar-fetch-detail-multipledata-role-viewRoleModal-'+item.id" data-url="{{ url('awesome_admin/role/detaildata/') }}" data-url-2="{{ url('awesome_admin/role/detaildatapermission/') }}" v-on:click="showModal('viewRoleModal', item.id)">View</a>
								<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ph-submit-multipledata-editRoleModal" :class="'ar-fetch-detail-multipledata-role-editRoleModal-'+item.id" data-url="{{ url('awesome_admin/role/detaildata/') }}" data-url-2="{{ url('awesome_admin/role/detaildatapermission/') }}" v-on:click="showModal('editRoleModal', item.id)" class="mx-3">Edit</a>
								<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ph-submit-multipledata-deleteRoleModal" :class="'ar-fetch-detail-multipledata-role-deleteRoleModal-'+item.id" data-url="{{ url('awesome_admin/role/detaildata/') }}" v-on:click="showModal('deleteRoleModal', item.id)" class="text-danger">Delete</a>
							</span>
						</li>
					</ul>
				</div>

				<!--- View Role Modal--->
				<div class="modal fade" id="ph-submit-multipledata-viewRoleModal" tabindex="-1" aria-labelledby="viewRoleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Role </h5>
							</div>

							<div class="modal-body">

								<div class="form-group mb-3">
									<div class="fw-bold mb-2">Role Name:</div>

									@{{ responseDetailDataRole.viewRoleModal.name }}
								</div>

								<div>
									<div class="fw-bold mb-2">List Permission:</div>

									<ul class="list-group list-group-flush">
										<li v-for="item in responseDetailDataPermission.viewRolePermissionModal" class="list-group-item">
											@{{ item }}
										</li>	
									</ul>
								</div>
							</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-secondary font-size-inherit" data-bs-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>

				<!--- Edit Role Modal--->
				<div class="modal fade" id="ph-submit-multipledata-editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
					<div class="ph-notice">
						<div aria-live="polite" aria-atomic="true" class="position-relative">
							<div class="toast-container position-fixed top-0 end-0 p-3">
								<div class="toast ph-notice-toast" :class="[responseStatusAfterSubmit ? '' : dangerClass, successClass]" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
									<div class="toast-header border-0" :class="[responseStatusAfterSubmit ? '' : dangerClass, successClass]" data-bs-theme="dark">
										<strong class="me-auto">Notice</strong>
										<small>just now</small>
										<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" style="margin-right: calc(-.1 * var(--bs-toast-padding-x));"></button>
									</div>
								
									<div class="toast-body text-start">
										<div v-if="isArrayMessageAfterSubmit == 1">
											<ul class="ps-3 m-0">
												<li v-for="(item, index) in responseMessageAfterSubmit">
													@{{ item[0] }}
												</li>
											</ul>
										</div>

										<div v-else>
											@{{ responseMessageAfterSubmit }}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<form :action="'{{ url('awesome_admin/role/edit') }}/'+responseDetailDataRole.editRoleModal.id" method="post" @submit="submitMultipleData($event, 'editRoleModal')" ref="formHTML-editRoleModal">
								<div class="modal-header">
									<h5 class="modal-title">Edit Role </h5>
								</div>

								<div class="modal-body" id="ar-fetch-detail-multipledata-role-editRoleModal">
									<div v-if="loadingData.detailDataRole" class="text-center p-5">
										<div class="spinner-border text-primary mb-2" role="status">
											<span class="sr-only"></span>
										</div>

										<div class="h6">Loading ...</div>
									</div>

									<div v-else-if="responseStatus.detaildataRole == 'failed'" class="ph-data-load-status" style="display: none">
										@{{ responseMessage.detailDataRole }}
									</div>

									<div v-else class="ph-data-load-content" style="display: none">
										<div class="form-group mb-3">
											<label class="form-label">Role Name</label>
											<input type="text" name="role_name" class="form-control" v-model="responseDetailDataRole.editRoleModal.name">
										</div>

										<div class="form-group mb-3">
											<label class="form-label">Permission</label>

											<v-select :options="responseDataAllPermissionForRole" v-model="responseDetailDataRole.editRoleModal.permissions" class="font-size-inherit" multiple>
												<template #open-indicator="{ attributes }">
													<span v-bind="attributes"><i class="fal fa-angle-down fa-lg"></i></span>
												</template>
											</v-select>

											<input type="hidden" name="permissions" class="form-control font-size-inherit" :value="responseDetailDataRole.editRoleModal.permissions">
										</div>
									</div>								
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary font-size-inherit" data-bs-dismiss="modal" v-on:click="closeModalMultipleData('editRoleModal')">Close</button>
									<button type="submit" class="btn btn-primary btn-submit-multipledata font-size-inherit">Save changes</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!--- Delete Role Modal--->
				<div class="modal fade" id="ph-submit-multipledata-deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
					<div class="ph-notice">
						<div aria-live="polite" aria-atomic="true" class="position-relative">
							<div class="toast-container position-fixed top-0 end-0 p-3">
								<div class="toast ph-notice-toast" :class="[responseStatusAfterSubmit ? '' : dangerClass, successClass]" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
									<div class="toast-header border-0" :class="[responseStatusAfterSubmit ? '' : dangerClass, successClass]" data-bs-theme="dark">
										<strong class="me-auto">Notice</strong>
										<small>just now</small>
										<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" style="margin-right: calc(-.1 * var(--bs-toast-padding-x));"></button>
									</div>
								
									<div class="toast-body text-start">
										<div v-if="isArrayMessageAfterSubmit == 1">
											<ul class="ps-3 m-0">
												<li v-for="(item, index) in responseMessageAfterSubmit">
													@{{ item[0] }}
												</li>
											</ul>
										</div>

										<div v-else>
											@{{ responseMessageAfterSubmit }}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<form :action="'{{ url('awesome_admin/role/delete') }}/'+responseDetailDataRole.deleteRoleModal.id" method="post" @submit="submitMultipleData($event, 'deleteRoleModal')" ref="formHTML-deleteRoleModal">
								<div class="modal-header">
									<h5 class="modal-title">Delete Role</h5>
								</div>

								<div class="modal-body">
									<div class="h6 text-center">
										Are you sure to delete this item?
									</div>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary font-size-inherit" data-bs-dismiss="modal" v-on:click="closeModalMultipleData('deleteRoleModal')">Close</button>
									<button type="submit" class="btn btn-primary btn-submit-multipledata font-size-inherit">Confirm</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 col-lg-6">
				<div class="h6 mb-3"><i class="fad fa-plus fa-fw me-1"></i> Add New Data</div>

				<div id="ph-submit-data-rp">
					<div class="ph-notice">
						<div aria-live="polite" aria-atomic="true" class="position-relative">
							<div class="toast-container position-fixed top-0 end-0 p-3">
								<div class="toast ph-notice-toast" :class="[responseStatusAfterSubmit ? '' : dangerClass, successClass]" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
									<div class="toast-header border-0" :class="[responseStatusAfterSubmit ? '' : dangerClass, successClass]" data-bs-theme="dark">
										<strong class="me-auto">Notice</strong>
										<small>just now</small>
										<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" style="margin-right: calc(-.1 * var(--bs-toast-padding-x));"></button>
									</div>
								
									<div class="toast-body text-start">
										<div v-if="isArrayMessageAfterSubmit == 1">
											<ul class="ps-3 m-0">
												<li v-for="(item, index) in responseMessageAfterSubmit">
													@{{ item[0] }}
												</li>
											</ul>
										</div>

										<div v-else>
											@{{ responseMessageAfterSubmit }}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<form action="{{ url('awesome_admin/role') }}" method="post" @submit="submitData" ref="formHTML">
						<div class="form-group mb-3">
							<label class="form-label">Role Name</label>
							<input type="text" name="role_name" class="form-control">
						</div>

						<div class="form-group mb-3">
							<label class="form-label">Permission</label>

							<v-select :options="responseDataAllPermissionForRole" v-model="responseDetailDataPermission.addRolePermissionModal" class="font-size-inherit" multiple>
								<template #open-indicator="{ attributes }">
									<span v-bind="attributes"><i class="fal fa-angle-down fa-lg"></i></span>
								</template>
							</v-select>
						
							<input type="hidden" name="permissions" class="form-control font-size-inherit" :value="responseDetailDataPermission.addRolePermissionModal">
						</div>

						<div class="form-group text-end">
							<button type="submit" class="btn btn-primary btn-submit-data font-size-inherit">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection