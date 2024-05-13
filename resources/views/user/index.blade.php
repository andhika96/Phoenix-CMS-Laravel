@extends('themes.default.layout')

@section('content')
    <div id="userIndex">
        <div class="ar-fetch-listdata" data-url="{{ route('api.v1.user.index') }}">
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
                        <td class="align-middle">#@{{ info.id }}</td>
                        <td class="align-middle">@{{ info.username }}</td>
                        <td class="align-middle">@{{ info.fullname }}</td>
                    </tr>
                </tbody>
            </table>
            <paginate :page-count="pageCount" :page-range="pageRange" :click-handler="clickPaginate" :prev-text="'Prev'" :next-text="'Next'" :container-class="'pagination'" v-model="getCurrentPage">
            </paginate>
        </div>
    </div>
@endsection

@pushonce('js')
    <script>
        const AuthVueDemo2 = createApp({
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

                                console.log(this.responseData);
                            })
                            .catch(function(error) {
                                console.log(error.response);
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
                                console.log(error);
                            })
                            .finally(() => {
                                this.loading = false;
                                this.loadingnextpage = false;
                            });
                    }
                },
            },

            mounted() {
                this.listData();
            }

        }).mount('#userIndex');
    </script>
@endpushonce
