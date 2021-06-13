<template>
    <app-layout :show-categories="false">
        <div class="container">
            <div class="row justify-content-center mt-4">
                <div class="col-md-7">
                    <h3 class="text-center">{{ undefined == $page.props.category ? 'Create' : 'Edit' }} Category</h3>
                    <category-form :category="$page.props.category ?? {}" />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import CategoryForm from '@/Components/Forms/CategoryForm'


export default {
    props: ['user'],

    components: {
        AppLayout,
        CategoryForm,
    },
}
</script>
