<template>
    <app-layout :show-categories="false">
        <div class="page-content py-4 bg-light">
            <div class="container">
                <div v-if="vendors.total == 0" class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="border bg-white p-3 rounded-md lead">
                            No vendor found at the moment!
                        </div>
                    </div>
                </div>
                <div v-else class="row">
                    <div class="col-md-10">
                        <div class="card">
                            <table class="table table-bordered table-sm admin-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Owner</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="vendor in vendors.data" :key="vendor.id">
                                        <td>{{ vendor.name }}</td>
                                        <td>Address</td>
                                        <td>
                                            {{ `${vendor.users[0].first_name} ${vendor.users[0].last_name}` }}<br>
                                            {{ vendor.users[0].email }}
                                        </td>
                                        <td>{{ vendor.approval_status }}</td>
                                        <td class="text-right">
                                            <div class="d-flex justify-content-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuSizeButton"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuSizeButton">
                                                        <a href="#" class="dropdown-item text-left font-size-normal">View</a>
                                                        <a v-if="vendor.status == 'awaiting approval'" href="#" class="dropdown-item text-left font-size-normal">Approve</a>
                                                        <a v-if="vendor.status == 'awaiting approval'" href="#" class="dropdown-item text-left font-size-normal">Disapprove</a>
                                                        <!-- <inertia-link class="dropdown-item text-left font-size-normal" :href="route('categories.create_subcategory', category.id)">Add subcategory</inertia-link> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-show="vendors.last_page > 1" class="card-footer">
                                <pagination :paginator="vendors" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout"
import Pagination from '@/Components/Pagination'

export default {
    props: ['vendors'],
    components: {
        AppLayout,
        Pagination,
    },
}
</script>