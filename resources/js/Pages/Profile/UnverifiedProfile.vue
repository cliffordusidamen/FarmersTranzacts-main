<template>
    <app-layout>
        <div class="page-content py-4 bg-light">
            <div class="container">
                <update-profile-information-form :user="this.$page.props.user" />
            </div>
        </div>
    </app-layout>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import UpdateProfileInformationForm from '@/Pages/Profile/UpdateProfileInformationForm'

export default {
    components: {
        AppLayout,
        UpdateProfileInformationForm,
    },
    setup() {
        
    },
}
</script>