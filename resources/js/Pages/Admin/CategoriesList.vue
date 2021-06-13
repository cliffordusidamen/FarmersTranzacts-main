<template>
    <app-layout :show-categories="false">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="d-flex py-2 mt-2">
                        <h3 class="flex-fill">Product categories</h3>
                        <span>
                            <inertia-link :href="route('categories.create')" class="btn btn-link">
                                <span class="fa fa-plus"></span>
                                Add category
                            </inertia-link>
                        </span>
                    </div>

                    <div class="card">
                        <div v-if="$page.props.product_categories.length == 0" class="card-body">
                            No category found!
                        </div>
                        <div v-else>
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold">Name</th>
                                        <th class="font-weight-bold">Subcategories</th>
                                        <th class="text-right font-weight-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="category in $page.props.product_categories" :key="category.id">
                                        <td>{{ category.name }}</td>
                                        <td>{{ category.subcategories.length }}</td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuSizeButton"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Actions
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuSizeButton">
                                                        <inertia-link class="dropdown-item text-left font-size-normal" :href="route('categories.show', category.id)">Details</inertia-link>
                                                        <inertia-link class="dropdown-item text-left font-size-normal" :href="route('categories.edit', category.id)">Edit</inertia-link>
                                                        <inertia-link class="dropdown-item text-left font-size-normal" :href="route('categories.create_subcategory', category.id)">Add subcategory</inertia-link>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout"

export default {
    props: ["categories"],
    components: {
        AppLayout,
    }
}
</script>
