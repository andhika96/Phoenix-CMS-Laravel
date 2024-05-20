@extends('themes.default.admin.admin_layout')

@section('content')
   <div id="ph-app-demo">
      
      <div class="display d-none">
         <table id="myTable2" class="myTable2 display"></table>
      </div>

      <div class="ar-fetch-listdata" data-url="{{ url('dashboard/listdata') }}">
         <div class="table-responsive">
            <table class="table table-striped table-hover">
               <thead>
                  <tr>
                     <th scope="col" style="width: 5%">ID</th>
                     <th scope="col" style="width: 20%">Username</th>
                     <th scope="col">Fullname</th>
                  </tr>
               </thead>

               <tbody>
                  <tr class="text-nowrap" v-for="(info, index) in responseData" v-bind:key="info.id">
                     <td class="align-middle"># @{{ info.id }}</td>
                     <td class="align-middle">@{{ info.title }}</td>
                     <td class="align-middle">-</td>
                  </tr>
               </tbody>
            </table>
         </div>

         <paginate :page-count="10" :container-class="pagination" :prev-text="prev" :next-text="next" :click-handler="paginate"></paginate>
      </div>

   </div>
@endsection