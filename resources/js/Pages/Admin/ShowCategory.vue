<template>
    <app-layout :show-categories="false">
        <div class="container">
            <div class="row justify-content-center mt-4">
                <div class="col-md-7">
                    <div class="">
                        <div class="d-flex">
                            <div class="flex-fill">
                                <p class="lead">{{ category.name }}</p>
                            </div>
                            <div class="text-right">
                                <inertia-link :href="route('categories.edit', category.id)" class="btn btn-link">EDIT</inertia-link>
                            </div>
                        </div>
                        <p>{{ category.description }}</p>
                    </div>

                    <div class="border rounded-sm p-3 shadow-sm">
                        <div class="d-flex px-2 border-bottom py-2 mb-2">
                            <div class="flex-fill">Categories</div>
                            <div class="text-right">
                                 <inertia-link :href="route('categories.create_subcategory', category.id)" class="btn btn-link">ADD</inertia-link>
                            </div>
                        </div>

                        <div v-if="subcategories.length == 0" class="tex-muted">
                            No subcategory found!
                        </div>

                        <div v-else class="list-group list-flush">
                            <div v-for="subcategory in subcategories" :key="subcategory.id" class="list-item">
                                {{ subcategory.name }}
                                <inertia-link :href="route('categories.edit_subcategory', [category.id, subcategory.id])">Edit</inertia-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'

export default {
    props: ['category', 'subcategories'],
    components: {
        AppLayout,
    }
}
</script>
