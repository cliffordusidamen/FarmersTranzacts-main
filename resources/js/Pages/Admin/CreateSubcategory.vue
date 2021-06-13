<template>
    <app-layout :show-categories="false">
        <div class="container">
            <div class="row justify-content-center mt-4">
                <div class="col-md-7">
                    <h3 class="text-center">{{ undefined == subcategory ? 'Create' : 'Edit' }} Subcategory</h3>
                    <subcategory-form :category="category ?? {}"  :subcategory="subcategory ?? {}" />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import SubcategoryForm from '@/Components/Forms/SubcategoryForm'


export default {
    props: {
        user: {
            required: true,
            type: Object,
        },
        category: {
            required: true,
            type: Object,
        },
        subcategory: {
            type: Object,
            default: () => ({}),
        },
    },

    components: {
        AppLayout,
        SubcategoryForm,
    },
}
</script>
