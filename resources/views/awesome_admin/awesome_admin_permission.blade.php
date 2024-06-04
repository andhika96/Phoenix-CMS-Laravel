@extends('themes.default.admin.admin_layout')

@section('content')
	<div class="arv6-box p-4" id="ph-list-data-simple">
		<div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-2 border-bottom">
			<div class="h5 mb-0">
				<i class="fad fa-project-diagram fa-fw me-1"></i> Manage Permission
			</div>
		</div>

		<div class="row gx-5 gy-3 gy-lg-3 gx-lg-4 mt-0">
			<div class="col-12 col-lg-6 ar-fetch-listdata-rp" id="ar-fetch-listdata-rp" data-url="{{ url('awesome_admin/permission/listdata') }}">
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
								<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ph-submit-multipledata-editPermissionModal" :class="'ar-fetch-detail-multipledata-permission-editPermissionModal-'+item.id" data-url="{{ url('awesome_admin/permission/detaildata/') }}" v-on:click="showModal('editPermissionModal', item.id)">Edit</a>
							</span>
						</li>
					</ul>
				</div>

				<!--- Edit Permission Modal--->
				<div class="modal fade" id="ph-submit-multipledata-editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
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
							<form :action="'{{ url('awesome_admin/permission/edit') }}/'+responseDetailDataPermission.editPermissionModal.id" method="post" @submit="submitMultipleData($event, 'editPermissionModal')" ref="formHTML-editPermissionModal">
								<div class="modal-header">
									<h5 class="modal-title">Edit Permission</h5>
								</div>

								<div class="modal-body" id="ar-fetch-detail-multipledata-permission-editPermissionModal">
									<div v-if="loadingData.detailDataPermission" class="text-center p-5">
										<div class="spinner-border text-primary mb-2" role="status">
											<span class="sr-only"></span>
										</div>

										<div class="h6">Loading ...</div>
									</div>

									<div v-else-if="responseStatus.detailDataPermission == 'failed'" class="ph-data-load-status" style="display: none">
										@{{ responseMessage.detailDataPermission }}
									</div>

									<div v-else class="ph-data-load-content" style="display: none">
										<div class="form-group mb-3">
											<label class="form-label">Role Name</label>
											<input type="text" name="permission_name" class="form-control" v-model="responseDetailDataPermission.editPermissionModal.name">
										</div>
									</div>								
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary font-size-inherit" data-bs-dismiss="modal" v-on:click="closeModalMultipleData('editPermissionModal')">Close</button>
									<button type="submit" class="btn btn-primary btn-submit-multipledata font-size-inherit">Save changes</button>
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
								<div class="toast ph-notice-toast" :class="[responseStatusAfterSubmit ? '' : dangerClass, successClass]" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
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

					<form action="{{ url('awesome_admin/permission') }}" method="post" @submit="submitData" ref="formHTML">
						<div class="form-group mb-3">
							<label class="form-label">Permission Name</label>
							<input type="text" name="permission_name" class="form-control">
						</div>

						<div class="form-group text-end">
							<input type="submit" class="btn btn-primary btn-submit-data font-size-inherit" value="Submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection