<template>
    <app-layout :show-categories="false">
        <div v-if="!vendors.length == 0" class="border p-3 rounded-md lead">
            No vendor found at the moment!
        </div>
        <div v-else class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Owner</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr v-for="vendor in vendors" :key="vendor.id">
                                        <td>{{ vendor.name }}</td>
                                        <td>Address</td>
                                        <td>
                                            {{ vendor.user.full_name }}<br>
                                            {{ vendor.user.email }}
                                        </td>
                                        <td>{{ vendor.status }}</td>
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
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>